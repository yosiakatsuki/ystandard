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
	const globalNav = document.getElementById( 'global-nav__toggle' );
	if ( globalNav ) {
		globalNav.addEventListener( 'click', ( e ) => {
			e.currentTarget.classList.toggle( 'is-open' );
			const mobileFooter = document.getElementsByClassName( 'footer-mobile-nav' );
			if ( mobileFooter ) {
				mobileFooter[ 0 ].classList.toggle( 'is-hide' );
			}
		} );
	}
	// グロナビ閉じる.
	const globalNavLinks = document.querySelectorAll( '.global-nav a[href*="#"]' );
	for ( let i = 0; i < globalNavLinks.length; i++ ) {
		globalNavLinks[ i ].addEventListener( 'click', ( e ) => {
			const toggle = document.getElementById( 'global-nav__toggle' );
			if ( toggle ) {
				toggle.classList.remove( 'is-open' );
			}
			const mobileFooter = document.getElementsByClassName( 'footer-mobile-nav' );
			if ( mobileFooter ) {
				mobileFooter[ 0 ].classList.remove( 'is-hide' );
			}
		} );
	}
	// スムーススクロール.
	const links = document.querySelectorAll( 'a[href^="#"]' );
	for ( let i = 0; i < links.length; i++ ) {
		links[ i ].addEventListener( 'click', ( e ) => {
			e.preventDefault();
			let top = 0;
			const id = e.currentTarget.getAttribute( 'href' ).replace( '#', '' );
			const target = document.getElementById( id );
			if ( ! target && '' !== id ) {
				return;
			}
			if ( target ) {
				const pos = target.getBoundingClientRect().top;
				let buffer = 50;
				const header = document.getElementById( 'masthead' );
				if ( 'fixed' === getComputedStyle( header, null ).getPropertyValue( 'position' ) ) {
					buffer = header.getBoundingClientRect().bottom + 20;
				}
				top = pos + window.pageYOffset - buffer;
			}

			window.scroll( {
				top: top,
				behavior: 'smooth'
			} );
		} );
	}
	// TOPへ戻る.
	const backToTop = document.getElementById( 'back-to-top' );
	if ( backToTop && backToTop.classList.contains( 'is-square' ) ) {
		const width = backToTop.getBoundingClientRect().width;
		const height = backToTop.getBoundingClientRect().height;
		const size = width < height ? `${ height }px` : `${ width }px`;
		backToTop.style.width = size;
		backToTop.style.height = size;
		console.log( backToTop.style.width );
		backToTop.addEventListener( 'click', ( e ) => {
			e.preventDefault();
			window.scroll( {
				top: 0,
				behavior: 'smooth'
			} );
		} );
	}
	// スクロールバー分.
	const scrollbar = window.innerWidth - document.body.clientWidth;
	if ( getComputedStyle( document.documentElement ).getPropertyValue( '--scrollbar-width' ) ) {
		document.querySelector( ':root' ).style.setProperty( '--scrollbar-width', scrollbar + 'px' );
	}
} );
