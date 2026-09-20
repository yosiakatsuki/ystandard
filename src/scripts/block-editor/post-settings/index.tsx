/**
 * WordPress Dependencies
 */
import { registerPlugin } from '@wordpress/plugins';

/**
 * Internal Dependencies
 */
import PostSettings from './post-settings';

import './style.scss';

const config = window.ystdPostSettings;

// PHPから対応する投稿編集コンテキストが渡された場合だけプラグインを登録する.
if (config?.apiVersion === 1) {
	registerPlugin('ystandard-post-settings', {
		render: () => <PostSettings context={config} />,
	});
}
