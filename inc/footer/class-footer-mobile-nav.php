<?php
/**
 * フッターモバイルメニュー
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

use ystandard\utils\Convert;
use ystandard\utils\CSS;

defined( 'ABSPATH' ) || die();

/**
 * Class Footer
 *
 * @package ystandard
 */
class Footer_Mobile_Nav {
	/**
	 * Footer constructor.
	 */
	public function __construct() {
		add_action( 'wp_footer', [ $this, 'footer_mobile_nav' ], 1 );
		add_filter( 'ys_get_css_custom_properties_args', [ $this, 'add_css_var_mobile_footer_menu' ] );
		add_filter( 'ys_get_inline_css', [ $this, 'add_footer_mobile_nav_css' ] );
	}

	/**
	 * モバイルフッター表示判断
	 *
	 * @return bool
	 */
	public static function show_footer_mobile_nav() {
		$result = has_nav_menu( 'mobile-footer' );

		$result = Convert::to_bool( apply_filters( 'ys_show_footer_mobile_nav', $result ) );

		if ( Widget::is_legacy_widget_preview() ) {
			$result = false;
		}

		return $result;
	}

	/**
	 * モバイルフッター用CSS
	 *
	 * @param string $css CSS.
	 *
	 * @return string
	 */
	public function add_footer_mobile_nav_css( $css ) {

		if ( ! self::show_footer_mobile_nav() ) {
			return $css;
		}

		$css .= '';

		return $css;
	}

	/**
	 * モバイルフッターナビゲーションの表示
	 */
	public function footer_mobile_nav() {
		if ( ! self::show_footer_mobile_nav() ) {
			return;
		}
		Template::get_template_part( 'template-parts/footer/footer-mobile-nav' );
	}

	/**
	 * モバイルフッターメニュー色
	 *
	 * @param array $css_vars CSS.
	 *
	 * @return array
	 */
	public function add_css_var_mobile_footer_menu( $css_vars ) {

		// モバイルフッター背景色
		$bg_color = Option::get_option( 'ys_color_mobile_footer_bg', '' );
		if ( $bg_color ) {
			$bg_color = CSS::hex_2_rgb( $bg_color );
			$bg       = Enqueue_Utility::get_css_var(
				'mobile-footer--background',
				sprintf(
					'rgb(%s,%s,%s,0.95)',
					$bg_color[0],
					$bg_color[1],
					$bg_color[2]
				)
			);
			$css_vars = array_merge( $css_vars, $bg );
		}

		// 文字色.
		$text_color = Option::get_option( 'ys_color_mobile_footer_text', '' );
		if ( $text_color ) {
			$color    = Enqueue_Utility::get_css_var( 'mobile-footer--text-color', $text_color );
			$css_vars = array_merge( $css_vars, $color );
		}

		return $css_vars;
	}
}

new Footer_Mobile_Nav();
