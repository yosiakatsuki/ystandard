/**
 * ドロワーメニューの開閉処理.
 */
export function drawerNav() {
	document.addEventListener('DOMContentLoaded', () => {
		const globalNavToggle = document.getElementById('global-nav__toggle');
		if (!globalNavToggle) {
			return;
		}
		// ドロワーメニュー開閉ボタン取得.
		const globalNavToggleButtons = document.querySelectorAll(
			'#global-nav__toggle, .drawer-menu-toggle'
		);
		// ボタンがあれば開閉処理追加.
		if (0 < globalNavToggleButtons.length) {
			globalNavToggleButtons.forEach((element) => {
				element.addEventListener('click', (e) => {
					e.preventDefault();
					const display =
						window.getComputedStyle(globalNavToggle).display;
					// ボタンが表示なしの場合は何もしない.
					if ('none' === display) {
						return;
					}
					globalNavToggle.classList.toggle('is-open');
					// グローバルメニューの開閉.
					const globalMenu =
						document.getElementById('global-nav__menu');
					if (globalMenu) {
						globalMenu.classList.toggle('is-open');
					}
					// グローバルサーチの開閉.
					const globalSearch =
						document.getElementById('global-nav__search');
					if (globalSearch) {
						globalSearch.classList.toggle('is-open');
					}
					// メイン要素のスクロール制御.
					disableScroll(globalNavToggle);
					// モバイルフッターの開閉.
					const mobileFooter =
						document.getElementsByClassName('footer-mobile-nav');
					if (mobileFooter && mobileFooter.length) {
						mobileFooter[0].classList.toggle('is-hide');
					}
				});
			});
		}
	});
}

/**
 * メニュー開閉時のメイン要素のスクロール制御
 * @param target
 */
function disableScroll(target: Element) {
	if (target.classList.contains('is-open')) {
		document.body.style.top = `-${window.scrollY}px`;
		document.body.style.position = 'fixed';
		document.body.style.width = '100%';
	} else if ('none' !== window.getComputedStyle(target).display) {
		const top = document.body.style.top;
		document.body.style.position = '';
		document.body.style.top = '';
		document.body.style.width = '';
		window.scrollTo(0, parseInt(top || '0') * -1);
	}
}
