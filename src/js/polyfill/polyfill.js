import cssVars from 'css-vars-ponyfill';
import objectFitImages from 'object-fit-images';
import Stickyfill from 'stickyfilljs'

document.addEventListener( 'DOMContentLoaded', () => {
	cssVars();
	objectFitImages( null, { watchMQ: true } );

	Stickyfill.add( document.querySelectorAll( '.sidebar__fixed' ) )
} );
