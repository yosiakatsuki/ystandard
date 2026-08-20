/**
 * WordPress Dependencies
 */
import { __ } from '@wordpress/i18n';

/**
 * Internal Dependencies
 */
import type {
	PostSettingsContext,
	PostSettingsItem,
	PostSettingsItemProps,
	PostSettingsSection,
	StateSettingKey,
} from '../types';
import DisplaySettingActions from './display-setting-actions';
import { OgpDescriptionSetting, OgpTitleSetting } from './ogp-settings';
import PartsShortcode from './parts-shortcode';
import StateSetting from './state-setting';

const STATE_ITEMS: Array<{
	id: string;
	section: string;
	order: number;
	settingKey: StateSettingKey;
	label: string;
	postTypes?: string[];
}> = [
	{
		id: 'ystandard/post-thumbnail',
		section: 'ystandard/post',
		order: 10,
		settingKey: 'post_thumbnail',
		label: __('アイキャッチ画像の表示', 'ystandard'),
	},
	{
		id: 'ystandard/publish-date',
		section: 'ystandard/post',
		order: 20,
		settingKey: 'publish_date',
		label: __('投稿日・更新日表示', 'ystandard'),
	},
	{
		id: 'ystandard/header-taxonomy',
		section: 'ystandard/post',
		order: 30,
		settingKey: 'header_taxonomy',
		label: __('本文上部のカテゴリー情報', 'ystandard'),
	},
	{
		id: 'ystandard/share-buttons-header',
		section: 'ystandard/post',
		order: 40,
		settingKey: 'share_buttons_header',
		label: __('シェアボタン表示（上）', 'ystandard'),
	},
	{
		id: 'ystandard/toc',
		section: 'ystandard/post',
		order: 50,
		settingKey: 'toc',
		label: __('目次を自動で作成', 'ystandard'),
	},
	{
		id: 'ystandard/share-buttons-footer',
		section: 'ystandard/post',
		order: 60,
		settingKey: 'share_buttons_footer',
		label: __('シェアボタン表示（下）', 'ystandard'),
	},
	{
		id: 'ystandard/footer-taxonomy',
		section: 'ystandard/post',
		order: 70,
		settingKey: 'footer_taxonomy',
		label: __('本文下部のカテゴリー・タグ情報', 'ystandard'),
	},
	{
		id: 'ystandard/author',
		section: 'ystandard/post',
		order: 80,
		settingKey: 'author',
		label: __('著者情報表示', 'ystandard'),
	},
	{
		id: 'ystandard/related-posts',
		section: 'ystandard/post',
		order: 90,
		settingKey: 'related_posts',
		label: __('関連記事表示', 'ystandard'),
		postTypes: ['post'],
	},
	{
		id: 'ystandard/paging',
		section: 'ystandard/post',
		order: 100,
		settingKey: 'paging',
		label: __('前後の記事表示', 'ystandard'),
		postTypes: ['post'],
	},
	{
		id: 'ystandard/advertisement',
		section: 'ystandard/post',
		order: 110,
		settingKey: 'advertisement',
		label: __('広告表示', 'ystandard'),
	},
	{
		id: 'ystandard/noindex',
		section: 'ystandard/seo',
		order: 10,
		settingKey: 'noindex',
		label: __('noindex出力', 'ystandard'),
	},
	{
		id: 'ystandard/meta-description',
		section: 'ystandard/seo',
		order: 20,
		settingKey: 'meta_description',
		label: __('meta description出力', 'ystandard'),
	},
];

/**
 * yStandard標準セクションを取得する.
 *
 * @param context 投稿設定コンテキスト.
 */
export function getStandardSections(
	context: PostSettingsContext
): PostSettingsSection[] {
	// ys-partsでは通常投稿設定を表示せず、ショートコード案内だけを表示する.
	if (context.postType === 'ys-parts') {
		return [
			{
				id: 'ystandard/shortcode',
				title: __('ショートコード', 'ystandard'),
				order: 10,
			},
		];
	}

	return [
		{
			id: 'ystandard/post',
			title: __('要素の表示設定', 'ystandard'),
			order: 10,
		},
		{
			id: 'ystandard/seo',
			title: __('SEO設定', 'ystandard'),
			order: 20,
		},
		{
			id: 'ystandard/sns',
			title: __('SNS設定', 'ystandard'),
			order: 30,
		},
	];
}

/**
 * 3状態設定コンポーネントを作成する.
 *
 * @param settingKey 設定キー.
 * @param label      表示ラベル.
 */
function createStateSetting(settingKey: StateSettingKey, label: string) {
	return function StandardStateSetting(props: PostSettingsItemProps) {
		return (
			<StateSetting {...props} settingKey={settingKey} label={label} />
		);
	};
}

/**
 * yStandard標準設定項目を取得する.
 *
 * @param context 投稿設定コンテキスト.
 */
export function getStandardItems(
	context: PostSettingsContext
): PostSettingsItem[] {
	// ys-partsではショートコード案内だけを登録する.
	if (context.postType === 'ys-parts') {
		return [
			{
				id: 'ystandard/parts-shortcode',
				section: 'ystandard/shortcode',
				order: 10,
				Component: PartsShortcode,
			},
		];
	}

	const items = STATE_ITEMS.filter((item) => {
		// PHPから利用可能な項目が渡されている場合は、投稿タイプの機能に合わせる.
		if (context.availableStateSettings) {
			return context.availableStateSettings.includes(item.settingKey);
		}

		return !item.postTypes || item.postTypes.includes(context.postType);
	}).map((item) => ({
		id: item.id,
		section: item.section,
		order: item.order,
		Component: createStateSetting(item.settingKey, item.label),
	}));

	return [
		{
			id: 'ystandard/display-setting-actions',
			section: 'ystandard/post',
			order: 0,
			Component: DisplaySettingActions,
		},
		...items,
		{
			id: 'ystandard/ogp-title',
			section: 'ystandard/sns',
			order: 10,
			Component: OgpTitleSetting,
		},
		{
			id: 'ystandard/ogp-description',
			section: 'ystandard/sns',
			order: 20,
			Component: OgpDescriptionSetting,
		},
	];
}
