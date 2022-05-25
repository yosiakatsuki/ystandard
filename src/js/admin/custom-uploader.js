jQuery(document).ready(($) => {
	let customUploader = null;
	const classSelected = 'is-selected';
	let imageUrl = $('.ys-custom-uploader__hidden').val();
	$('.ys-custom-uploader__clear').click(() => {
		$('.ys-custom-uploader__hidden').val('');
		$('.ys-custom-uploader__preview').text('画像が選択されていません。');
		$('.ys-custom-uploader').toggleClass(classSelected);
	});
	$('.ys-custom-uploader__select').click(function (e) {
		e.preventDefault();
		if (customUploader) {
			customUploader.open();
			return;
		}
		//メディアアップローダー設定
		customUploader = wp.media({
			title: '画像を選択',
			button: {
				text: '選択',
			},
			multiple: false,
		});
		//画像選択された時の処理
		customUploader.on('select', () => {
			const image = customUploader.state().get('selection');
			image.each(function (file) {
				imageUrl = file.toJSON().url;
				$('.ys-custom-uploader__hidden').val(imageUrl);
				$('.ys-custom-uploader__preview')
					.text('')
					.append('<img src="' + imageUrl + '" />');
				$('.ys-custom-uploader').toggleClass(classSelected);
			});
		});
		customUploader.open();
	});
	if (imageUrl) {
		$('.ys-custom-uploader__preview')
			.text('')
			.append('<img src="' + imageUrl + '" />');
		$('.ys-custom-uploader').addClass(classSelected);
	}
});
