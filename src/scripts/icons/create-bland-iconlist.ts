const fs = require('fs');
const OUTPUT_PATH = './library/simple-icons/brand-icons.json';
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
} = require('simple-icons');

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
	siX,
];
const data = {};

iconList.forEach((value) => {
	// @ts-expect-error
	data[value.slug] = value;
});

// @ts-expect-error
fs.writeFile(OUTPUT_PATH, JSON.stringify({data}), (err) => {
	/* eslint-disable no-console */
	if (err) {
		console.log(err);
	} else {
		console.log('write end');
	}
	/* eslint-enable */
});
