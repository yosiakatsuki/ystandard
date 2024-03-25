// @ts-expect-error
const defaultConfig = require('@wordpress/scripts/config/webpack.config');

module.exports = {
	...defaultConfig,
	entry: {
		ystandard: './src/js/ystandard.ts',
		admin: './src/js/admin/admin.js',
		'custom-uploader': './src/js/admin/custom-uploader.js',
		'customizer-control': './src/js/admin/customizer-control.js',
		'customizer-control-ys-color-control':
			'./src/js/admin/customizer-control-ys-color-control.js',
		'customizer-preview': './src/js/admin/customizer-preview.js',
		'search-icons': './src/js/admin/search-icons.js',
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
