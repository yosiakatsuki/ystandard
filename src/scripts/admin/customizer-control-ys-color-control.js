wp.customize.controlConstructor[ 'ys-color-control' ] = wp.customize.Control.extend( {
	ready: function () {
		var control = this,
			updating = false,
			picker;

		picker = this.container.find( '.color-picker-hex' );
		picker.val( control.setting() ).wpColorPicker( {
			palettes: control.params.palette,
			change: function () {
				updating = true;
				control.setting.set( picker.wpColorPicker( 'color' ) );
				updating = false;
			},
			clear: function () {
				updating = true;
				control.setting.set( '' );
				updating = false;
			}
		} );

		control.setting.bind( function ( value ) {
			if ( updating ) {
				return;
			}
			picker.val( value );
			picker.wpColorPicker( 'color', value );
		} );

		control.container.on( 'keydown', function ( event ) {
			var pickerContainer;
			if ( 27 !== event.which ) {
				return;
			}
			pickerContainer = control.container.find( '.wp-picker-container' );
			if ( pickerContainer.hasClass( 'wp-picker-active' ) ) {
				picker.wpColorPicker( 'close' );
				control.container.find( '.wp-color-result' ).focus();
				event.stopPropagation();
			}
		} );
	}
} );
