(function ($) {
	wp.customize.bind('ready', function () {
		var selector =
			'#_customize-input-background_repeat,#_customize-input-background_attachment,#_customize-input-background_size,[name="background-position"]';
		$(selector).change(function () {
			setBackgroundPresetCustom($);
		});

		for (var index = 1; index <= 6; index++) {
			bindFontSizePresetControls($, index);
		}
	});
})(jQuery);

/**
 * 背景画像のプリセット変更.
 */
function setBackgroundPresetCustom($) {
	var preset = $('#_customize-input-background_preset');
	// 個別項目を変更した場合は、背景プリセットをカスタムへ切り替える.
	if ('default' === preset.val()) {
		preset.val('custom');
	}
}

/**
 * 文字サイズプリセットの入力欄を連動させる.
 *
 * @param {jQuery} $ jQuery.
 * @param {number} index 設定番号.
 */
function bindFontSizePresetControls($, index) {
	var prefix = 'ys-block-editor-font-size-preset-' + index;
	var typeSetting = wp.customize(prefix + '-type');
	var unitSetting = wp.customize(prefix + '-unit');

	// 文字サイズ設定が登録されていない画面では処理しない.
	if (!typeSetting || !unitSetting) {
		return;
	}

	/**
	 * 種類に応じて必要な入力欄だけを表示する.
	 *
	 * @param {string} type 文字サイズの種類.
	 */
	var updateControlVisibility = function (type) {
		setControlVisibility(prefix + '-static', 'static' === type);
		setControlVisibility(prefix + '-min', 'fluid' === type);
		setControlVisibility(prefix + '-max', 'fluid' === type);
		setControlVisibility(prefix + '-unit', 'fluid' === type);
	};

	/**
	 * 単位に合わせて数値入力のstepを変更する.
	 *
	 * @param {string} unit 単位.
	 */
	var updateNumberStep = function (unit) {
		var step = 'px' === unit ? 1 : 0.1;
		['min', 'max'].forEach(function (field) {
			wp.customize.control(prefix + '-' + field, function (control) {
				control.container
					.find('input[type="number"]')
					.attr('step', step);
			});
		});
	};

	typeSetting.bind(updateControlVisibility);
	unitSetting.bind(updateNumberStep);
	updateControlVisibility(typeSetting.get());
	updateNumberStep(unitSetting.get());
}

/**
 * カスタマイザーコントロールの表示を切り替える.
 *
 * @param {string} controlId コントロールID.
 * @param {boolean} isVisible 表示するか.
 */
function setControlVisibility(controlId, isVisible) {
	wp.customize.control(controlId, function (control) {
		control.container.toggle(isVisible);
	});
}
