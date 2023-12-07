<?php
/**
 * カスタマイザーコントロール : カラーコントロール
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */

namespace ystandard\customizer;

defined( 'ABSPATH' ) || die();

if ( class_exists( 'WP_Customize_Color_Control' ) ) {

	/**
	 * Class Color_Control
	 *
	 * @package ystandard
	 */
	class Color_Control extends \WP_Customize_Color_Control {
		/**
		 * Type.
		 *
		 * @var string
		 */
		public $type = 'ys-color-control';

		/**
		 * Color Palette.
		 *
		 * @var array
		 */
		public $palette;

		/**
		 * Enqueue
		 */
		public function enqueue() {
			parent::enqueue();
			$handle = 'customizer-control-ys-color-control';
			wp_register_script(
				$handle,
				false,
				[ 'customize-controls', 'jquery', 'customize-base', 'wp-color-picker' ]
			);
			wp_add_inline_script( $handle, $this->get_script() );
			wp_enqueue_script( $handle );
		}

		/**
		 * Refresh the parameters passed to the JavaScript via JSON.
		 */
		public function to_json() {
			parent::to_json();
			$this->json['palette'] = $this->palette;
		}

		private function get_script() {
			return <<<SCRIPT
			wp.customize.controlConstructor[ 'ys-color-control' ] = wp.customize.Control.extend( {
				ready: function () {
					const control = this;
					let	updating = false;
					const picker = this.container.find( '.color-picker-hex' );

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
			wp.customize.bind( 'ready', function () {
				const $ = jQuery;
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
			SCRIPT;
		}
	}
}
