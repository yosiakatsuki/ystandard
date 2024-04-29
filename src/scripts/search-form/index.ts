export function setGlobalNavSearch() {
	document.addEventListener('DOMContentLoaded', () => {
		// 検索フォームを開く処理セット.
		setOpenSearch();
		// 検索フォームを閉じる処理セット.
		setCloseSearch();
	});
}

/**
 * 検索フォームを開く処理セット.
 */
function setOpenSearch() {
	const searchButton = document.getElementById('global-nav__search-button');
	if (!searchButton) {
		return;
	}
	searchButton.addEventListener('click', (e) => {
		e.preventDefault();
		const search = document.getElementById('global-nav__search');
		if (search) {
			search.classList.toggle('is-active');
			// 検索フォームが表示されたらフォーカスをセット
			setTimeout(function () {
				const field = document.querySelector(
					'#global-nav__search .search-field'
				) as HTMLInputElement | null;
				// フォーカスをセット
				if (field) {
					field.focus();
				}
			}, 50);
		}
	});
}

/**
 * 検索フォームを閉じる処理セット.
 */
function setCloseSearch() {
	const closeButton = document.getElementById('global-nav__search-close');
	if (!closeButton) {
		return;
	}
	closeButton.addEventListener('click', () => {
		const search = document.getElementById('global-nav__search');
		if (search) {
			search.classList.remove('is-active');
		}
	});
}
