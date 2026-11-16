/**
 * 固定ヘッダー関連
 * @param $
 */
const fixedHeader = ($) => {
	// 固定ヘッダーが有効なプレビューだけ、高さを表示する。
	if ($('.has-fixed-header').length) {
		const header = $('.site-header');
		// ヘッダーが省略された構成では、高さを取得できないため処理を終える。
		if (0 === header.length) {
			return;
		}
		/**
		 * 表示用要素の追加
		 */
		header.prepend(
			'<span class="header-height-info">ヘッダー高さ:<span class="header-height-num"></span>px</span>'
		);
		$('.header-height-num').text(Math.floor(header.outerHeight()));
		/**
		 * リサイズでセット
		 */
		$(window).on('resize', () => {
			$('.header-height-num').text(' ... ');
			setTimeout(() => {
				$('.header-height-num').text(Math.floor(header.outerHeight()));
			}, 800);
		});
	}
};
document.addEventListener('DOMContentLoaded', function () {
	fixedHeader(jQuery);
});
