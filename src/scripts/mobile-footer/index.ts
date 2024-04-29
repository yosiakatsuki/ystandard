export function setMobileFooter() {
	let resizeTimer: NodeJS.Timeout;
	document.addEventListener('DOMContentLoaded', setMobileFooterPadding);
	// リサイズイベントは間引く.
	window.addEventListener('resize', () => {
		clearTimeout(resizeTimer);
		resizeTimer = setTimeout(setMobileFooterPadding, 300);
	});
}

function setMobileFooterPadding() {
	const mobileFooter = document.querySelector('.footer-mobile-nav');
	let mobileFooterHeight = '0';

	if (!mobileFooter) {
		return;
	}
	// モバイルフッターが表示されている場合、高さをセットする.
	if ('none' !== window.getComputedStyle(mobileFooter).display) {
		mobileFooterHeight = mobileFooter.clientHeight + 'px';
	}
	// モバイルフッターの高さ分をセット.
	document.body.style.setProperty(
		'--ystd--mobile-footer-nav--footer-padding-bottom',
		mobileFooterHeight
	);
}
