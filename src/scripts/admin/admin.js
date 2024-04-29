function copyClipboardForm( target ) {
	const text = jQuery( target ).prev();
	const info = jQuery( target ).next();
	text.select();
	document.execCommand( 'copy' );
	if ( info.length ) {
		info.show().fadeOut( 2000 );
	}
}

document.addEventListener( 'DOMContentLoaded', function () {
	/**
	 * クリップボードにコピー
	 */
	var copyButtons = jQuery( '.copy-form__button:not(.is-without-event)' );
	if ( copyButtons.length ) {
		copyButtons.each( ( i, elem ) => {
			elem.addEventListener( 'click', ( e ) => {
				e.preventDefault();
				copyClipboardForm( e.target );
			} )
		} );
	}
} );
