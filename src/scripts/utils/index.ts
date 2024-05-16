/**
 * ヘッダー高さ取得.
 */
export function getHeaderHeight() {
	const header = document.getElementById('masthead');
	if (!header) {
		return undefined;
	}
	return Math.floor(header.getBoundingClientRect().height);
}
