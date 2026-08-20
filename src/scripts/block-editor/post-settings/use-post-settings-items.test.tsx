/**
 * WordPress Dependencies
 */
import { addFilter, removeFilter } from '@wordpress/hooks';

/**
 * Internal Dependencies
 */
import { ITEM_FILTER, SECTION_FILTER } from './types';
import {
	getPostSettingsItems,
	getPostSettingsSections,
} from './use-post-settings-items';

jest.mock('./providers', () => ({
	getStandardSections: () => [
		{ id: 'ystandard/post', title: '要素の表示設定', order: 10 },
		{ id: 'ystandard/seo', title: 'SEO設定', order: 20 },
		{ id: 'ystandard/sns', title: 'SNS設定', order: 30 },
	],
	getStandardItems: () => [
		{
			id: 'ystandard/standard-item',
			section: 'ystandard/post',
			order: 10,
			Component: () => null,
		},
	],
}));

const CONTEXT = {
	apiVersion: 1 as const,
	postType: 'post',
	postId: 1,
};

const SECTION_NAMESPACE = 'ystandard/test-sections';
const ITEM_NAMESPACE = 'ystandard/test-items';

afterEach(() => {
	removeFilter(SECTION_FILTER, SECTION_NAMESPACE);
	removeFilter(ITEM_FILTER, ITEM_NAMESPACE);
});

describe('投稿設定の拡張定義', () => {
	it('標準セクションを外部定義で置き換えず、独自セクションを順序どおり追加する', () => {
		addFilter(SECTION_FILTER, SECTION_NAMESPACE, (sections) => [
			{
				id: 'ystandard/post',
				title: '置き換え対象',
				order: 1,
			},
			...sections,
			{
				id: 'example/design',
				title: 'デザイン',
				order: 15,
			},
		]);

		const sections = getPostSettingsSections(CONTEXT);

		expect(sections.map(({ id }) => id)).toEqual([
			'ystandard/post',
			'example/design',
			'ystandard/seo',
			'ystandard/sns',
		]);
		expect(sections[0].title).toBe('要素の表示設定');
	});

	it('外部項目を標準セクションへ追加し、重複IDでは後の定義を採用する', () => {
		const FirstComponent = () => null;
		const LastComponent = () => null;
		addFilter(ITEM_FILTER, ITEM_NAMESPACE, (items) => [
			...items,
			{
				id: 'example/setting',
				section: 'ystandard/seo',
				order: 100,
				Component: FirstComponent,
			},
			{
				id: 'example/setting',
				section: 'ystandard/seo',
				order: 110,
				Component: LastComponent,
			},
		]);
		const sections = getPostSettingsSections(CONTEXT);

		const items = getPostSettingsItems(CONTEXT, sections);
		const item = items.find(({ id }) => id === 'example/setting');

		expect(item?.order).toBe(110);
		expect(item?.Component).toBe(LastComponent);
	});

	it('未登録セクションを参照する外部項目を除外する', () => {
		const Component = () => null;
		addFilter(ITEM_FILTER, ITEM_NAMESPACE, (items) => [
			...items,
			{
				id: 'example/orphan',
				section: 'example/missing',
				order: 10,
				Component,
			},
		]);
		const sections = getPostSettingsSections(CONTEXT);

		const items = getPostSettingsItems(CONTEXT, sections);

		expect(items.some(({ id }) => id === 'example/orphan')).toBe(false);
		expect(console).toHaveWarnedWith(
			'Invalid post settings item definition was ignored.'
		);
	});
});
