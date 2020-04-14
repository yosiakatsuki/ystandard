module.exports = {
	mode: "production",

	entry: {
		'font-awesome-ystd': './src/js/font-awesome/app.js',
		'polyfill': './src/js/polyfill/polyfill.js',
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
	}
};
