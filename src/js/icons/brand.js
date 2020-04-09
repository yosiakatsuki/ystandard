const simpleIcons = require( 'simple-icons' );
const fs = require( 'fs' );
const jsonPath = './library/simple-icons/brand-icons.json';
const iconList = [
	'Twitter',
	'Facebook',
	'Hatena Bookmark',
	'Pocket',
	'Line',
	'Feedly',
	'RSS',
	'WordPress',
	'Pinterest',
	'Instagram',
	'linkedIn',
	'YouTube',
	'Twitch',
	'Dribbble',
	'GitHub',
	'Tumblr',
	'Amazon',
];
const data = {};

iconList.map( ( value ) => {
	const icon = simpleIcons.get( value );
	data[ icon.slug ] = icon;
	return '';
} );
fs.writeFile( jsonPath, JSON.stringify( { data } ), ( err ) => {
	/* eslint-disable no-console */
	if ( err ) {
		console.log( err );
	} else {
		console.log( 'write end' );
	}
	/* eslint-enable */
} );
