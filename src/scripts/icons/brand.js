const {
	siFacebook,
	siHatenabookmark,
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
	siDiscord,
	siTiktok,
	siX,
	siBluesky,
} = require('simple-icons');
// v16で削除されたアイコンは、v14.4.0由来の既存データを維持する。
const legacyIcons = require('./legacy-brand-icons.json');
const fs = require('fs');
const jsonPath = './library/simple-icons/brand-icons.json';
const iconList = [
	siFacebook,
	siHatenabookmark,
	legacyIcons.pocket,
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
	legacyIcons.amazon,
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
