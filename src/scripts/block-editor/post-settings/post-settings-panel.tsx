/**
 * WordPress Dependencies
 */
import { PluginDocumentSettingPanel } from '@wordpress/editor';
import { __ } from '@wordpress/i18n';

/**
 * Internal Dependencies
 */
import PostSettingsItemBoundary from './post-settings-item-boundary';
import type { PostSettingsContext } from './types';
import { usePostSettingsItems } from './use-post-settings-items';

interface PostSettingsPanelProps {
	context: PostSettingsContext;
}

/**
 * セクションIDを設定パネル名に変換する.
 *
 * @param sectionId セクションID.
 */
function getPanelName(sectionId: string) {
	return `ystandard-post-settings-${sectionId.replace(/[^a-z0-9_-]/gi, '-')}`;
}

/**
 * yStandard投稿設定をエディター設定パネルへ表示する.
 *
 * @param props         投稿設定パネルprops.
 * @param props.context 投稿設定コンテキスト.
 */
export default function PostSettingsPanel({ context }: PostSettingsPanelProps) {
	const { sections, items } = usePostSettingsItems(context);

	return (
		<>
			{sections.map((section) => {
				const sectionItems = items.filter(
					(item) => item.section === section.id
				);
				// 項目がないセクションは空の設定パネルを作らない.
				if (sectionItems.length === 0) {
					return null;
				}

				return (
					<PluginDocumentSettingPanel
						key={section.id}
						name={getPanelName(section.id)}
						title={section.title}
						initialOpen={section.id === 'ystandard/shortcode'}
						className="ys-post-settings__panel"
					>
						<div className="ys-post-settings__items">
							{sectionItems.map((item) => {
								const ItemComponent = item.Component;
								return (
									<PostSettingsItemBoundary
										key={item.id}
										fallback={
											<p role="alert">
												{__(
													'設定項目を表示できませんでした。',
													'ystandard'
												)}
											</p>
										}
									>
										<ItemComponent
											postType={context.postType}
											postId={context.postId}
										/>
									</PostSettingsItemBoundary>
								);
							})}
						</div>
					</PluginDocumentSettingPanel>
				);
			})}
		</>
	);
}
