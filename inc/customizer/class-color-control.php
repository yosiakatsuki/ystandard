<?php
/**
 * カスタマイザーコントロール : カラーコントロール
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

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
			$file_name = 'customizer-control-ys-color-control.js';
			wp_enqueue_script(
				'customizer-control-ys-color-control',
				get_template_directory_uri() . "/js/admin/${file_name}",
				[ 'customize-controls', 'jquery', 'customize-base', 'wp-color-picker' ],
				filemtime( get_template_directory() . "/js/admin/${file_name}" ),
				false
			);
		}

		/**
		 * Refresh the parameters passed to the JavaScript via JSON.
		 */
		public function to_json() {
			parent::to_json();
			$this->json['palette'] = $this->palette;
		}
	}
}
