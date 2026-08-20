/**
 * Internal Dependencies
 */
import type { PostSettings, SettingState, StateSettingKey } from '../types';

export const SETTINGS_VERSION = 1;
export const STATE_KEYS: StateSettingKey[] = [
	'post_thumbnail',
	'header_taxonomy',
	'footer_taxonomy',
	'advertisement',
	'toc',
	'share_buttons_header',
	'share_buttons_footer',
	'publish_date',
	'author',
	'related_posts',
	'paging',
	'noindex',
	'meta_description',
];

/**
 * 有効な新形式の投稿設定か判定する.
 *
 * @param value 判定対象.
 */
export function isValidSettings(value: unknown): value is PostSettings {
	return (
		value !== null &&
		typeof value === 'object' &&
		'version' in value &&
		value.version === SETTINGS_VERSION
	);
}

/**
 * 分割前のシェアボタン設定を現在の形式へ変換する.
 *
 * @param settings 投稿設定.
 */
export function normalizeSettings(settings: PostSettings): PostSettings {
	const result = { ...settings };
	// v5開発版の分割前設定がある場合は、未設定の上下両方へ同じ状態を引き継ぐ.
	if (result.share_buttons === 'off' || result.share_buttons === 'on') {
		result.share_buttons_header ??= result.share_buttons;
		result.share_buttons_footer ??= result.share_buttons;
	}
	delete result.share_buttons;

	return result;
}

/**
 * 指定した3状態設定をまとめて変更する.
 *
 * @param settings 投稿設定.
 * @param keys     設定キー一覧.
 * @param state    新しい状態.
 */
export function updateSettingsStates(
	settings: PostSettings,
	keys: StateSettingKey[],
	state: SettingState
): PostSettings {
	const result = normalizeSettings(settings);
	keys.forEach((key) => {
		// 既定値へ戻す項目はプロパティごと削除する.
		if (state === 'default') {
			delete result[key];
		} else {
			// 投稿単位で上書きする状態だけを設定する.
			result[key] = state;
		}
	});

	return result;
}

/**
 * 投稿設定から保存不要な値を除去する.
 *
 * @param settings 投稿設定.
 */
export function compactSettings(settings: PostSettings): PostSettings {
	const normalized = normalizeSettings(settings);
	const result: PostSettings = { version: SETTINGS_VERSION };
	STATE_KEYS.forEach((key) => {
		const value = normalized[key];
		// 明示的なON・OFFだけを保存し、テーマ設定を使う状態は省略する.
		if (value === 'off' || value === 'on') {
			result[key] = value;
		}
	});
	['ogp_title', 'ogp_description'].forEach((key) => {
		const value = normalized[key as 'ogp_title' | 'ogp_description'];
		// 空文字や文字列以外の値は保存せず、OGP設定のキーごと除去する.
		if (typeof value === 'string' && value.trim() !== '') {
			result[key as 'ogp_title' | 'ogp_description'] = value;
		}
	});

	return result;
}
