const path = require( 'path' );
const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );

module.exports = {
	...defaultConfig,
	entry: {
		ystandard: './src/js/ystandard.js',
		'admin/admin': './src/js/admin/admin.js',
		'admin/custom-uploader': './src/js/admin/custom-uploader.js',
		'admin/customizer-control': './src/js/admin/customizer-control.js',
		'admin/customizer-control-ys-color-control':
			'./src/js/admin/customizer-control-ys-color-control.js',
		'admin/customizer-preview': './src/js/admin/customizer-preview.js',
		'admin/search-icons': './src/js/admin/search-icons.js',
		'block-editor/post-meta': './src/js/block-editor/post-meta.js',
	},
	devtool: 'production' === process.env.NODE_ENV ? false : defaultConfig.devtool,
	output: {
		...defaultConfig.output,
		path: path.resolve( __dirname, 'js' ),
		filename: '[name].js',
		clean: false,
	},
};
