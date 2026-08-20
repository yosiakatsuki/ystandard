// @ts-expect-error
const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const path = require('path');

module.exports = {
	...defaultConfig,
	entry: {
		ystandard: './src/scripts/ystandard.ts',
		admin: './src/scripts/admin/admin.js',
		'custom-uploader': './src/scripts/admin/custom-uploader.js',
		'customizer-control': './src/scripts/admin/customizer-control.js',
		'customizer-control-ys-color-palette-control':
			'./src/scripts/admin/customizer-control-ys-color-palette-control.jsx',
		'customizer-control-ys-toggle-group-control':
			'./src/scripts/admin/customizer-control-ys-toggle-group-control.jsx',
		'customizer-preview': './src/scripts/admin/customizer-preview.js',
		'search-icons': './src/scripts/admin/search-icons.js',
		'block-editor/post-settings':
			'./src/scripts/block-editor/post-settings/',
	},
	output: {
		filename: '[name].js',
		path: `${__dirname}/js`,
	},
	resolve: {
		...defaultConfig?.resolve,
		alias: {
			...defaultConfig?.resolve?.alias,
			'@aktk/block-components': path.resolve(
				__dirname,
				'src/aktk-block-components'
			),
		},
	},
	cache: false,
};
