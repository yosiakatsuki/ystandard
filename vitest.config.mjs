import { fileURLToPath } from 'node:url';
import { defineConfig } from 'vitest/config';

export default defineConfig({
	resolve: {
		alias: {
			'@aktk/block-components': fileURLToPath(
				new URL('./src/aktk-block-components', import.meta.url)
			),
		},
	},
	oxc: {
		jsx: { runtime: 'automatic' },
	},
	test: {
		environment: 'node',
		include: ['src/**/*.test.{ts,tsx}'],
		restoreMocks: true,
	},
});
