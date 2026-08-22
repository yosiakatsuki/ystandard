/* @jsxRuntime classic */
/* @jsx createElement */
import { PluginDocumentSettingPanel } from '@wordpress/editor';
import {
	Component,
	createElement,
	useEffect,
	useRef,
	useState,
} from '@wordpress/element';
import { addAction, applyFilters, removeAction } from '@wordpress/hooks';

export const SECTIONS_HOOK = 'ystandard.hooks.postSettings.sections';
export const ITEMS_HOOK = 'ystandard.hooks.postSettings.items';
export const BUILT_IN_SECTION_IDS = Object.freeze( {
	post: 'ystandard/post',
	seo: 'ystandard/seo',
	sns: 'ystandard/sns',
} );

const HOOK_ADDED = 'hookAdded';
const HOOK_REMOVED = 'hookRemoved';
const isDevelopment = 'production' !== process.env.NODE_ENV;
let listenerId = 0;

/**
 * 開発環境で無視した定義を通知する.
 *
 * @param {string} message 通知内容.
 * @param {*}      value   無視した値.
 */
const warnInvalidDefinition = ( message, value ) => {
	if ( isDevelopment ) {
		// eslint-disable-next-line no-console
		console.warn( `[yStandard post settings] ${ message }`, value );
	}
};

/**
 * セクション定義が正しいか判定する.
 *
 * @param {*} section セクション定義.
 * @return {boolean} 判定結果.
 */
const isValidSection = ( section ) =>
	section &&
	'object' === typeof section &&
	'string' === typeof section.id &&
	'' !== section.id.trim() &&
	'string' === typeof section.title &&
	'' !== section.title.trim() &&
	'number' === typeof section.order &&
	Number.isFinite( section.order );

/**
 * 設定項目定義が正しいか判定する.
 *
 * @param {*} item 設定項目定義.
 * @return {boolean} 判定結果.
 */
const isValidItem = ( item ) =>
	item &&
	'object' === typeof item &&
	'string' === typeof item.id &&
	'' !== item.id.trim() &&
	'string' === typeof item.section &&
	'' !== item.section.trim() &&
	'number' === typeof item.order &&
	Number.isFinite( item.order ) &&
	'function' === typeof item.Component;

/**
 * 定義を検証し、同じIDは後の定義を採用する.
 *
 * @param {*}        definitions フィルター適用後の定義.
 * @param {Function} validator   検証関数.
 * @param {string}   type        定義種別.
 * @return {Array} 検証・重複排除済みの定義.
 */
const normalizeDefinitions = ( definitions, validator, type ) => {
	if ( ! Array.isArray( definitions ) ) {
		warnInvalidDefinition(
			`${ type }フィルターの戻り値が配列ではありません。`,
			definitions
		);
		return [];
	}

	const normalized = new Map();
	definitions.forEach( ( definition, index ) => {
		if ( ! validator( definition ) ) {
			warnInvalidDefinition(
				`不正な${ type }定義を無視しました。`,
				definition
			);
			return;
		}

		normalized.set( definition.id, {
			...definition,
			index,
		} );
	} );

	return Array.from( normalized.values() ).sort(
		( first, second ) =>
			first.order - second.order || first.index - second.index
	);
};

/**
 * 外部設定の定義を取得する.
 *
 * @param {Object} context         共通コンテキスト.
 * @param {Array}  builtInSections yStandard標準セクション.
 * @return {Array} 表示するセクションと設定項目.
 */
export const getPostSettingsSections = ( context, builtInSections = [] ) => {
	let filteredSections = [];
	let filteredItems = [];

	try {
		filteredSections = applyFilters( SECTIONS_HOOK, [], context );
	} catch ( error ) {
		warnInvalidDefinition(
			'セクションフィルターの適用中にエラーが発生しました。',
			error
		);
	}

	try {
		filteredItems = applyFilters( ITEMS_HOOK, [], context );
	} catch ( error ) {
		warnInvalidDefinition(
			'設定項目フィルターの適用中にエラーが発生しました。',
			error
		);
	}

	const normalizedBuiltInSections = normalizeDefinitions(
		builtInSections,
		isValidSection,
		'標準セクション'
	);
	const builtInSectionIds = new Set( [
		...Object.values( BUILT_IN_SECTION_IDS ),
		...normalizedBuiltInSections.map( ( section ) => section.id ),
	] );
	const externalSections = normalizeDefinitions(
		filteredSections,
		isValidSection,
		'セクション'
	).filter( ( section ) => {
		if ( ! builtInSectionIds.has( section.id ) ) {
			return true;
		}

		warnInvalidDefinition(
			'予約済みの標準セクションと同じIDの定義を無視しました。',
			section
		);
		return false;
	} );
	const sections = [
		...normalizedBuiltInSections.map( ( section, index ) => ( {
			...section,
			index,
		} ) ),
		...externalSections.map( ( section, index ) => ( {
			...section,
			index: normalizedBuiltInSections.length + index,
		} ) ),
	].sort(
		( first, second ) =>
			first.order - second.order || first.index - second.index
	);
	const sectionIds = new Set( sections.map( ( section ) => section.id ) );
	const items = normalizeDefinitions(
		filteredItems,
		isValidItem,
		'設定項目'
	).filter( ( item ) => {
		if ( sectionIds.has( item.section ) ) {
			return true;
		}

		warnInvalidDefinition(
			'未登録のセクションを参照する設定項目を無視しました。',
			item
		);
		return false;
	} );

	return sections.map( ( section ) => ( {
		...section,
		items: items.filter( ( item ) => section.id === item.section ),
	} ) );
};

/**
 * 設定項目単位のエラー境界.
 */
class PostSettingsItemErrorBoundary extends Component {
	constructor( props ) {
		super( props );
		this.state = { hasError: false };
	}

	static getDerivedStateFromError() {
		return { hasError: true };
	}

	componentDidCatch( error ) {
		warnInvalidDefinition(
			`設定項目「${ this.props.itemId }」の描画中にエラーが発生しました。`,
			error
		);
	}

	componentDidUpdate( previousProps ) {
		if (
			this.state.hasError &&
			previousProps.resetKey !== this.props.resetKey
		) {
			this.setState( { hasError: false } );
		}
	}

	render() {
		if ( this.state.hasError ) {
			return null;
		}

		return this.props.children;
	}
}

/**
 * 投稿設定の登録変更を監視してセクションを取得する.
 *
 * @param {Object} context         共通コンテキスト.
 * @param {Array}  builtInSections yStandard標準セクション.
 * @return {Array} 表示するセクションと設定項目.
 */
export const usePostSettingsSections = ( context, builtInSections = [] ) => {
	const [ , setRevision ] = useState( 0 );
	const namespace = useRef();

	if ( ! namespace.current ) {
		listenerId += 1;
		namespace.current = `ystandard/post-settings/${ listenerId }`;
	}

	useEffect( () => {
		const updateSettings = ( hookName ) => {
			if ( SECTIONS_HOOK === hookName || ITEMS_HOOK === hookName ) {
				setRevision( ( currentRevision ) => currentRevision + 1 );
			}
		};

		addAction( HOOK_ADDED, namespace.current, updateSettings );
		addAction( HOOK_REMOVED, namespace.current, updateSettings );
		setRevision( ( currentRevision ) => currentRevision + 1 );

		return () => {
			removeAction( HOOK_ADDED, namespace.current );
			removeAction( HOOK_REMOVED, namespace.current );
		};
	}, [] );

	return getPostSettingsSections( context, builtInSections );
};

/**
 * 外部プラグインが登録した設定項目を表示する.
 *
 * @param {Object} props         コンポーネントプロパティ.
 * @param {Array}  props.items   設定項目.
 * @param {Object} props.context 共通コンテキスト.
 * @return {Element[]} 設定項目.
 */
export const PostSettingsItems = ( { items, context } ) =>
	items.map( ( item ) => {
		const ItemComponent = item.Component;

		return (
			<PostSettingsItemErrorBoundary
				key={ item.id }
				itemId={ item.id }
				resetKey={ ItemComponent }
			>
				<ItemComponent
					postType={ context.postType }
					postId={ context.postId }
				/>
			</PostSettingsItemErrorBoundary>
		);
	} );

/**
 * 外部プラグインが登録した投稿設定を表示する.
 *
 * @param {Object} props         コンポーネントプロパティ.
 * @param {Object} props.context 共通コンテキスト.
 * @return {Element|null} 設定パネル.
 */
export const ExternalPostSettingsPanels = ( { context } ) => {
	const sections = usePostSettingsSections( context ).filter(
		( section ) => 0 < section.items.length
	);

	if ( ! sections.length ) {
		return null;
	}

	return sections.map( ( section ) => (
		<PluginDocumentSettingPanel
			key={ section.id }
			name={ `ystandard-post-settings-${ section.id }` }
			className="ystandard-post-meta-panel"
			title={ section.title }
		>
			<div className="ystandard-post-settings-items">
				<PostSettingsItems
					items={ section.items }
					context={ context }
				/>
			</div>
		</PluginDocumentSettingPanel>
	) );
};
