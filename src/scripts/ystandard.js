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
	// 固定ヘッダー高さセット.
	ysSetFixedHeaderPadding();
	ysSetDrawerNavPadding();
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
