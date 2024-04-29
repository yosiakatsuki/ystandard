// @ts-expect-error
const defaultConfig = require('@wordpress/scripts/config/webpack.config');

module.exports = {
	...defaultConfig,
	entry: {
		ystandard: './src/scripts/ystandard.ts',
		admin: './src/scripts/admin/admin.js',
		'custom-uploader': './src/scripts/admin/custom-uploader.js',
		'customizer-control': './src/scripts/admin/customizer-control.js',
		'customizer-control-ys-color-control':
			'./src/scripts/admin/customizer-control-ys-color-control.js',
		'customizer-preview': './src/scripts/admin/customizer-preview.js',
		'search-icons': './src/scripts/admin/search-icons.js',
	},
	output: {
		filename: '[name].js',
		path: `${__dirname}/js`,
	},
	resolve: {
		...defaultConfig?.resolve,
		alias: {
			...defaultConfig?.resolve?.alias,
		},
	},
	cache: false,
};
