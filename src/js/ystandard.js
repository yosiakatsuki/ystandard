document.addEventListener( 'DOMContentLoaded', () => {
	const searchButton = document.getElementById( 'global-nav__search-button' );
	if ( searchButton ) {
		searchButton.addEventListener( 'click', ( e ) => {
			const search = document.getElementById( 'global-nav__search' );
			if ( search ) {
				search.classList.toggle( 'is-active' );
			}
		} );
	}
	document.getElementById( 'global-nav__toggle' ).addEventListener( 'click', (e) => {
		e.currentTarget.classList.toggle( 'is-open' );
	} );

} );
