<?php
/**
 * カスタマイザーコントロール：余白.
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

// WordPressのカスタマイザーAPIが利用できる時だけコントロールを定義する.
if ( class_exists( 'WP_Customize_Control' ) ) {

	/**
	 * Class Spacer_Control
	 *
	 * @package ystandard
	 */
	class Spacer_Control extends \WP_Customize_Control {

		/**
		 * Type.
		 *
		 * @var string
		 */
		public $type = 'ys-spacer-control';

		/**
		 * 余白サイズ.
		 *
		 * @var int
		 */
		public $size = 24;

		/**
		 * 余白を出力.
		 */
		protected function render_content() {
			?>
			<div class="ys-customizer-spacer" style="height:<?php echo esc_attr( absint( $this->size ) ); ?>px" aria-hidden="true"></div>
			<?php
		}
	}
}
