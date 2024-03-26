/**
 * ドロワーメニューの開閉処理.
 */
export function drawerNav() {
	document.addEventListener('DOMContentLoaded', () => {
		// ドロワーメニューの開閉処理セット.
		if (!toggleDrawerNav()) {
			return;
		}
		// ドロワーメニューを閉じる処理セット.
		closeDrawerNav();
	});
}

/**
 * ドロワーメニューの開閉処理.
 */
function toggleDrawerNav() {
	const globalNavToggle = document.getElementById('global-nav__toggle');
	const drawerNavToggle = document.getElementById('drawer-nav__toggle');
	if (!globalNavToggle || !drawerNavToggle) {
		return false;
	}
	// ドロワーメニュー開閉ボタン取得.
	const globalNavToggleButtons = document.querySelectorAll(
		'.global-nav__toggle, .drawer-menu-toggle'
	);
	// ボタンが無ければ処理終了
	if (0 >= globalNavToggleButtons.length) {
		return false;
	}
	// ボタンクリック時の処理追加.
	globalNavToggleButtons.forEach((element) => {
		element.addEventListener('click', (e) => {
			e.preventDefault();
			const display = window.getComputedStyle(globalNavToggle).display;
			// ボタンが表示なしの場合は何もしない.
			if ('none' === display) {
				return;
			}
			// ドロワーメニュー開閉に関するクラス設定.
			setDrawerNavClass();
			// メイン要素のスクロール制御.
			toggleBodyScroll(globalNavToggle);
		});
	});

	return true;
}

/**
 * ドロワーメニューを閉じる処理.
 */
function closeDrawerNav() {
	// ドロワーメニュー閉じる.
	const drawerNavCloseLinks = document.querySelectorAll(
		'.global-nav a[href*="#"]'
	);
	// ドロワーメニュー閉じるリンク・ボタンのチェック.
	if (0 >= drawerNavCloseLinks.length) {
		return false;
	}
	// ドロワーメニュー閉じるリンククリック時の処理追加.
	drawerNavCloseLinks.forEach((element) => {
		element.addEventListener('click', () => {
			const globalNavToggle =
				document.getElementById('global-nav__toggle');

			// ドロワーメニュー開閉に関するクラス設定.
			setDrawerNavClass('remove');
			if (globalNavToggle) {
				// メイン要素のスクロール制御.
				toggleBodyScroll(globalNavToggle);
			}
		});
	});
	return true;
}

/**
 * ドロワーメニュー開閉に関するクラス設定
 * @param type
 */
function setDrawerNavClass(type: 'toggle' | 'remove' = 'toggle') {
	// クラスの設定
	const setClass = (target: Element | null, className: string) => {
		if (!target) return;
		if ('toggle' === type) {
			target.classList.toggle(className);
		} else {
			target.classList.remove(className);
		}
	};
	// グローバルメニュー開閉ボタン.
	setClass(document.getElementById('global-nav__toggle'), 'is-open');
	// ドロワーメニュー開閉ボタン.
	setClass(document.getElementById('drawer-nav__toggle'), 'is-open');
	// グローバルメニュー.
	setClass(document.getElementById('global-nav__menu'), 'is-open');
	// ドロワーメニュー.
	setClass(document.getElementById('drawer-nav'), 'is-open');
	// グローバルサーチ.
	setClass(
		document.getElementById('global-nav__search'),
		'is-drawer-nav-open'
	);
	// メイン要素.
	setClass(document.documentElement, 'is-open');
	// モバイルフッター.
	setClass(document.getElementById('footer-mobile-nav'), 'is-open');
}

/**
 * メニュー開閉時のメイン要素のスクロール制御
 * @param target
 */
function toggleBodyScroll(target: Element) {
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
