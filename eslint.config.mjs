import wordpress from '@wordpress/eslint-plugin';

export default [
	{ ignores: ['**/*.d.ts'] },
	{ files: ['**/*.{js,jsx,ts,tsx}'] },
	...wordpress.configs.recommended,
	...wordpress.configs['test-unit'].map((config) => ({
		...config,
		files: ['**/*.test.{js,jsx,ts,tsx}'],
	})),
	{
		languageOptions: {
			globals: {
				ystdScriptOption: 'writable',
				jQuery: 'readonly',
			},
		},
		rules: {
			'import/no-unresolved': 'off',
			'import/no-extraneous-dependencies': 'off',
			'@wordpress/no-unsafe-wp-apis': 'off',
			'jsdoc/require-returns-description': 'off',
			'jsdoc/require-param-type': 'off',
			'react-hooks/exhaustive-deps': 'off',
			'jsdoc/check-param-names': 'off',
		},
	},
];
