// @ts-ignore
const defaultConfig = require('@wordpress/scripts/config/webpack.config');

module.exports = {
	...defaultConfig,
	entry: {
		ystandard: './src/scripts/app/ystandard.ts',
		'ystandard-admin': './src/scripts/admin/ystandard-admin.ts',
	},
	resolve: {
		...defaultConfig.resolve,
		alias: {
			...defaultConfig.resolve.alias,
		},
	},
	cache: false,
};
