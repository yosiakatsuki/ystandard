module.exports = {
	mode: "production",

	entry: {
		'font-awesome-ystd': './src/js/font-awesome/app.js',
		'polyfill': './src/js/polyfill/polyfill.js',
		'search-icons': './src/js/admin/search-icons.js',
	},
	output: {
		filename: '[name].js',
		path: `${ __dirname }/js`
	},
	module: {
		rules: [
			{
				test: /\.js$/,
				use: [
					{
						loader: 'babel-loader',
						options: {
							presets: [
								'@babel/preset-env',
							]
						}
					}
				]
			}
		]
	},
	resolve: {
		alias: {
			'vue$': 'vue/dist/vue.esm.js'
		}
	}
};
