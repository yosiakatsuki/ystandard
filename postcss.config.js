module.exports = {
	plugins: [
		require( 'autoprefixer' )( { grid: 'autoplace' } ),
		require( 'cssnano' )( {
			preset: [
				'default',
				{ minifyFontValues: { removeQuotes: false } }
			]
		} ),
		require( 'css-declaration-sorter' )( { order: 'smacss' } ),
	],
}
