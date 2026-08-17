const { ColorPalette, Popover, SlotFillProvider } = wp.components;
const { createElement, createRoot } = wp.element;

wp.customize.controlConstructor[ 'ys-color-palette-control' ] =
	wp.customize.Control.extend( {
		ready: function () {
			const control = this;
			const mount = control.container
				.get( 0 )
				.querySelector( '.ys-color-palette-control__mount' );

			if ( ! mount ) {
				return;
			}

			control.colorPaletteRoot = createRoot( mount );
			control.renderColorPalette = function () {
				control.colorPaletteRoot.render(
					createElement(
						SlotFillProvider,
						null,
						createElement( Popover.Slot ),
						createElement( ColorPalette, {
							clearable: true,
							colors: control.params.palette || [],
							enableAlpha: Boolean( control.params.enableAlpha ),
							onChange: function ( color ) {
								control.setting.set( color || '' );
							},
							value: control.setting(),
						} )
					)
				);
			};

			control.setting.bind( control.renderColorPalette );
			control.renderColorPalette();
		},
	} );
