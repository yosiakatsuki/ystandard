/**
 * WordPress Dependencies
 */
import { Button } from '@wordpress/components';
import { useEntityProp } from '@wordpress/core-data';
import { useState } from '@wordpress/element';
import { __, sprintf } from '@wordpress/i18n';

/**
 * Internal Dependencies
 */
import type { PostSettingsItemProps } from '../types';

/**
 * ys-partsのショートコードを表示する.
 *
 * @param props          設定項目props.
 * @param props.postType 投稿タイプ.
 * @param props.postId   投稿ID.
 */
export default function PartsShortcode({
	postType,
	postId,
}: PostSettingsItemProps) {
	const [status] = useEntityProp('postType', postType, 'status', postId);
	const [notice, setNotice] = useState('');
	const shortcode = `[ys_parts parts_id="${postId}"]`;

	// 公開前は利用できない理由を案内し、空の設定欄にしない.
	if (status !== 'publish') {
		return (
			<p>
				{__(
					'公開すると、投稿・固定ページやウィジェットで利用するショートコードを表示できます。',
					'ystandard'
				)}
			</p>
		);
	}

	/**
	 * ショートコードをクリップボードへコピーする.
	 */
	const copyShortcode = async () => {
		try {
			// Clipboard APIを利用できない環境では失敗を明示する.
			if (!navigator.clipboard) {
				throw new Error('Clipboard API is unavailable.');
			}
			await navigator.clipboard.writeText(shortcode);
			setNotice(__('ショートコードをコピーしました。', 'ystandard'));
		} catch (error) {
			setNotice(
				__('ショートコードをコピーできませんでした。', 'ystandard')
			);
		}
	};

	return (
		<div className="ys-post-settings__shortcode">
			<label htmlFor="ys-parts-shortcode">
				{__('ショートコード', 'ystandard')}
			</label>
			<div className="ys-post-settings__shortcode-row">
				<input
					id="ys-parts-shortcode"
					type="text"
					value={shortcode}
					readOnly
					onFocus={(event) => event.currentTarget.select()}
				/>
				<Button variant="secondary" onClick={copyShortcode}>
					{__('コピー', 'ystandard')}
				</Button>
			</div>
			<p className="description">
				{sprintf(
					/* translators: %s: ys-parts shortcode. */
					__(
						'%sを投稿・固定ページやウィジェットで使用できます。',
						'ystandard'
					),
					shortcode
				)}
			</p>
			<p className="ys-post-settings__copy-notice" aria-live="polite">
				{notice}
			</p>
		</div>
	);
}
