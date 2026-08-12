( function ( $ ) {
	wp.customize.bind( 'ready', function () {
		var customize = this;
		setupFontWeightControl( $, customize );

		var selector = '#_customize-input-background_repeat,#_customize-input-background_attachment,#_customize-input-background_size,[name="background-position"]';
		$( selector ).change( function () {
			setBackgroundPresetCustom( $ );
		} );
		$( '.customize-control-ys-color-control .wp-color-result' ).each( function ( index, element ) {
			$( element ).on( 'click', function () {
				if ( $( this ).hasClass( 'wp-picker-open' ) ) {
					const holder = $( this ).nextAll( '.wp-picker-holder' );
					const pickerInner = holder.find( '.iris-picker-inner' );
					const square = holder.find( '.iris-square' );
					square.height( pickerInner.height() );
				}
			} );
		} );
	} );
} )( jQuery );

/**
 * 選択フォントに合わせて標準ウエイト設定を更新する.
 *
 * @param {jQuery} $ jQuery.
 * @param {wp.customize} customize カスタマイザー.
 */
function setupFontWeightControl( $, customize ) {
	const typographyOption = window.ystdTypographyOption || {};
	const fontWeightChoices = typographyOption.fontWeightChoices || {};

	customize( 'ys_design_font_type', function ( fontTypeSetting ) {
		customize( 'ys_design_font_weight', function ( fontWeightSetting ) {
			customize.control( 'ys_design_font_weight', function ( control ) {
				const updateControl = function ( fontType ) {
					const choices = fontWeightChoices[ fontType ] || {};
					const choiceKeys = Object.keys( choices );
					const select = control.container.find( 'select' );

					select.empty();
					choiceKeys.forEach( function ( value ) {
						select.append(
							$( '<option>', {
								value,
								text: choices[ value ],
							} )
						);
					} );

					if (
						! Object.prototype.hasOwnProperty.call(
							choices,
							fontWeightSetting.get()
						)
					) {
						fontWeightSetting.set( '' );
					}
					select.val( fontWeightSetting.get() );
					control.active.set( 0 < choiceKeys.length );
				};

				updateControl( fontTypeSetting.get() );
				fontTypeSetting.bind( updateControl );
			} );
		} );
	} );
}

/**
 * 背景画像のプリセット変更.
 */
function setBackgroundPresetCustom( $ ) {
	var preset = $( '#_customize-input-background_preset' );
	if ( 'default' === preset.val() ) {
		preset.val( 'custom' );
	}
}
