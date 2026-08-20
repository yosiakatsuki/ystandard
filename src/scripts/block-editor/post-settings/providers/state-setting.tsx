/**
 * WordPress Dependencies
 */
import {
	__experimentalToggleGroupControl as ToggleGroupControl,
	__experimentalToggleGroupControlOption as ToggleGroupControlOption,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';

/**
 * Internal Dependencies
 */
import type {
	PostSettingsItemProps,
	SettingState,
	StateSettingKey,
} from '../types';
import { usePostSettings } from './use-post-settings';

interface StateSettingProps extends PostSettingsItemProps {
	settingKey: StateSettingKey;
	label: string;
}

/**
 * テーマ設定を含む3状態の投稿設定を表示する.
 *
 * @param props            設定項目props.
 * @param props.postType   投稿タイプ.
 * @param props.postId     投稿ID.
 * @param props.settingKey 設定キー.
 * @param props.label      表示ラベル.
 */
export default function StateSetting({
	postType,
	postId,
	settingKey,
	label,
}: StateSettingProps) {
	const { settings, updateState } = usePostSettings(postType, postId);
	const themeSetting = window.ystdPostSettings?.themeSettings[settingKey];
	const value = settings[settingKey] ?? 'default';

	return (
		<ToggleGroupControl
			__next40pxDefaultSize
			__nextHasNoMarginBottom
			isBlock
			label={label}
			help={themeSetting?.description}
			value={value}
			onChange={(next) => updateState(settingKey, next as SettingState)}
		>
			<ToggleGroupControlOption label="-" value="default" />
			<ToggleGroupControlOption
				label={__('OFF', 'ystandard')}
				value="off"
			/>
			<ToggleGroupControlOption
				label={__('ON', 'ystandard')}
				value="on"
			/>
		</ToggleGroupControl>
	);
}
