/**
 * 検索ボタンの開閉処理
 */
const ysSetGlobalNavSearch = () => {
	const searchButton = document.getElementById('global-nav__search-button');
	if (searchButton) {
		searchButton.addEventListener('click', () => {
			const search = document.getElementById('global-nav__search');
			if (search) {
				search.classList.toggle('is-active');
				setTimeout(function () {
					document
						.querySelector('#global-nav__search .search-field')
						.focus();
				}, 50);
			}
		});
	}
	const closeButton = document.getElementById('global-nav__search-close');
	if (closeButton) {
		closeButton.addEventListener('click', () => {
			const search = document.getElementById('global-nav__search');
			if (search) {
				search.classList.remove('is-active');
			}
		});
	}
};

/**
 * グローバルナビゲーションの開閉処理
 */
const ysSetGlobalNavToggle = () => {
	const globalNav = document.getElementById('global-nav__toggle');
	if (globalNav) {
		globalNav.addEventListener('click', (e) => {
			e.currentTarget.classList.toggle('is-open');
			const globalMenu = document.getElementById('global-nav__menu');
			if (globalMenu) {
				globalMenu.classList.toggle('is-open');
			}
			const globalSearch = document.getElementById('global-nav__search');
			if (globalSearch) {
				globalSearch.classList.toggle('is-open');
			}
			ysToggleContentDisableScroll(e.currentTarget);
			const mobileFooter =
				document.getElementsByClassName('footer-mobile-nav');
			if (mobileFooter && mobileFooter.length) {
				mobileFooter[0].classList.toggle('is-hide');
			}
		});
	}
	// グロナビ閉じる.
	const globalNavLinks = document.querySelectorAll(
		'.global-nav a[href*="#"]'
	);
	for (let i = 0; i < globalNavLinks.length; i++) {
		globalNavLinks[i].addEventListener('click', () => {
			const toggle = document.getElementById('global-nav__toggle');
			if (toggle) {
				toggle.classList.remove('is-open');
				ysToggleContentDisableScroll(toggle);
			}
			const mobileFooter =
				document.getElementsByClassName('footer-mobile-nav');

			if (mobileFooter && mobileFooter.length) {
				mobileFooter[0].classList.remove('is-hide');
			}
		});
	}
};

const ysToggleContentDisableScroll = (target) => {
	if (target.classList.contains('is-open')) {
		document.body.style.top = `-${window.scrollY}px`;
		document.body.style.position = 'fixed';
		document.body.style.width = '100%';
	} else if (
		'none' !== document.defaultView.getComputedStyle(target, null).display
	) {
		const top = document.body.style.top;
		document.body.style.position = '';
		document.body.style.top = '';
		document.body.style.width = '';
		window.scrollTo(0, parseInt(top || '0') * -1);
	}
};

/**
 * ページ内リンクのスムーススクロール.
 */
const ysSetSmoothScroll = () => {
	const links = document.querySelectorAll('a[href*="#"]');
	for (let i = 0; i < links.length; i++) {
		links[i].addEventListener('click', (e) => {
			const urlSplit = e.currentTarget.getAttribute('href').split('#');
			const targetPageUrl = urlSplit[0].split('?')[0].replace(/\/$/, '');
			const currentPageUrl = location.href
				.split('#')[0]
				.split('?')[0]
				.replace(/\/$/, '');
			const id = urlSplit[1].split('?')[0];
			if ('' !== targetPageUrl && targetPageUrl !== currentPageUrl) {
				location.href = e.currentTarget.getAttribute('href');
				return;
			}
			e.preventDefault();
			ysScrollToTarget(id);
		});
	}
};

const ysScrollToTarget = (id, behavior = 'smooth') => {
	const target = document.getElementById(id);
	if (!target && '' !== id) {
		return;
	}
	let top = 0;
	if (target) {
		const pos = target.getBoundingClientRect().top;
		top = pos + window.pageYOffset - ysGetScrollBuffer();
	}
	window.scroll({
		top,
		behavior,
	});
};

const ysGetScrollBuffer = () => {
	let buffer = 50;
	const header = document.getElementById('masthead');
	if (header) {
		if (
			'fixed' ===
			window.getComputedStyle(header, null).getPropertyValue('position')
		) {
			buffer = header.getBoundingClientRect().bottom + 20;
		}
	}
	return buffer;
};

/**
 * ページ表示時の位置調整
 */
const ysSetLoadedPosition = () => {
	if (-1 === location.href.indexOf('#')) {
		return;
	}
	const id = location.href.split('#')[1].split('?')[0];
	const target = document.getElementById(id);
	if (!target && '' !== id) {
		return;
	}
	window.scrollTo({
		top:
			target.getBoundingClientRect().top +
			window.pageYOffset -
			ysGetScrollBuffer(),
	});
};

/**
 * TOPへ戻る.
 */
const ysSetBackToTop = () => {
	const backToTop = document.getElementById('back-to-top');
	if (backToTop && backToTop.classList.contains('is-square')) {
		const width = backToTop.getBoundingClientRect().width;
		const height = backToTop.getBoundingClientRect().height;
		const size = width < height ? `${height}px` : `${width}px`;
		backToTop.style.width = size;
		backToTop.style.height = size;
		backToTop.addEventListener('click', (e) => {
			e.preventDefault();
			window.scroll({
				top: 0,
				behavior: 'smooth',
			});
		});
	}
};

/**
 * スクロールバー幅の変数セット.
 */
const ysSetScrollBarWidth = () => {
	const scrollbar = window.innerWidth - document.body.clientWidth;
	if (
		window
			.getComputedStyle(document.documentElement)
			.getPropertyValue('--scrollbar-width')
	) {
		document
			.querySelector(':root')
			.style.setProperty('--scrollbar-width', scrollbar + 'px');
	}
};

const getHeaderHeight = () => {
	const header = document.getElementById('masthead');
	if (!header) {
		return undefined;
	}
	return Math.floor(header.getBoundingClientRect().height);
};

const ysSetFixedHeaderPadding = () => {
	const classes = document.body.classList;
	if (
		classes.contains('has-fixed-header') &&
		!classes.contains('disable-auto-padding') &&
		!classes.contains('is-overlay')
	) {
		const size = getHeaderHeight();
		if (size) {
			document.body.style.paddingTop = `${size}px`;
			document
				.querySelector(':root')
				.style.setProperty('--fixed-sidebar-top', `${size + 48}px`);
		}
	}
};

const ysSetDrawerNavPadding = () => {
	const size = getHeaderHeight();
	if (size) {
		document
			.querySelector(':root')
			.style.setProperty(
				'--mobile-nav-container-padding',
				`${size + 24}px`
			);
	}
};

document.addEventListener('DOMContentLoaded', () => {
	// スクロールバー分.
	ysSetScrollBarWidth();
	// 固定ヘッダー高さセット.
	ysSetFixedHeaderPadding();
	// メニュー.
	ysSetGlobalNavToggle();
	ysSetDrawerNavPadding();
	// 検索ボタン.
	ysSetGlobalNavSearch();
	// スムーススクロール.
	ysSetSmoothScroll();
	// TOPへ戻る.
	ysSetBackToTop();
	// ページ内リンクの位置調整.
	setTimeout(function () {
		ysSetLoadedPosition();
	}, 1);
});

window.addEventListener('resize', () => {
	if (window.ysResizeFixedHeader) {
		clearTimeout(window.ysResizeFixedHeader);
	}
	window.ysResizeFixedHeader = setTimeout(function () {
		// 固定ヘッダー高さセット.
		ysSetFixedHeaderPadding();
	}, 100);
});
