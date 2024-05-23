module.exports = {
	plugins: [
		require('tailwindcss'),
		require('autoprefixer'),
		require('cssnano')({
			preset: [
				'default',
				{
					minifyFontValues: { removeQuotes: false },
					colormin: false,
				},
			],
		}),
		require('css-declaration-sorter')({ order: 'smacss' }),
	],
};
