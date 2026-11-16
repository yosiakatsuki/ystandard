function copyClipboardForm(target) {
	const text = jQuery(target).prev();
	const info = jQuery(target).next();
	text.select();
	document.execCommand('copy');
	// 完了案内の要素がある場合だけ、コピー後のメッセージを表示する。
	if (info.length) {
		info.show().fadeOut(2000);
	}
}

document.addEventListener('DOMContentLoaded', function () {
	/**
	 * クリップボードにコピー
	 */
	const copyButtons = jQuery('.copy-form__button:not(.is-without-event)');
	// コピー用ボタンがある画面だけ、クリック処理を登録する。
	if (copyButtons.length) {
		copyButtons.each((i, elem) => {
			elem.addEventListener('click', (e) => {
				e.preventDefault();
				copyClipboardForm(e.target);
			});
		});
	}
});
