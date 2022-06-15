module.exports = {
	plugins: [
		require( 'autoprefixer' ),
		require( 'cssnano' )( {
			preset: [
				'default',
				{ minifyFontValues: { removeQuotes: false } },
			],
		} ),
		require( 'css-declaration-sorter' )( { order: 'smacss' } ),
	],
};
