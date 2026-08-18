<?php
/**
 * カスタマイザーコントロール：横並び選択.
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

use ystandard\utils\Theme;

defined( 'ABSPATH' ) || die();

// WordPressのカスタマイザーAPIが利用できる時だけコントロールを定義する.
if ( class_exists( 'WP_Customize_Control' ) ) {

	/**
	 * Class Toggle_Group_Control
	 *
	 * @package ystandard
	 */
	class Toggle_Group_Control extends \WP_Customize_Control {

		/**
		 * Type.
		 *
		 * @var string
		 */
		public $type = 'ys-toggle-group-control';

		/**
		 * スクリプト読み込み.
		 */
		public function enqueue() {
			$file_base  = 'customizer-control-ys-toggle-group-control';
			$script_dir = get_template_directory() . '/js';
			$asset      = [
				'dependencies' => [],
				'version'      => Theme::get_ystandard_version(),
			];
			$asset_file = "{$script_dir}/{$file_base}.asset.php";

			// ビルド済みアセットがあれば実際の依存関係とバージョンを使用する.
			if ( file_exists( $asset_file ) ) {
				$asset = wp_parse_args( require $asset_file, $asset );
			}

			wp_enqueue_script(
				'customizer-control-ys-toggle-group-control',
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
			$this->json['choices'] = $this->choices;
		}

		/**
		 * コントロールのテンプレート.
		 */
		protected function content_template() {
			?>
			<div class="ys-toggle-group-control__mount"></div>
			<?php
		}
	}
}
