// @ts-ignore
const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const path = require("path");

module.exports = {
	...defaultConfig,
	entry: {
		ystandard: './src/scripts/app/ystandard.ts',
		'ystandard-admin': './src/scripts/admin/ystandard-admin.ts',
		'ystandard-customizer-control' : './src/scripts/admin/ystandard-customizer-control.ts',
		'ystandard-customizer-preview' : './src/scripts/admin/ystandard-customizer-preview.ts',
	},
	resolve: {
		...defaultConfig.resolve,
		alias: {
			...defaultConfig.resolve.alias,
			'@aktk/blocks': path.resolve(__dirname, 'src/blocks'),
			'@ystd': path.resolve(__dirname, 'src/scripts'),
		},
	},
	cache: false,
};
