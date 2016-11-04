jQuery(document).ready(function($){
	var custom_uploader;
	var title = '画像を選択',
			urltarget = '.ys-custom-image-upload-url',
			previewtarget = '.ys-custom-image-upload-preview';

	// 画像の削除
	$('.ys-custom-image-clear').click(function(e) {
		// 取得URLの出力先
		if($(this).attr('data-uploaderurl')){
			urltarget = $(this).attr('data-uploaderurl');
		}
		// 取得画像のプレビュー先
		if($(this).attr('data-uploaderpreview')){
			previewtarget = $(this).attr('data-uploaderpreview');
		}
		$(urltarget).val('');
		$(previewtarget).text('画像が選択されてません。');
		$('.ys-custom-image-upload').css('display','block');
		$('.ys-custom-image-clear').css('display','none');
	});

	//メディアアップローダー
	$('.ys-custom-image-upload').click(function(e) {
		e.preventDefault();
		//オブジェクト生成されてれば開く
		if (custom_uploader) {
				custom_uploader.open();
				return;
		}

		// メディアアップローダーのタイトルに指定があれば変更
		if($(this).attr('data-uploadertitle')){
			title = $(this).attr('data-uploadertitle');
		}
		// 取得URLの出力先
		if($(this).attr('data-uploaderurl')){
			urltarget = $(this).attr('data-uploaderurl');
		}
		// 取得画像のプレビュー先
		if($(this).attr('data-uploaderpreview')){
			previewtarget = $(this).attr('data-uploaderpreview');
		}
		//メディアアップローダー設定
		custom_uploader = wp.media({
			title: title,
			button: {
					text: '選択'
			},
			multiple: false
		});
		//画像選択された時の処理
		custom_uploader.on('select', function() {
			var image = custom_uploader.state().get('selection');
			//1枚だけなんだけどとりあえず
			image.each(function(file){
							var selectimgurl = file.toJSON().url;
							// URLの出力
							$(urltarget).val(selectimgurl);
							// プレビュー画像の出力
							$(previewtarget).text('');
							$(previewtarget).append('<img style="max-width:100px;height:auto;" src="'+selectimgurl+'" />');
							$('.ys-custom-image-upload').css('display','none');
							$('.ys-custom-image-clear').css('display','block');
					});
		});
		//メディアアップローダー開く
		custom_uploader.open();
	});
});