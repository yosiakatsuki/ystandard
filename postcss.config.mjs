import autoprefixer from 'autoprefixer';
import cssnano from 'cssnano';
import cssDeclarationSorter from 'css-declaration-sorter';

export default {
	plugins: [
		autoprefixer(),
		cssnano({
			preset: [
				'default',
				{
					minifyFontValues: { removeQuotes: false },
					colormin: false,
				},
			],
		}),
		cssDeclarationSorter({ order: 'smacss' }),
	],
};
