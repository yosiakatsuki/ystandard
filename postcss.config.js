module.exports = {
	plugins: {
		tailwindcss: {},
		autoprefixer: { grid: 'autoplace' },
		cssnano: {
			preset: [
				'default',
				{
					minifyFontValues: { removeQuotes: false },
					colormin: false,
				},
			],
		},
		'css-declaration-sorter': { order: 'smacss' },
	},
};
