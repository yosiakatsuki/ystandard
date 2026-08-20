/**
 * Internal Dependencies
 */
import {
	compactSettings,
	isValidSettings,
	normalizeSettings,
	updateSettingsStates,
} from './settings';

describe('投稿設定の圧縮', () => {
	it('versionと明示設定、空でないOGP文字列だけを残す', () => {
		expect(
			compactSettings({
				version: 1,
				post_thumbnail: 'on',
				header_taxonomy: 'off',
				footer_taxonomy: 'on',
				toc: 'off',
				author: 'on',
				ogp_title: '',
				ogp_description: '説明',
			})
		).toEqual({
			version: 1,
			post_thumbnail: 'on',
			header_taxonomy: 'off',
			footer_taxonomy: 'on',
			toc: 'off',
			author: 'on',
			ogp_description: '説明',
		});
	});

	it('任意設定が空でもversionを残す', () => {
		expect(compactSettings({ version: 1, ogp_title: ' ' })).toEqual({
			version: 1,
		});
	});

	it('分割前のシェアボタン設定を上下の設定へ変換する', () => {
		expect(normalizeSettings({ version: 1, share_buttons: 'off' })).toEqual(
			{
				version: 1,
				share_buttons_header: 'off',
				share_buttons_footer: 'off',
			}
		);
	});

	it('複数項目をまとめて変更し、既定値ではキーごと削除する', () => {
		const settings = {
			version: 1,
			toc: 'off' as const,
			author: 'on' as const,
			ogp_title: 'タイトル',
		};

		expect(
			updateSettingsStates(settings, ['toc', 'author'], 'off')
		).toEqual({
			...settings,
			author: 'off',
		});
		expect(
			updateSettingsStates(settings, ['toc', 'author'], 'default')
		).toEqual({
			version: 1,
			ogp_title: 'タイトル',
		});
	});

	it('versionが一致するオブジェクトだけを新形式として扱う', () => {
		expect(isValidSettings({ version: 1 })).toBe(true);
		expect(isValidSettings({})).toBe(false);
		expect(isValidSettings({ version: 2 })).toBe(false);
	});
});
