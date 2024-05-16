export function initAnchorLink() {
	document.addEventListener('DOMContentLoaded', () => {
		// ページ内リンククリック処理.
		setAnchorLinkClick();
		setTimeout(function () {
			// ページ読み込み時の位置調整.
			setLoadedPosition();
		}, 1);
	});
}

function setAnchorLinkClick() {
	// アンカーリンク付きのリンク取得.
	const links = document.querySelectorAll('a[href*="#"]');
	links.forEach((link) => {
		link.addEventListener('click', (e) => {
			const targetId = getTargetId(e.currentTarget as HTMLElement);
			// 対象IDがない場合はスクロールしない.
			if (null === targetId) {
				return;
			}
			e.preventDefault();
			doScroll(targetId);
		});
	});
}

function getTargetId(currentTarget: HTMLElement): string | null {
	const urlSplit = currentTarget?.getAttribute('href')?.split('#');
	// 対象URLなし.
	if (!urlSplit) {
		return '';
	}

	// ターゲットID抽出.
	const targetId = urlSplit[1].split('?')[0].split('&')[0];
	let targetUrl = urlSplit[0];
	// ページURL.
	let currentUrl = window.location.href.split('#')[0];

	// URL構造がベーシックの場合は「?」以降をURLとする.
	// @ts-expect-error
	if (!window?.ystdScriptOption?.isPermalinkBasic) {
		targetUrl = targetUrl.split('?')[0];
		currentUrl = currentUrl.split('?')[0];
	}
	targetUrl = targetUrl.split('&')[0].replace(/\/$/, '');
	currentUrl = currentUrl.split('&')[0].replace(/\/$/, '');

	// ターゲットURLがページURLと一致しない場合はリンク先へ移動.
	if ('' !== targetUrl && targetUrl !== currentUrl) {
		return null;
	}

	return targetId;
}

/**
 * スクロール処理.
 *
 * @param {string} id       スクロール先の要素ID
 * @param {string} behavior スクロール動作
 */
function doScroll(id: string, behavior: ScrollBehavior | undefined = 'smooth') {
	const target = document.getElementById(id);
	let top = 0;
	// 対象チェック..
	if (!target) {
		// 対象IDが存在しない場合はスクロールしない.
		// IDが空の場合はページ先頭へ移動.
		if ('' !== id) {
			return;
		}
	} else {
		const pos = target.getBoundingClientRect().top;
		top = pos + window.scrollY - getScrollBuffer();
	}
	// 履歴追加.
	history.pushState(null, '', `#${id}`);
	window.scroll({
		top,
		behavior,
	});
}

function setLoadedPosition() {
	const url = location.href;

	if (-1 === url.indexOf('#')) {
		return;
	}
	const id = url.split('#')[1].split('?')[0];
	// 対象の要素取得.
	const target = document.getElementById(id);
	if (!target) {
		return;
	}

	const position =
		target.getBoundingClientRect().top + window.scrollY - getScrollBuffer();
	// 内部リンク付きURLの場合、位置調整.
	window.scrollTo({
		top: position,
	});
}

/**
 * スクロール位置調整.
 */
function getScrollBuffer(): number {
	let buffer = 50;
	// ヘッダーチェック.
	const header = document.getElementById('masthead');
	// ヘッダーがなければ50px.
	if (!header) {
		return buffer;
	}
	// ヘッダーの固定表示チェック.
	const headerPosition = window
		.getComputedStyle(header, null)
		.getPropertyValue('position');
	// ヘッダーが固定表示の場合はヘッダーの高さを追加.
	if ('fixed' === headerPosition) {
		buffer = header.getBoundingClientRect().bottom + 20;
	}
	return buffer;
}
