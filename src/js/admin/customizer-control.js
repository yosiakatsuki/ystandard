( function ( $ ) {
	wp.customize.bind( 'ready', function () {
		var customize = this;

		var selector = '#_customize-input-background_repeat,#_customize-input-background_attachment,#_customize-input-background_size,[name="background-position"]';
		$( selector ).change( function () {
			setBackgroundPresetCustom( $ );
		} );
		$( '.customize-control-ys-color-control .wp-color-result' ).each( function ( index, element ) {
			$( element ).on( 'click', function () {
				if ( $( this ).hasClass( 'wp-picker-open' ) ) {
					const holder = $( this ).nextAll( '.wp-picker-holder' );
					const pickerInner = holder.find( '.iris-picker-inner' );
					const square = holder.find( '.iris-square' );
					square.height( pickerInner.height() );
				}
			} );
		} );
	} );
} )( jQuery );

/**
 * 背景画像のプリセット変更.
 */
function setBackgroundPresetCustom( $ ) {
	var preset = $( '#_customize-input-background_preset' );
	if ( 'default' === preset.val() ) {
		preset.val( 'custom' );
	}
}
