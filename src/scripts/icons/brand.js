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
	siLinkedin,
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
} = require('simple-icons');
const fs = require('fs');
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
	siLinkedin,
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

iconList.map((value) => {
	// @ts-ignore
	data[value.slug] = value;
	return '';
});
fs.writeFile(jsonPath, JSON.stringify({ data }), (err) => {
	/* eslint-disable no-console */
	if (err) {
		console.log(err);
	} else {
		console.log('write end');
	}
	/* eslint-enable */
});
