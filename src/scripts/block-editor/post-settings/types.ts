/**
 * WordPress Dependencies
 */
import type { ComponentType } from '@wordpress/element';

export const SECTION_FILTER = 'ystandard.hooks.postSettings.sections';
export const ITEM_FILTER = 'ystandard.hooks.postSettings.items';

export type SettingState = 'default' | 'off' | 'on';

export interface PostSettings {
	version?: number;
	post_thumbnail?: 'off' | 'on';
	header_taxonomy?: 'off' | 'on';
	footer_taxonomy?: 'off' | 'on';
	advertisement?: 'off' | 'on';
	toc?: 'off' | 'on';
	share_buttons_header?: 'off' | 'on';
	share_buttons_footer?: 'off' | 'on';
	/** v5開発版の分割前設定を読み取るためだけに使用する. */
	share_buttons?: 'off' | 'on';
	publish_date?: 'off' | 'on';
	author?: 'off' | 'on';
	related_posts?: 'off' | 'on';
	paging?: 'off' | 'on';
	noindex?: 'off' | 'on';
	meta_description?: 'off' | 'on';
	ogp_title?: string;
	ogp_description?: string;
}

export type StateSettingKey = Exclude<
	keyof PostSettings,
	'version' | 'ogp_title' | 'ogp_description' | 'share_buttons'
>;

export interface ThemeSetting {
	description: string;
}

export interface PostSettingsContext {
	apiVersion: 1;
	postType: string;
	postId: number;
	availableStateSettings?: StateSettingKey[];
}

export interface PostSettingsConfig extends PostSettingsContext {
	postStatus: string;
	hasStoredSettings: boolean;
	settings: PostSettings;
	fallbackSettings: PostSettings;
	themeSettings: Partial<Record<StateSettingKey, ThemeSetting>>;
}

export interface PostSettingsSection {
	id: string;
	title: string;
	order: number;
}

export interface PostSettingsItemProps {
	postType: string;
	postId: number;
}

export interface PostSettingsItem {
	id: string;
	section: string;
	order: number;
	Component: ComponentType<PostSettingsItemProps>;
}

export type PostMeta = Record<string, unknown>;

declare global {
	interface Window {
		ystdPostSettings?: PostSettingsConfig;
	}
}
