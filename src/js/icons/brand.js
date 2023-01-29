const {
	siTwitter,
	siFacebook,
	siHatenabookmark,
	siPocket,
	siLine,
	siFeedly,
	siRss,
	siWordpress,
	siPinterest,
	siInstagram,
	siLinkerd,
	siYoutube,
	siTwitch,
	siDribbble,
	siGithub,
	siTumblr,
	siAmazon,
	siDiscord,
	siTiktok,
	siLinkedin,
} = require( 'simple-icons' );
const fs = require( 'fs' );
const jsonPath = './library/simple-icons/brand-icons.json';
const iconList = [
	siTwitter,
	siFacebook,
	siHatenabookmark,
	siPocket,
	siLine,
	siFeedly,
	siRss,
	siWordpress,
	siPinterest,
	siInstagram,
	siLinkedin,
	siYoutube,
	siTwitch,
	siDribbble,
	siGithub,
	siTumblr,
	siAmazon,
	siDiscord,
	siTiktok,
];
const data = {};

iconList.map( ( value ) => {
	data[ value.slug ] = value;
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
