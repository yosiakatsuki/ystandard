document.addEventListener('DOMContentLoaded', () => {
	const search = document.getElementById('icon-search');
	search.addEventListener('keyup', (e) => {
		const $ = jQuery;
		const inputValue = $(e.target).val();
		$('.ys-icon-search__item').each((index, element) => {
			// 検索語が空の場合は、すべてのアイコンを表示する。
			if (!inputValue) {
				$(element).css('display', 'block');
			} else {
				// 入力された語を含むアイコンだけを表示する。
				const iconName = $(element).data('icon-name');
				const display =
					-1 === iconName.indexOf(inputValue) ? 'none' : 'block';
				$(element).css('display', display);
			}
		});
	});
});
