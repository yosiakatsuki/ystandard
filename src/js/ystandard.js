document.addEventListener( 'DOMContentLoaded', () => {
	// 検索ボタン.
	const searchButton = document.getElementById( 'global-nav__search-button' );
	if ( searchButton ) {
		searchButton.addEventListener( 'click', ( e ) => {
			const search = document.getElementById( 'global-nav__search' );
			if ( search ) {
				search.classList.toggle( 'is-active' );
			}
		} );
	}
	// メニュー.
	document.getElementById( 'global-nav__toggle' ).addEventListener( 'click', ( e ) => {
		e.currentTarget.classList.toggle( 'is-open' );
	} );
	// スムーススクロール.
	const links = document.querySelectorAll( 'a[href^="#"]' );
	for ( let i = 0; i < links.length; i++ ) {
		links[ i ].addEventListener( 'click', ( e ) => {
			e.preventDefault();
			const id = e.currentTarget.getAttribute( 'href' ).replace( '#', '' );
			const target = document.getElementById( id );
			if ( ! target ) {
				return;
			}
			const pos = target.getBoundingClientRect().top;
			window.scroll( {
				top: pos + window.pageYOffset - ( 16 * 3 ),
				behavior: 'smooth'
			} );
		} );
	}
} );
