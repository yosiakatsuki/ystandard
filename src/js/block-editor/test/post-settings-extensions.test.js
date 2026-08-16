/* @jsxRuntime classic */
/* @jsx createElement */
// eslint-disable-next-line import/no-extraneous-dependencies
import { act } from 'react';
import {
	actions,
	addFilter,
	removeAllFilters,
	removeFilter,
} from '@wordpress/hooks';
import { createElement, createRoot } from '@wordpress/element';
import { useEntityProp } from '@wordpress/core-data';
import {
	ExternalPostSettingsPanels,
	ITEMS_HOOK,
	SECTIONS_HOOK,
} from '../post-settings-extensions';
import { PostMetaPanels } from '../post-meta';

jest.mock( '@wordpress/components', () => {
	const { createElement: createWpElement } = require( '@wordpress/element' );
	const Control = ( { label } ) =>
		createWpElement( 'div', { className: 'control' }, label );

	return {
		Button: Control,
		TextControl: Control,
		TextareaControl: Control,
		ToggleControl: Control,
	};
} );

jest.mock( '@wordpress/core-data', () => ( {
	useEntityProp: jest.fn(),
} ) );

jest.mock( '@wordpress/data', () => ( {
	useDispatch: jest.fn(),
	useSelect: jest.fn(),
} ) );

jest.mock( '@wordpress/editor', () => {
	const { createElement: createWpElement } = require( '@wordpress/element' );

	return {
		PluginDocumentSettingPanel: ( { children, name, title } ) =>
			createWpElement(
				'section',
				{ 'data-panel-name': name },
				createWpElement( 'h2', null, title ),
				children
			),
	};
} );

jest.mock( '@wordpress/i18n', () => ( {
	__: ( text ) => text,
} ) );

jest.mock( '@wordpress/plugins', () => ( {
	registerPlugin: jest.fn(),
} ) );

describe( 'ExternalPostSettingsPanels', () => {
	const context = {
		apiVersion: 1,
		postType: 'post',
		postId: 9123,
	};
	let container;
	let root;

	beforeAll( () => {
		global.IS_REACT_ACT_ENVIRONMENT = true;
	} );

	beforeEach( () => {
		container = document.createElement( 'div' );
		document.body.appendChild( container );
	} );

	afterEach( () => {
		if ( root ) {
			act( () => root.unmount() );
			root = null;
		}
		container.remove();
		removeAllFilters( SECTIONS_HOOK );
		removeAllFilters( ITEMS_HOOK );
		jest.restoreAllMocks();
	} );

	const renderPanels = () => {
		act( () => {
			root = createRoot( container );
			root.render( <ExternalPostSettingsPanels context={ context } /> );
		} );
	};

	it( 'セクションと項目をorder順で表示し、投稿情報を渡す', () => {
		const receivedProps = [];
		const createSetting = ( label ) => ( props ) => {
			receivedProps.push( props );
			return <div className="setting">{ label }</div>;
		};
		const FirstSetting = createSetting( 'first' );
		const SecondBSetting = createSetting( 'second-b' );
		const SecondASetting = createSetting( 'second-a' );

		addFilter( SECTIONS_HOOK, 'test/sections', ( sections ) => [
			...sections,
			{ id: 'second', title: '2番目', order: 20 },
			{ id: 'first', title: '1番目', order: 10 },
		] );
		addFilter( ITEMS_HOOK, 'test/items', ( items ) => [
			...items,
			{
				id: 'item-b',
				section: 'second',
				order: 10,
				Component: SecondBSetting,
			},
			{
				id: 'item-a',
				section: 'second',
				order: 10,
				Component: SecondASetting,
			},
			{
				id: 'item-first',
				section: 'first',
				order: 20,
				Component: FirstSetting,
			},
		] );

		renderPanels();

		expect(
			Array.from(
				container.querySelectorAll( 'h2' ),
				( node ) => node.textContent
			)
		).toEqual( [ '1番目', '2番目' ] );
		expect(
			Array.from(
				container.querySelectorAll( '.setting' ),
				( node ) => node.textContent
			)
		).toEqual( [ 'first', 'second-b', 'second-a' ] );
		expect( receivedProps ).toEqual(
			expect.arrayContaining( [ { postType: 'post', postId: 9123 } ] )
		);
	} );

	it( '同じIDでは後の定義を採用し、不正な定義を無視する', () => {
		const warn = jest.spyOn( console, 'warn' ).mockImplementation();
		const OldSetting = () => <div>古い設定</div>;
		const NewSetting = () => <div>新しい設定</div>;

		addFilter( SECTIONS_HOOK, 'test/sections', () => [
			{ id: 'design', title: '古いタイトル', order: 10 },
			null,
			{ id: 'design', title: '新しいタイトル', order: 20 },
			{ id: 'empty', title: '空のセクション', order: 30 },
		] );
		addFilter( ITEMS_HOOK, 'test/items', () => [
			{
				id: 'setting',
				section: 'design',
				order: 10,
				Component: OldSetting,
			},
			{ id: 'invalid', section: 'design', order: 20, Component: null },
			{
				id: 'unknown',
				section: 'missing',
				order: 30,
				Component: NewSetting,
			},
			{
				id: 'setting',
				section: 'design',
				order: 40,
				Component: NewSetting,
			},
		] );

		renderPanels();

		expect( container.querySelector( 'h2' ).textContent ).toBe(
			'新しいタイトル'
		);
		expect( container.textContent ).toContain( '新しい設定' );
		expect( container.textContent ).not.toContain( '古い設定' );
		expect( container.textContent ).not.toContain( '空のセクション' );
		expect( warn ).toHaveBeenCalled();
	} );

	it( '初回描画後のフィルター追加と削除へ追従する', () => {
		const Setting = () => <div>動的設定</div>;
		const addSection = ( sections ) => [
			...sections,
			{ id: 'dynamic', title: '動的パネル', order: 10 },
		];
		const addItem = ( items ) => [
			...items,
			{
				id: 'dynamic-item',
				section: 'dynamic',
				order: 10,
				Component: Setting,
			},
		];

		renderPanels();
		expect( container.querySelector( 'section' ) ).toBeNull();

		act( () => {
			addFilter( SECTIONS_HOOK, 'test/dynamic-section', addSection );
			addFilter( ITEMS_HOOK, 'test/dynamic-item', addItem );
		} );
		expect( container.textContent ).toContain( '動的設定' );

		act( () => {
			removeFilter( ITEMS_HOOK, 'test/dynamic-item' );
		} );
		expect( container.querySelector( 'section' ) ).toBeNull();
	} );

	it( '項目の例外を分離して、ほかの項目を表示する', () => {
		jest.spyOn( console, 'error' ).mockImplementation();
		jest.spyOn( console, 'warn' ).mockImplementation();
		const BrokenSetting = () => {
			throw new Error( '描画エラー' );
		};
		const ValidSetting = () => <div>正常な設定</div>;

		addFilter( SECTIONS_HOOK, 'test/sections', () => [
			{ id: 'error-boundary', title: 'エラー境界', order: 10 },
		] );
		addFilter( ITEMS_HOOK, 'test/items', () => [
			{
				id: 'broken',
				section: 'error-boundary',
				order: 10,
				Component: BrokenSetting,
			},
			{
				id: 'valid',
				section: 'error-boundary',
				order: 20,
				Component: ValidSetting,
			},
		] );

		renderPanels();

		expect( container.textContent ).toContain( '正常な設定' );
		expect( container.textContent ).not.toContain( '描画エラー' );
	} );

	it( '外部設定がない場合は追加パネルを表示しない', () => {
		renderPanels();

		expect( container.childElementCount ).toBe( 0 );
	} );

	it( 'アンマウント時にフック監視を解除する', () => {
		const countListeners = ( hookName ) =>
			( actions[ hookName ]?.handlers || [] ).filter( ( handler ) =>
				handler.namespace.startsWith( 'ystandard/post-settings/' )
			).length;

		renderPanels();
		expect( countListeners( 'hookAdded' ) ).toBe( 1 );
		expect( countListeners( 'hookRemoved' ) ).toBe( 1 );

		act( () => root.unmount() );
		root = null;

		expect( countListeners( 'hookAdded' ) ).toBe( 0 );
		expect( countListeners( 'hookRemoved' ) ).toBe( 0 );
	} );
} );

describe( 'PostMetaPanels', () => {
	it( '既存のyStandard投稿設定パネルを表示する', () => {
		const container = document.createElement( 'div' );
		const root = createRoot( container );
		useEntityProp.mockReturnValue( [ { ys_noindex: true }, jest.fn() ] );

		act( () => {
			root.render(
				<PostMetaPanels
					postType="post"
					panels={ { seo: '[ys] SEO設定' } }
					fields={ [
						{
							key: 'ys_noindex',
							panel: 'seo',
							control: 'toggle',
							label: 'noindex',
						},
					] }
				/>
			);
		} );

		expect( container.textContent ).toContain( '[ys] SEO設定' );
		expect( container.textContent ).toContain( 'noindex' );

		act( () => root.unmount() );
	} );
} );
