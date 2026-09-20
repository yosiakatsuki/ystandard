/**
 * Internal Dependencies
 */
import PostSettings from './post-settings';
import PostSettingsModal from './post-settings-modal';
import PostSettingsPanel from './post-settings-panel';

jest.mock('./post-settings-modal', () => () => null);
jest.mock('./post-settings-panel', () => () => null);

describe('投稿タイプ別の投稿設定UI', () => {
	it('ys-partsではエディター設定パネルを表示する', () => {
		const element = PostSettings({
			context: {
				apiVersion: 1,
				postType: 'ys-parts',
				postId: 1,
			},
		});

		expect(element.type).toBe(PostSettingsPanel);
	});

	it('通常投稿タイプでは投稿設定モーダルを表示する', () => {
		const element = PostSettings({
			context: {
				apiVersion: 1,
				postType: 'post',
				postId: 1,
			},
		});

		expect(element.type).toBe(PostSettingsModal);
	});
});
