export function setColorControlClickEvent() {
	// @ts-ignore
	wp.customize.bind('ready', function () {
		// @ts-ignore
		const $ = jQuery;
		// @ts-ignore
		$('.customize-control-ys-color-control .wp-color-result').each(function (index, element) {
			$(element).on('click', function () {
				// @ts-ignore
				if ($(this).hasClass('wp-picker-open')) {
					// @ts-ignore
					const holder = $(this).nextAll('.wp-picker-holder');
					const pickerInner = holder.find('.iris-picker-inner');
					const square = holder.find('.iris-square');
					square.height(pickerInner.height());
				}
			});
		});
	});
}

export function extendColorControl() {
	// @ts-ignore
	wp.customize.controlConstructor['ys-color-control'] = wp.customize.Control.extend({
		ready: function () {
			const control = this;
			let updating = false;
			const picker = this.container.find('.color-picker-hex');

			picker.val(control.setting()).wpColorPicker({
				palettes: control.params.palette,
				change: function () {
					updating = true;
					control.setting.set(picker.wpColorPicker('color'));
					updating = false;
				},
				clear: function () {
					updating = true;
					control.setting.set('');
					updating = false;
				}
			});
			// @ts-ignore
			control.setting.bind(function (value) {
				if (updating) {
					return;
				}
				picker.val(value);
				picker.wpColorPicker('color', value);
			});
			// @ts-ignore
			control.container.on('keydown', function (event) {
				var pickerContainer;
				if (27 !== event.which) {
					return;
				}
				pickerContainer = control.container.find('.wp-picker-container');
				if (pickerContainer.hasClass('wp-picker-active')) {
					picker.wpColorPicker('close');
					control.container.find('.wp-color-result').focus();
					event.stopPropagation();
				}
			});
		}
	});
}
