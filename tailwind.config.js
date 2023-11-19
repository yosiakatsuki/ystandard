/** @type {import("tailwindcss").Config} */
module.exports = {
	content: [
		'./src/**/*.{js,jsx,ts,tsx}',
		'./**/*.{html,php}'
	],
	corePlugins: {
		preflight: false,
	},
	theme: {
		extend: {
			colors: {
				'aktk-blue': '#07689f',
			},
			fontFamily: {
				orbitron: ['Orbitron', 'sans-serif'],
			},
		},
	},
	plugins: [],
	important: true,
};
