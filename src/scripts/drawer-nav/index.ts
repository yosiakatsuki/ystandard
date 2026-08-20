import { getHeaderHeight } from '../utils';

const OPEN_CLASS = 'is-open';

type BodyScrollLock = {
	scrollPosition: number;
	styles: {
		position: string;
		top: string;
		width: string;
	};
};

let bodyScrollLock: BodyScrollLock | null = null;
let usesFixedBodyScrollLock = false;

/**
 * ドロワーメニューの開閉処理.
 */
export function drawerNav() {
	document.addEventListener('DOMContentLoaded', () => {
		const drawerNav = document.getElementById(
			'drawer-nav'
		) as HTMLDialogElement | null;
		const globalNavToggle = document.getElementById(
			'global-nav__toggle'
		) as HTMLButtonElement | null;

		// メニューを出力しない構成では、開閉イベントを登録できないため処理を終える.
		if (!drawerNav || !globalNavToggle) {
			return;
		}

		setOpenEvent(drawerNav, globalNavToggle);
		setCloseEvents(drawerNav, globalNavToggle);
		setCloseContainerHeight();
	});

	let resizeWindow: NodeJS.Timeout;
	window.addEventListener('resize', () => {
		// 連続するリサイズ処理の最後だけで高さを再計算する.
		if (resizeWindow) {
			clearTimeout(resizeWindow);
		}
		resizeWindow = setTimeout(function () {
			setCloseContainerHeight();
		}, 100);
	});
}

/**
 * ドロワーメニューを開くイベントを登録する.
 *
 * @param drawerNav       ドロワーメニュー.
 * @param globalNavToggle メニューを開くボタン.
 */
function setOpenEvent(
	drawerNav: HTMLDialogElement,
	globalNavToggle: HTMLButtonElement
) {
	globalNavToggle.addEventListener('click', (event) => {
		event.preventDefault();

		// PC表示などで開くボタンが非表示のときは、メニューの状態を変更しない.
		if ('none' === window.getComputedStyle(globalNavToggle).display) {
			return;
		}

		// 多重に開く操作をしてshowModal()が例外になることを防ぐ.
		if (isDrawerOpen(drawerNav)) {
			return;
		}

		openDrawerNav(drawerNav, globalNavToggle);
	});
}

/**
 * ドロワーメニューを閉じるイベントを登録する.
 *
 * @param drawerNav       ドロワーメニュー.
 * @param globalNavToggle メニューを開くボタン.
 */
function setCloseEvents(
	drawerNav: HTMLDialogElement,
	globalNavToggle: HTMLButtonElement
) {
	const targetQuery = [
		'.global-nav a[href*="#"]',
		'.drawer-nav a[href*="#"]',
		'#drawer-nav__toggle',
	].join(',');
	const drawerNavCloseElements = document.querySelectorAll(targetQuery);

	drawerNavCloseElements.forEach((element) => {
		element.addEventListener('click', () => {
			closeDrawerNav(drawerNav, globalNavToggle);
		});
	});

	drawerNav.addEventListener('click', (event) => {
		// ダイアログ内の操作では閉じず、背景部分を直接クリックした場合だけ閉じる.
		if (event.target !== drawerNav) {
			return;
		}

		closeDrawerNav(drawerNav, globalNavToggle);
	});

	// ネイティブのEscape操作を含め、すべての終了経路で表示状態を確実に戻す.
	drawerNav.addEventListener('close', () => {
		finishClosingDrawerNav(drawerNav, globalNavToggle);
		globalNavToggle.focus();
	});

	// ネイティブdialogがない環境だけ、Escapeキーの終了処理を補う.
	if (!supportsNativeDialog(drawerNav)) {
		document.addEventListener('keydown', (event) => {
			// 他のキー操作や閉じている状態には影響を与えない.
			if ('Escape' !== event.key || !isDrawerOpen(drawerNav)) {
				return;
			}

			closeDrawerNav(drawerNav, globalNavToggle);
		});
	}
}

/**
 * ドロワーメニューを開く.
 *
 * @param drawerNav       ドロワーメニュー.
 * @param globalNavToggle メニューを開くボタン.
 */
function openDrawerNav(
	drawerNav: HTMLDialogElement,
	globalNavToggle: HTMLButtonElement
) {
	// 対応ブラウザではトップレイヤーと標準のフォーカス制御を利用する.
	if (supportsNativeDialog(drawerNav)) {
		drawerNav.showModal();
		lockPageScroll();
		setDrawerNavState(globalNavToggle, true);

		window.requestAnimationFrame(() => {
			// 開く直後に別経路から閉じられた場合は、終了済みの状態を上書きしない.
			if (drawerNav.open) {
				drawerNav.classList.add(OPEN_CLASS);
			}
		});
		return;
	}

	// 未対応ブラウザでは従来のクラス表示でメニュー操作を維持する.
	lockPageScroll();
	setDrawerNavState(globalNavToggle, true);
	drawerNav.classList.add(OPEN_CLASS);
	document.getElementById('drawer-nav__toggle')?.focus();
}

/**
 * ドロワーメニューを閉じる.
 *
 * @param drawerNav       ドロワーメニュー.
 * @param globalNavToggle メニューを開くボタン.
 */
function closeDrawerNav(
	drawerNav: HTMLDialogElement,
	globalNavToggle: HTMLButtonElement
) {
	// 閉じた状態のリンク操作では、フォーカスやスクロール位置を変更しない.
	if (!isDrawerOpen(drawerNav)) {
		return;
	}

	// ネイティブdialogの終了処理はcloseイベントへ集約する.
	if (supportsNativeDialog(drawerNav)) {
		drawerNav.close();
		return;
	}

	// 未対応ブラウザではcloseイベントがないため、その場で終了処理を行う.
	finishClosingDrawerNav(drawerNav, globalNavToggle);
	globalNavToggle.focus();
}

/**
 * ドロワーメニューを閉じたあとの状態を戻す.
 *
 * @param drawerNav       ドロワーメニュー.
 * @param globalNavToggle メニューを開くボタン.
 */
function finishClosingDrawerNav(
	drawerNav: HTMLDialogElement,
	globalNavToggle: HTMLButtonElement
) {
	drawerNav.classList.remove(OPEN_CLASS);
	setDrawerNavState(globalNavToggle, false);
	unlockPageScroll();
}

/**
 * ネイティブdialogを利用できるか確認する.
 *
 * @param drawerNav ドロワーメニュー.
 */
function supportsNativeDialog(drawerNav: HTMLDialogElement) {
	return (
		'function' === typeof drawerNav.showModal &&
		'function' === typeof drawerNav.close
	);
}

/**
 * ドロワーメニューが開いているか確認する.
 *
 * @param drawerNav ドロワーメニュー.
 */
function isDrawerOpen(drawerNav: HTMLDialogElement) {
	return drawerNav.open || drawerNav.classList.contains(OPEN_CLASS);
}

/**
 * ドロワーメニュー開閉に関する状態を設定する.
 *
 * @param globalNavToggle メニューを開くボタン.
 * @param isOpen          開いている状態にするか.
 */
function setDrawerNavState(
	globalNavToggle: HTMLButtonElement,
	isOpen: boolean
) {
	const setClass = (target: Element | null, className: string) => {
		// テンプレート構成によって対象がない場合も、他の状態更新を続ける.
		if (!target) {
			return;
		}

		target.classList.toggle(className, isOpen);
	};

	setClass(globalNavToggle, OPEN_CLASS);
	setClass(document.getElementById('drawer-nav__toggle'), OPEN_CLASS);
	setClass(document.getElementById('global-nav__menu'), OPEN_CLASS);
	setClass(
		document.getElementById('global-nav__search'),
		'is-drawer-nav-open'
	);
	setClass(document.documentElement, 'is-drawer-open');
	setClass(document.getElementById('footer-mobile-nav'), OPEN_CLASS);
	globalNavToggle.setAttribute('aria-expanded', String(isOpen));
}

/**
 * ページのスクロールを固定する.
 */
function lockPageScroll() {
	const scrollbarWidth = Math.max(
		0,
		window.innerWidth - document.documentElement.clientWidth
	);
	document.documentElement.classList.toggle(
		'has-drawer-scrollbar',
		0 < scrollbarWidth
	);

	// 標準CSSでスクロール連鎖を止められる環境では、ページ全体を再配置しない.
	if (supportsCssScrollLock(scrollbarWidth)) {
		usesFixedBodyScrollLock = false;
		return;
	}

	usesFixedBodyScrollLock = true;
	lockBodyScroll(scrollbarWidth);
}

/**
 * ページのスクロール固定を解除する.
 */
function unlockPageScroll() {
	document.documentElement.classList.remove('has-drawer-scrollbar');

	// 標準CSSを使った環境では、HTMLの状態クラス解除だけで復元が完了する.
	if (!usesFixedBodyScrollLock) {
		return;
	}

	usesFixedBodyScrollLock = false;
	unlockBodyScroll();
}

/**
 * 標準CSSで背景スクロールを抑止できるか確認する.
 *
 * @param scrollbarWidth スクロールバーの幅.
 */
function supportsCssScrollLock(scrollbarWidth: number) {
	const supportsOverscrollBehavior =
		'undefined' !== typeof CSS &&
		'function' === typeof CSS.supports &&
		CSS.supports('overscroll-behavior', 'none');
	const supportsScrollbarGutter =
		'undefined' !== typeof CSS &&
		'function' === typeof CSS.supports &&
		CSS.supports('scrollbar-gutter', 'stable');

	return (
		supportsOverscrollBehavior &&
		(0 === scrollbarWidth || supportsScrollbarGutter)
	);
}

/**
 * 標準CSSが使えない環境で本文のスクロール位置を固定する.
 *
 * @param scrollbarWidth スクロールバーの幅.
 */
function lockBodyScroll(scrollbarWidth: number) {
	// 二重固定や別機能による固定を上書きせず、ドロワーが取得した状態だけ管理する.
	if (null !== bodyScrollLock || 'fixed' === document.body.style.position) {
		return;
	}

	const bodyStyle = document.body.style;
	bodyScrollLock = {
		scrollPosition: window.scrollY,
		styles: {
			position: bodyStyle.position,
			top: bodyStyle.top,
			width: bodyStyle.width,
		},
	};
	bodyStyle.top = `-${bodyScrollLock.scrollPosition}px`;
	bodyStyle.position = 'fixed';
	bodyStyle.width = `calc(100% - ${scrollbarWidth}px)`;
}

/**
 * 固定した本文のスクロール位置を復元する.
 */
function unlockBodyScroll() {
	// ドロワーが固定していない状態は、他機能のスタイルを守るため変更しない.
	if (null === bodyScrollLock) {
		return;
	}

	const currentBodyScrollLock = bodyScrollLock;
	const bodyStyle = document.body.style;
	bodyScrollLock = null;
	bodyStyle.position = currentBodyScrollLock.styles.position;
	bodyStyle.top = currentBodyScrollLock.styles.top;
	bodyStyle.width = currentBodyScrollLock.styles.width;
	window.scrollTo(0, currentBodyScrollLock.scrollPosition);
}

/**
 * ドロワーメニュー閉じるボタンエリアの高さ設定.
 */
function setCloseContainerHeight() {
	const headerHeight = getHeaderHeight();
	const container = document.querySelector(
		'.drawer-nav__close-container'
	) as HTMLElement | null;

	// ヘッダーと閉じるボタン領域がある場合だけ、高さを揃える.
	if (container && headerHeight) {
		container.style.height = `${headerHeight}px`;
	}
}
