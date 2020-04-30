jQuery( document ).ready( ( $ ) => {
	let custom_uploader = null;
	const classSelected = 'is-selected';
	let imageUrl = $( '.ys-custom-uploader__hidden' ).val();
	$( '.ys-custom-uploader__clear' ).click( ( e ) => {
		$( '.ys-custom-uploader__hidden' ).val( '' );
		$( '.ys-custom-uploader__preview' ).text( '画像が選択されてません。' );
		$( '.ys-custom-uploader' ).toggleClass( classSelected );
	} );
	$( '.ys-custom-uploader__select' ).click( function ( e ) {
		e.preventDefault();
		if ( custom_uploader ) {
			custom_uploader.open();
			return;
		}
		//メディアアップローダー設定
		custom_uploader = wp.media( {
			title: '画像を選択',
			button: {
				text: '選択'
			},
			multiple: false
		} );
		//画像選択された時の処理
		custom_uploader.on( 'select', () => {
			const image = custom_uploader.state().get( 'selection' );
			image.each( function ( file ) {
				imageUrl = file.toJSON().url;
				$( '.ys-custom-uploader__hidden' ).val( imageUrl );
				$( '.ys-custom-uploader__preview' ).text( '' ).append( '<img src="' + imageUrl + '" />' );
				$( '.ys-custom-uploader' ).toggleClass( classSelected );
			} );
		} );
		custom_uploader.open();
	} );
	if ( imageUrl ) {
		console.log(imageUrl);
		$( '.ys-custom-uploader__preview' ).text( '' ).append( '<img src="' + imageUrl + '" />' );
		$( '.ys-custom-uploader' ).addClass( classSelected );
	}
} );
