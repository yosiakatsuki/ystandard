<?php
/**
 * カスタマイザーコントロール : カラーパレット
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

use ystandard\utils\Theme;

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
			$file_base  = 'customizer-control-ys-color-palette-control';
			$script_dir = get_template_directory() . '/js';
			$asset      = [
				'dependencies' => [],
				'version'      => Theme::get_ystandard_version(),
			];
			$asset_file = "{$script_dir}/{$file_base}.asset.php";

			if ( file_exists( $asset_file ) ) {
				$asset = wp_parse_args( require $asset_file, $asset );
			}

			wp_enqueue_script(
				'customizer-control-ys-color-palette-control',
				get_template_directory_uri() . "/js/{$file_base}.js",
				array_values( array_unique( array_merge( [ 'customize-controls' ], $asset['dependencies'] ) ) ),
				$asset['version'],
				true
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
			<div class="ys-color-palette-control__mount"></div>
			<# if ( data.description ) { #>
				<span id="{{ descriptionId }}" class="description customize-control-description">{{{ data.description }}}</span>
			<# } #>
			<?php
		}
	}
}
