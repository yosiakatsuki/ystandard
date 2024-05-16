import { getHeaderHeight } from '../utils';

export function initFixedHeader() {
	let resizeFixedHeader: NodeJS.Timeout;
	document.addEventListener('DOMContentLoaded', () => {
		setFixedHeaderPadding();
	});

	window.addEventListener('resize', () => {
		if (resizeFixedHeader) {
			clearTimeout(resizeFixedHeader);
		}
		resizeFixedHeader = setTimeout(function () {
			// 固定ヘッダー高さセット.
			setFixedHeaderPadding();
		}, 100);
	});
}

/**
 * 固定ヘッダー高さセット.
 */
function setFixedHeaderPadding() {
	const bodyClass = document.body.classList;
	// 各条件.
	const hasFixedHeader = bodyClass.contains('has-fixed-header');
	const isDisableAutoPadding = bodyClass.contains('disable-auto-padding');
	const isOverlay = bodyClass.contains('is-overlay');
	// チェック.
	if (hasFixedHeader && !isDisableAutoPadding && !isOverlay) {
		const headerHeight = getHeaderHeight();
		if (headerHeight) {
			document.body.style.paddingTop = `${headerHeight}px`;
			document.body.style.setProperty(
				'--ystd--sidebar--custom-fixed-position--top',
				`calc(${headerHeight}px + var(--ystd--sidebar--fixed-position--top) * 2)`
			);
		}
	}
}
