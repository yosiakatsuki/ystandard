/**
 * 検索ボタンの開閉処理
 */
const ysSetGlobalNavSearch = () => {

	if (searchButton) {

	}

	if (closeButton) {

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

			let targetPageUrl = urlSplit[0];
			if (!window?.ystdScriptOption?.isPermalinkBasic) {
				targetPageUrl = targetPageUrl.split('?')[0];
			}
			targetPageUrl = targetPageUrl.split('&')[0];
			targetPageUrl = targetPageUrl.replace(/\/$/, '');

			let currentPageUrl = location.href.split('#')[0];
			if (!window?.ystdScriptOption?.isPermalinkBasic) {
				currentPageUrl = currentPageUrl.split('?')[0];
			}
			currentPageUrl = currentPageUrl.split('&')[0];
			currentPageUrl = currentPageUrl.replace(/\/$/, '');
			const id = urlSplit[1].split('?')[0].split('&')[0];
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
				.style.setProperty(
					'--ystd--sidebar--fixed-position--top',
					`${size + 48}px`
				);
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
