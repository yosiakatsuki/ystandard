/**
 * WordPress Dependencies
 */
import { __ } from '@wordpress/i18n';

/**
 * Aktk Dependencies
 */
import Button from '@aktk/block-components/wp-controls/button';

/**
 * Internal Dependencies
 */
import type { PostSettingsItemProps, StateSettingKey } from '../types';
import { usePostSettings } from './use-post-settings';

const DISPLAY_SETTING_KEYS: StateSettingKey[] = [
	'publish_date',
	'post_thumbnail',
	'header_taxonomy',
	'share_buttons_header',
	'share_buttons_footer',
	'footer_taxonomy',
	'toc',
	'author',
	'related_posts',
	'paging',
	'advertisement',
];

const POST_ONLY_KEYS: StateSettingKey[] = ['related_posts', 'paging'];

/**
 * 要素の表示設定をまとめて変更するボタンを表示する.
 *
 * @param props          設定項目props.
 * @param props.postType 投稿タイプ.
 * @param props.postId   投稿ID.
 */
export default function DisplaySettingActions({
	postType,
	postId,
}: PostSettingsItemProps) {
	const { updateStates } = usePostSettings(postType, postId);
	const availableStateSettings =
		window.ystdPostSettings?.availableStateSettings;
	const settingKeys = DISPLAY_SETTING_KEYS.filter((key) => {
		// PHPから利用可能な項目が渡されている場合は、投稿タイプの機能に合わせる.
		if (availableStateSettings) {
			return availableStateSettings.includes(key);
		}

		return postType === 'post' || !POST_ONLY_KEYS.includes(key);
	});

	return (
		<div
			className="ys-post-settings__bulk-actions"
			role="group"
			aria-label={__('要素の表示設定をまとめて変更', 'ystandard')}
		>
			<Button
				size="small"
				variant="secondary"
				onClick={() => updateStates(settingKeys, 'default')}
			>
				{__('既定値に戻す', 'ystandard')}
			</Button>
			<Button
				size="small"
				variant="secondary"
				onClick={() => updateStates(settingKeys, 'off')}
			>
				{__('すべてOFF', 'ystandard')}
			</Button>
			<Button
				size="small"
				variant="secondary"
				onClick={() => updateStates(settingKeys, 'on')}
			>
				{__('すべてON', 'ystandard')}
			</Button>
		</div>
	);
}
