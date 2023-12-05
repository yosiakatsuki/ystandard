/** @type {import("tailwindcss").Config} */
module.exports = {
	content: [
		'./src/**/*.{js,jsx,ts,tsx}',
		'./**/*.{html,php}'
	],
	prefix: 'tw-',
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
			fontSize: {
				'fz-xxs': '10px',
				'fz-xs': '12px',
				'fz-s': '14px',
				'fz-m': '16px',
				'fz-l': '18px',
				'fz-xl': '20px',
				'fz-xxl': '24px',
			},
		},
	},
	plugins: [],
	important: true,
};
