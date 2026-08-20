/**
 * WordPress Dependencies
 */
import { useState } from '@wordpress/element';
import { PluginDocumentSettingPanel } from '@wordpress/editor';
import { __ } from '@wordpress/i18n';

/**
 * Aktk Dependencies
 */
import Button from '@aktk/block-components/wp-controls/button';
import EditorPinnedButton from '@aktk/block-components/wp-controls/editor-pinned-button';
import Modal from '@aktk/block-components/wp-controls/modal';
import PluginMoreMenuItem from '@aktk/block-components/wp-controls/plugin-more-menu-item';

/**
 * Internal Dependencies
 */
import PostSettingsItemBoundary from './post-settings-item-boundary';
import type { PostSettingsContext } from './types';
import { usePostSettingsItems } from './use-post-settings-items';
import YStandardIcon from './ystandard-icon';

interface PostSettingsModalProps {
	context: PostSettingsContext;
}

/**
 * yStandard投稿設定モーダルを表示する.
 *
 * @param props         投稿設定モーダルprops.
 * @param props.context 投稿設定コンテキスト.
 */
export default function PostSettingsModal({ context }: PostSettingsModalProps) {
	const [isOpen, setIsOpen] = useState(false);
	const { sections, items } = usePostSettingsItems(context);

	// yStandardと外部プロバイダーの項目がない場合は起動UIも表示しない.
	if (items.length === 0) {
		return null;
	}
	const title = __('yStandard 投稿設定', 'ystandard');
	/** モーダルを開く. */
	const openModal = () => setIsOpen(true);

	return (
		<>
			<EditorPinnedButton
				icon={<YStandardIcon />}
				label={title}
				onClick={openModal}
			/>
			<PluginMoreMenuItem icon={<YStandardIcon />} onClick={openModal}>
				{title}
			</PluginMoreMenuItem>
			<PluginDocumentSettingPanel
				name="ystandard-post-settings-launcher"
				title={title}
				className="ys-post-settings__launcher-panel"
			>
				<Button
					className="ys-post-settings__open-button"
					variant="secondary"
					onClick={openModal}
				>
					{__('投稿設定を開く', 'ystandard')}
				</Button>
			</PluginDocumentSettingPanel>
			{isOpen && (
				<Modal
					title={title}
					onRequestClose={() => setIsOpen(false)}
					className="ys-post-settings__modal"
				>
					<div className="ys-post-settings__sections">
						{sections.map((section) => {
							const sectionItems = items.filter(
								(item) => item.section === section.id
							);
							// 項目がないセクション見出しは表示しない.
							if (sectionItems.length === 0) {
								return null;
							}
							return (
								<section
									key={section.id}
									className="ys-post-settings__section"
								>
									<h2>{section.title}</h2>
									<div className="ys-post-settings__items">
										{sectionItems.map((item) => {
											const ItemComponent =
												item.Component;
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
														postType={
															context.postType
														}
														postId={context.postId}
													/>
												</PostSettingsItemBoundary>
											);
										})}
									</div>
								</section>
							);
						})}
					</div>
					<p className="ys-post-settings__save-notice">
						{__(
							'変更は投稿の保存・更新時に保存されます。',
							'ystandard'
						)}
					</p>
				</Modal>
			)}
		</>
	);
}
