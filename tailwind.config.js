/** @type {import("tailwindcss").Config} */
module.exports = {
	content: ['./src/**/*.{js,jsx,ts,tsx}', './**/*.{html,php}'],
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
