document.addEventListener( 'DOMContentLoaded', () => {
	// 検索ボタン.
	const searchButton = document.getElementById( 'global-nav__search-button' );
	if ( searchButton ) {
		searchButton.addEventListener( 'click', ( e ) => {
			const search = document.getElementById( 'global-nav__search' );
			if ( search ) {
				search.classList.toggle( 'is-active' );
				setTimeout( function () {
					document.querySelector( '#global-nav__search .search-field' ).focus();
				}, 50 );
			}
		} );
	}
	const closeButton = document.getElementById( 'global-nav__search-close' );
	if ( closeButton ) {
		closeButton.addEventListener( 'click', ( e ) => {
			const search = document.getElementById( 'global-nav__search' );
			if ( search ) {
				search.classList.remove( 'is-active' );
			}
		} );
	}
	// メニュー.
	const glovalNav = document.getElementById( 'global-nav__toggle' );
	if ( glovalNav ) {
		glovalNav.addEventListener( 'click', ( e ) => {
			e.currentTarget.classList.toggle( 'is-open' );
		} );
	}
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
			let buffer = 50;
			const header = document.getElementById( 'masthead' );
			if ( 'fixed' === getComputedStyle( header, null ).getPropertyValue( 'position' ) ) {
				buffer = header.getBoundingClientRect().bottom + 20;
			}
			window.scroll( {
				top: pos + window.pageYOffset - buffer,
				behavior: 'smooth'
			} );
		} );
	}
} );
