const {
	siFacebook,
	siHatenabookmark,
	siPocket,
	siLine,
	siFeedly,
	siRss,
	siWordpress,
	siPinterest,
	siInstagram,
	siYoutube,
	siTwitch,
	siDribbble,
	siGithub,
	siTumblr,
	siAmazon,
	siDiscord,
	siTiktok,
	siX,
	siBluesky,
} = require( 'simple-icons' );
const fs = require( 'fs' );
const jsonPath = './library/simple-icons/brand-icons.json';
const iconList = [
	siFacebook,
	siHatenabookmark,
	siPocket,
	siLine,
	siFeedly,
	siRss,
	siWordpress,
	siPinterest,
	siInstagram,
	siYoutube,
	siTwitch,
	siDribbble,
	siGithub,
	siTumblr,
	siAmazon,
	siDiscord,
	siTiktok,
	siX,
	siBluesky,
];
const data = {};
iconList.forEach( ( value ) => {
	if ( value?.slug ) {
		console.log( value.slug );
		data[ value.slug ] = value;
	}
} );

console.log(`iconList: ${iconList.length}`);
console.log(`data: ${Object.keys(data).length}`);

fs.writeFile( jsonPath, JSON.stringify( { data } ), ( err ) => {
	/* eslint-disable no-console */
	if ( err ) {
		console.log( err );
	} else {
		console.log( 'write end' );
	}
	/* eslint-enable */
} );
