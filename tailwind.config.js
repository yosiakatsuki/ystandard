/** @type {import("tailwindcss").Config} */
module.exports = {
	content: [
		'./src/**/*.{js,jsx,ts,tsx}',
		'./*.php',
		'./template-parts/**/*.php',
		'./page-template/**/*.php',
		'./inc/**/*.php',
		'./js/**/*.js',
	],
	prefix: 'tw-',
	corePlugins: {
		preflight: false,
	},
	theme: {
		extend: {
			colors: {
				'aktk-blue': 'var(--ys--global--brand-color--main)',
				'ys-alert-success-bg': 'var(--ystd--alert--background--green)',
				'ys-alert-success-text':
					'var(--ystd--alert--text-color--green)',
				'ys-alert-warning-bg': 'var(--ystd--alert--background--yellow)',
				'ys-alert-warning-text':
					'var(--ystd--alert--text-color--yellow)',
				'ys-alert-error-bg': 'var(--ystd--alert--background--red)',
				'ys-alert-error-text': 'var(--ystd--alert--text-color--red)',
				'ys-alert-info-bg': 'var(--ystd--alert--background--blue)',
				'ys-alert-info-text': 'var(--ystd--alert--text-color--blue)',
			},
			fontFamily: {
				orbitron: ['Orbiter', 'sans-serif'],
			},
			fontSize: {
				'ys-fz-xxs': '10px',
				'ys-fz-xs': '12px',
				'ys-fz-s': '14px',
				'ys-fz-m': '16px',
				'ys-fz-l': '18px',
				'ys-fz-xl': '20px',
				'ys-fz-xxl': '24px',
				'ys-customizer-small': '12px',
			},
		},
	},
	plugins: [],
	important: true,
};
