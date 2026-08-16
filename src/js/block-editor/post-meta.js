/* @jsxRuntime classic */
/* @jsx createElement */
import {
	Button,
	TextControl,
	TextareaControl,
	ToggleControl,
} from '@wordpress/components';
import { useEntityProp } from '@wordpress/core-data';
import { useDispatch, useSelect } from '@wordpress/data';
import { createElement, Fragment, useRef } from '@wordpress/element';
import { PluginDocumentSettingPanel } from '@wordpress/editor';
import { __ } from '@wordpress/i18n';
import { registerPlugin } from '@wordpress/plugins';
import {
	BUILT_IN_SECTION_IDS,
	ExternalPostSettingsPanels,
	PostSettingsItems,
	usePostSettingsSections,
} from './post-settings-extensions';

const settings = window.ystandardPostMetaSettings || {};

const FieldControl = ( { field, value, onChange } ) => {
	if ( 'text' === field.control ) {
		return (
			<TextControl
				__next40pxDefaultSize
				__nextHasNoMarginBottom
				label={ field.label }
				help={ field.help }
				value={ value || '' }
				onChange={ onChange }
			/>
		);
	}

	if ( 'textarea' === field.control ) {
		return (
			<TextareaControl
				__nextHasNoMarginBottom
				label={ field.label }
				help={ field.help }
				value={ value || '' }
				onChange={ onChange }
			/>
		);
	}

	return (
		<ToggleControl
			__nextHasNoMarginBottom
			label={ field.label }
			help={ field.help }
			checked={ Boolean( value ) }
			onChange={ onChange }
		/>
	);
};

export const PostMetaPanels = ( {
	postType = settings.postType,
	panels = settings.panels || {},
	fields: metaFields = settings.fields || [],
	context = settings.postSettingsContext,
} = {} ) => {
	const [ meta, setMeta ] = useEntityProp( 'postType', postType, 'meta' );
	const builtInPanels = Object.entries( panels ).map(
		( [ panelName, panelTitle ], index ) => ( {
			id: BUILT_IN_SECTION_IDS[ panelName ] || `ystandard/${ panelName }`,
			title: panelTitle,
			order: ( index + 1 ) * 10,
			panelName,
		} )
	);
	const builtInSections = builtInPanels.map( ( { id, title, order } ) => ( {
		id,
		title,
		order,
	} ) );
	const builtInPanelNames = new Map(
		builtInPanels.map( ( panel ) => [ panel.id, panel.panelName ] )
	);
	const sections = usePostSettingsSections( context, builtInSections );

	return sections.map( ( section ) => {
		const panelName = builtInPanelNames.get( section.id );
		const fields = meta
			? metaFields.filter( ( field ) => panelName === field.panel )
			: [];

		if ( ! fields.length && ! section.items.length ) {
			return null;
		}

		return (
			<PluginDocumentSettingPanel
				key={ section.id }
				name={
					panelName
						? `ystandard-${ panelName }`
						: `ystandard-post-settings-${ section.id }`
				}
				className="ystandard-post-meta-panel"
				title={ section.title }
			>
				{ 0 < fields.length && (
					<div className="ystandard-post-meta-fields">
						{ fields.map( ( field ) => (
							<FieldControl
								key={ field.key }
								field={ field }
								value={ meta[ field.key ] }
								onChange={ ( value ) =>
									setMeta( {
										...meta,
										[ field.key ]: value,
									} )
								}
							/>
						) ) }
					</div>
				) }
				{ 0 < section.items.length && (
					<div className="ystandard-post-settings-items">
						<PostSettingsItems
							items={ section.items }
							context={ context }
						/>
					</div>
				) }
			</PluginDocumentSettingPanel>
		);
	} );
};

const PartsShortcodePanel = () => {
	const shortcodeInput = useRef( null );
	const { postId, postStatus } = useSelect( ( select ) => {
		const editor = select( 'core/editor' );

		return {
			postId: editor.getCurrentPostId(),
			postStatus: editor.getEditedPostAttribute( 'status' ),
		};
	}, [] );
	const { createErrorNotice, createSuccessNotice } =
		useDispatch( 'core/notices' );

	if ( 'publish' !== postStatus || ! postId ) {
		return null;
	}

	const shortcode = `[ys_parts parts_id="${ postId }"]`;
	const copyShortcode = async () => {
		let copied = false;

		if ( navigator.clipboard ) {
			try {
				await navigator.clipboard.writeText( shortcode );
				copied = true;
			} catch {
				copied = false;
			}
		}

		if ( ! copied && shortcodeInput.current ) {
			try {
				// HTTP環境などClipboard APIを使えない場合のフォールバック.
				shortcodeInput.current.focus();
				shortcodeInput.current.select();
				shortcodeInput.current.setSelectionRange( 0, shortcode.length );
				copied = document.execCommand( 'copy' );
			} catch {
				copied = false;
			}
		}

		if ( copied ) {
			createSuccessNotice(
				__( 'ショートコードをコピーしました。', 'ystandard' ),
				{
					type: 'snackbar',
				}
			);
		} else {
			createErrorNotice(
				__( 'ショートコードをコピーできませんでした。', 'ystandard' )
			);
		}
	};

	return (
		<PluginDocumentSettingPanel
			name="ystandard-parts-shortcode"
			title={ __( '[ys] ショートコード', 'ystandard' ) }
		>
			<TextControl
				ref={ shortcodeInput }
				__next40pxDefaultSize
				label={ __( 'ショートコード', 'ystandard' ) }
				value={ shortcode }
				readOnly
				onFocus={ ( event ) => event.target.select() }
			/>
			<Button variant="secondary" onClick={ copyShortcode }>
				{ __( 'ショートコードをコピー', 'ystandard' ) }
			</Button>
		</PluginDocumentSettingPanel>
	);
};

export const PostMetaPlugin = () => {
	return (
		<Fragment>
			{ settings.partsPostType === settings.postType ? (
				<Fragment>
					<PartsShortcodePanel />
					<ExternalPostSettingsPanels
						context={ settings.postSettingsContext }
					/>
				</Fragment>
			) : (
				<PostMetaPanels context={ settings.postSettingsContext } />
			) }
		</Fragment>
	);
};

registerPlugin( 'ystandard-post-meta', {
	render: PostMetaPlugin,
} );
