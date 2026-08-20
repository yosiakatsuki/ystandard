/**
 * WordPress Dependencies
 */
import { TextareaControl, TextControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

/**
 * Internal Dependencies
 */
import type { PostSettingsItemProps } from '../types';
import { usePostSettings } from './use-post-settings';

/**
 * OGPタイトル設定を表示する.
 *
 * @param props          設定項目props.
 * @param props.postType 投稿タイプ.
 * @param props.postId   投稿ID.
 */
export function OgpTitleSetting({ postType, postId }: PostSettingsItemProps) {
	const { settings, updateText } = usePostSettings(postType, postId);

	return (
		<TextControl
			label={__('OGP/Twitter Cards用タイトル', 'ystandard')}
			help={__('未入力の場合は投稿タイトルを使用します。', 'ystandard')}
			value={settings.ogp_title ?? ''}
			onChange={(value) => updateText('ogp_title', value)}
			// @ts-ignore WordPressの次期標準余白とサイズを使用する.
			__nextHasNoMarginBottom
			__next40pxDefaultSize
		/>
	);
}

/**
 * OGP description設定を表示する.
 *
 * @param props          設定項目props.
 * @param props.postType 投稿タイプ.
 * @param props.postId   投稿ID.
 */
export function OgpDescriptionSetting({
	postType,
	postId,
}: PostSettingsItemProps) {
	const { settings, updateText } = usePostSettings(postType, postId);

	return (
		<TextareaControl
			label={__('OGP/Twitter Cards用description', 'ystandard')}
			help={__(
				'未入力の場合は抜粋または投稿本文から自動生成します。',
				'ystandard'
			)}
			value={settings.ogp_description ?? ''}
			onChange={(value) => updateText('ogp_description', value)}
			// @ts-ignore WordPressの次期標準余白を使用する.
			__nextHasNoMarginBottom
		/>
	);
}
