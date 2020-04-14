import cssVars from 'css-vars-ponyfill';
import objectFitImages from 'object-fit-images';

document.addEventListener( 'DOMContentLoaded', () => {
	cssVars();
	objectFitImages( null, { watchMQ: true } );
} );
