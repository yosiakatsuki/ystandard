export function setBackgroundPreset() {
	// @ts-ignore
	wp.customize.bind('ready', function () {
		// @ts-ignore
		const $ = jQuery;
		const selector = [
			'#_customize-input-background_repeat',
			'#_customize-input-background_attachment',
			'#_customize-input-background_size',
			'[name="background-position"]',
		].join(',');
		$(selector).change(function () {
			var preset = $('#_customize-input-background_preset');
			if ('default' === preset.val()) {
				preset.val('custom');
			}
		});
	});
}
