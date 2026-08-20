/**
 * WordPress Dependencies
 */
import { useEntityProp } from '@wordpress/core-data';

/**
 * Internal Dependencies
 */
import type { PostMeta, SettingState, StateSettingKey } from '../types';
import {
	compactSettings,
	isValidSettings,
	normalizeSettings,
	SETTINGS_VERSION,
	updateSettingsStates,
} from './settings';

const META_KEY = 'ys_post_settings';

/**
 * 投稿設定メタを取得・更新する.
 *
 * @param postType 投稿タイプ.
 * @param postId   投稿ID.
 */
export function usePostSettings(postType: string, postId: number) {
	const config = window.ystdPostSettings;
	const [meta, setMeta] = useEntityProp('postType', postType, 'meta', postId);
	const currentMeta =
		meta !== null && typeof meta === 'object' ? (meta as PostMeta) : {};
	const stored = currentMeta[META_KEY];
	const hasStoredSettings = isValidSettings(stored);
	let settings = config?.fallbackSettings ?? {
		version: SETTINGS_VERSION,
	};
	// エディターの現在値が新形式なら、ほかの初期値より優先する.
	if (hasStoredSettings) {
		settings = stored;
	}
	const shouldUseInitialSettings =
		!hasStoredSettings &&
		config?.hasStoredSettings &&
		isValidSettings(config.settings);
	// RESTの現在値をまだ取得できない場合はPHPから渡した保存済み設定を使う.
	if (shouldUseInitialSettings) {
		settings = config.settings;
	}
	settings = normalizeSettings(settings);

	/**
	 * 複数の3状態設定をまとめて更新する.
	 *
	 * @param keys  設定キー一覧.
	 * @param state 新しい状態.
	 */
	const updateStates = (keys: StateSettingKey[], state: SettingState) => {
		setMeta({
			...currentMeta,
			[META_KEY]: compactSettings(
				updateSettingsStates(settings, keys, state)
			),
		});
	};

	/**
	 * 3状態設定を更新する.
	 *
	 * @param key   設定キー.
	 * @param state 新しい状態.
	 */
	const updateState = (key: StateSettingKey, state: SettingState) => {
		updateStates([key], state);
	};

	/**
	 * 文字列設定を更新する.
	 *
	 * @param key   設定キー.
	 * @param value 新しい文字列.
	 */
	const updateText = (
		key: 'ogp_title' | 'ogp_description',
		value: string
	) => {
		const next = { ...settings, [key]: value };
		setMeta({
			...currentMeta,
			[META_KEY]: compactSettings(next),
		});
	};

	return { settings, updateState, updateStates, updateText };
}
