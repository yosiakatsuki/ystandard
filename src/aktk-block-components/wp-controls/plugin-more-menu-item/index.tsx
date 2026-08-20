/**
 * WordPress Dependencies
 */
import { PluginMoreMenuItem as WPPluginMoreMenuItem } from '@wordpress/editor';

/**
 * エディターの「︙」メニューへ項目を表示する.
 *
 * @param props PluginMoreMenuItemへ渡すprops.
 */
export default function PluginMoreMenuItem(
	props: React.ComponentProps<typeof WPPluginMoreMenuItem>
) {
	return <WPPluginMoreMenuItem {...props} />;
}
