/**
 * Internal Dependencies
 */
import PostSettingsModal from './post-settings-modal';
import PostSettingsPanel from './post-settings-panel';
import type { PostSettingsContext } from './types';

interface PostSettingsProps {
	context: PostSettingsContext;
}

/**
 * 投稿タイプに対応する投稿設定UIを表示する.
 *
 * @param props         投稿設定props.
 * @param props.context 投稿設定コンテキスト.
 */
export default function PostSettings({ context }: PostSettingsProps) {
	// ショートコードだけを扱うys-partsでは、設定サイドバーから直接確認できるようにする.
	if (context.postType === 'ys-parts') {
		return <PostSettingsPanel context={context} />;
	}

	return <PostSettingsModal context={context} />;
}
