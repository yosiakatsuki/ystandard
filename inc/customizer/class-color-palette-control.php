<?php
/**
 * カスタマイザーコントロール : カラーパレット
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

if ( class_exists( 'WP_Customize_Control' ) ) {

	/**
	 * Class Color_Palette_Control
	 *
	 * @package ystandard
	 */
	class Color_Palette_Control extends \WP_Customize_Control {
		/**
		 * Type.
		 *
		 * @var string
		 */
		public $type = 'ys-color-palette-control';

		/**
		 * Color Palette.
		 *
		 * @var array
		 */
		public $palette = [];

		/**
		 * 不透明度設定を有効にするか.
		 *
		 * @var bool
		 */
		public $enable_alpha = true;

		/**
		 * スクリプト読み込み.
		 */
		public function enqueue() {
			$file_name = 'customizer-control-ys-color-palette-control.js';
			wp_enqueue_script(
				'customizer-control-ys-color-palette-control',
				get_template_directory_uri() . "/js/{$file_name}",
				[ 'customize-controls', 'wp-components', 'wp-element' ],
				filemtime( get_template_directory() . "/js/{$file_name}" ),
				false
			);
		}

		/**
		 * JavaScriptへ渡すデータを作成.
		 */
		public function to_json() {
			parent::to_json();
			$this->json['palette']     = $this->palette;
			$this->json['enableAlpha'] = $this->enable_alpha;
		}

		/**
		 * コントロールのテンプレート.
		 */
		protected function content_template() {
			?>
			<# var descriptionId = '_customize-description-' + data.id; #>
			<# if ( data.label ) { #>
				<span class="customize-control-title">{{ data.label }}</span>
			<# } #>
			<# if ( data.description ) { #>
				<span id="{{ descriptionId }}" class="description customize-control-description">{{{ data.description }}}</span>
			<# } #>
			<div class="ys-color-palette-control__mount"<# if ( data.description ) { #> aria-describedby="{{ descriptionId }}"<# } #>></div>
			<?php
		}
	}
}
