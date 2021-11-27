module.exports = {
	mode: "production",

	entry: {
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
								'@babel/env',
							],
							targets: {
								ie: '11',
								esmodules: true
							}
						}
					}
				]
			}
		]
	},
	target: [ 'web', 'es5' ],
};
