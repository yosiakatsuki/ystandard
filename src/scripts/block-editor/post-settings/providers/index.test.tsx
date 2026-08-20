/**
 * Internal Dependencies
 */
import { getStandardItems } from '.';

jest.mock('@aktk/block-components/wp-controls/button', () => () => null, {
	virtual: true,
});
jest.mock('./display-setting-actions', () => () => null);
jest.mock('./ogp-settings', () => ({
	OgpDescriptionSetting: () => null,
	OgpTitleSetting: () => null,
}));
jest.mock('./parts-shortcode', () => () => null);
jest.mock('./state-setting', () => () => null);

describe('投稿タイプ別の標準投稿設定', () => {
	it('要素の表示設定をページ内の表示順に並べる', () => {
		const availableStateSettings = [
			'post_thumbnail',
			'publish_date',
			'header_taxonomy',
			'share_buttons_header',
			'toc',
			'share_buttons_footer',
			'footer_taxonomy',
			'author',
			'related_posts',
			'paging',
			'advertisement',
		] as const;
		const items = getStandardItems({
			apiVersion: 1,
			postType: 'post',
			postId: 1,
			availableStateSettings: [...availableStateSettings],
		});

		expect(
			items
				.filter(({ section }) => section === 'ystandard/post')
				.sort((a, b) => a.order - b.order)
				.map(({ id }) => id)
		).toEqual([
			'ystandard/display-setting-actions',
			'ystandard/post-thumbnail',
			'ystandard/publish-date',
			'ystandard/header-taxonomy',
			'ystandard/share-buttons-header',
			'ystandard/toc',
			'ystandard/share-buttons-footer',
			'ystandard/footer-taxonomy',
			'ystandard/author',
			'ystandard/related-posts',
			'ystandard/paging',
			'ystandard/advertisement',
		]);
	});

	it('PHPが許可したカスタム投稿タイプ用の関連記事と前後記事を表示する', () => {
		const items = getStandardItems({
			apiVersion: 1,
			postType: 'book',
			postId: 1,
			availableStateSettings: ['related_posts', 'paging'],
		});
		const ids = items.map(({ id }) => id);

		expect(ids).toContain('ystandard/related-posts');
		expect(ids).toContain('ystandard/paging');
		expect(ids).not.toContain('ystandard/post-thumbnail');
	});

	it('投稿タイプで利用できない表示設定を除外する', () => {
		const items = getStandardItems({
			apiVersion: 1,
			postType: 'page',
			postId: 1,
			availableStateSettings: ['post_thumbnail', 'publish_date'],
		});
		const ids = items.map(({ id }) => id);

		expect(ids).toContain('ystandard/post-thumbnail');
		expect(ids).not.toContain('ystandard/header-taxonomy');
		expect(ids).not.toContain('ystandard/related-posts');
	});
});
