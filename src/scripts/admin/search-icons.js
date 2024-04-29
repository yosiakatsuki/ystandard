document.addEventListener( 'DOMContentLoaded', () => {
	const search = document.getElementById( 'icon-search' );
	search.addEventListener( 'keyup', ( e ) => {
		const $ = jQuery;
		const inputValue = $( e.target ).val();
		$( '.ys-icon-search__item' ).each( ( index, element ) => {
			if ( ! inputValue ) {
				$( element ).css( 'display', 'block' );
			} else {
				const iconName = $( element ).data( 'icon-name' );
				const display = -1 === iconName.indexOf( inputValue ) ? 'none' : 'block';
				$( element ).css( 'display', display );
			}
		} );
	} );
} );
