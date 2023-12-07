/**
 * 固定ヘッダー設定時にヘッダーの高さを表示する.
 */
export function showHeaderHeightInfo() {
	document.addEventListener('DOMContentLoaded', () => {
			// @ts-ignore
			const $ = jQuery;
			const label = 'ヘッダー高さ:';
			const wrapClass = 'header-height-info';
			const numClass = 'header-height-num';
			// 固定ヘッダーチェック.
			const fixedHeader = $('.has-fixed-header');
			if (!fixedHeader.length) {
				return;
			}
			// ヘッダー.
			const header = $('.site-header');
			if (!header.length) {
				return;
			}
			// 表示追加.
			header.prepend(`<span class="${wrapClass}">${label}<span class="${numClass}"></span>px</span>`);
			// 表示更新
			const updateHeight = () => {
				const height = Math.floor(header.outerHeight());
				$(`.${numClass}`).text(height);
			}
			updateHeight();
			// リサイズで更新.
			$(window).on('resize', () => {
				$(`.${numClass}`).text(' ... ');
				setTimeout(() => {
					updateHeight();
				}, 800);
			});
		}
	);
}
