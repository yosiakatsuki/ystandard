<?php
/**
 * Header 関連
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

use customizer\Customizer;
use utils\Style_Sheet;

defined( 'ABSPATH' ) || die();

/**
 * Class Header
 *
 * @package ystandard
 */
class Header {

	/**
	 * Header constructor.
	 */
	public function __construct() {
		add_filter( Enqueue_Utility::FILTER_INLINE_CSS, [ $this, 'add_inline_css' ] );
		add_filter( Enqueue_Utility::FILTER_CSS_VARS, [ $this, 'add_css_var' ] );
		add_action( 'wp_footer', [ $this, 'amp_mobile_nav' ] );
	}


	/**
	 * ヘッダー検索フォームを表示するか
	 *
	 * @return bool
	 */
	public static function is_active_header_search_form() {

		if ( AMP::is_amp() ) {
			return false;
		}

		return Option::get_option_by_bool( 'ys_show_header_search_form', true );
	}

	/**
	 * 固定ヘッダーか
	 *
	 * @return bool
	 */
	public static function is_header_fixed() {
		return Option::get_option_by_bool( 'ys_header_fixed', false );
	}

	/**
	 * ヘッダータイプ
	 *
	 * @return string
	 */
	public static function get_header_type() {
		return Option::get_option( 'ys_design_header_type', 'row1' );
	}

	/**
	 * ヘッダーボックスシャドウ取得
	 *
	 * @return mixed|string
	 */
	private function get_header_shadow() {
		$option = Option::get_option( 'ys_header_box_shadow', 'none' );
		$props  = [
			'none'  => 'none',
			'small' => '0 0 4px rgba(0,0,0,0.1)',
			'large' => '0 0 12px rgba(0,0,0,0.1)',
		];
		if ( ! isset( $props[ $option ] ) ) {
			return 'none';
		}

		return $props[ $option ];
	}

	/**
	 * フッターメイン
	 *
	 * @param array $css_vars CSS.
	 *
	 * @return array
	 */
	public function add_css_var( $css_vars ) {
		$site_cover    = Enqueue_Utility::get_css_var(
			'site-cover',
			Option::get_option( 'ys_color_header_bg', '#ffffff' )
		);
		$header_bg     = Enqueue_Utility::get_css_var(
			'header-bg',
			Option::get_option( 'ys_color_header_bg', '#ffffff' )
		);
		$header_color  = Enqueue_Utility::get_css_var(
			'header-text',
			Option::get_option( 'ys_color_header_font', '#222222' )
		);
		$header_dscr   = Enqueue_Utility::get_css_var(
			'header-dscr',
			Option::get_option( 'ys_color_header_dscr_font', '#656565' )
		);
		$header_shadow = Enqueue_Utility::get_css_var(
			'header-shadow',
			$this->get_header_shadow()
		);

		return array_merge(
			$css_vars,
			$site_cover,
			$header_bg,
			$header_color,
			$header_dscr,
			$header_shadow,
			$this->get_fixed_sidebar_pos()
		);
	}

	/**
	 * 固定サイドバーの位置作成
	 *
	 * @return array
	 */
	private function get_fixed_sidebar_pos() {

		$sidebar_top = Option::get_option_by_int( 'ys_header_fixed_height_pc', 0 );
		$sidebar_top = 0 < $sidebar_top ? ( $sidebar_top + 50 ) . 'px' : '2em';
		if ( ! Option::get_option_by_bool( 'ys_header_fixed', false ) ) {
			$sidebar_top = '2em';
		}

		return Enqueue_Utility::get_css_var(
			'fixed-sidebar-top',
			$sidebar_top
		);
	}

	/**
	 * インラインCSS
	 *
	 * @param string $css CSS.
	 *
	 * @return string
	 */
	public function add_inline_css( $css ) {

		$css .= $this->get_logo_css();
		$css .= $this->get_header_shadow_css();
		$css .= self::get_fixed_header_css();
		$css .= self::get_header_height_css();

		return $css;
	}

	/**
	 * ロゴ関連のCSS取得
	 *
	 * @return string
	 */
	private function get_logo_css() {
		$css = '';
		/**
		 * ロゴ画像の幅設定
		 */
		if ( 0 < Option::get_option_by_int( 'ys_logo_width_sp', 0 ) ) {
			$css .= sprintf(
				'.site-title img{width:%spx;}',
				Option::get_option_by_int( 'ys_logo_width_sp', 0 )
			);
		}
		if ( 0 < Option::get_option_by_int( 'ys_logo_width_pc', 0 ) ) {
			$css .= Style_Sheet::add_media_query(
				sprintf(
					'.site-title img{width:%spx;}',
					Option::get_option_by_int( 'ys_logo_width_pc', 0 )
				),
				'sm'
			);
		}

		return $css;
	}

	/**
	 * 影ありヘッダー用CSS
	 */
	private function get_header_shadow_css() {
		if ( 'none' === $this->get_header_shadow() || Option::get_option_by_bool( 'ys_header_fixed', false ) ) {
			return '';
		}

		return '.site-header {z-index:var(--z-index-header)}';
	}

	/**
	 * ヘッダー高さ指定.
	 *
	 * @return string
	 */
	public static function get_header_height_css() {
		$css    = '';
		$pc     = Option::get_option_by_int( 'ys_header_fixed_height_pc', 0 );
		$tablet = Option::get_option_by_int( 'ys_header_fixed_height_tablet', 0 );
		$mobile = Option::get_option_by_int( 'ys_header_fixed_height_mobile', 0 );
		if ( 0 < $pc || 0 < $tablet || 0 < $mobile ) {
			$css = '.site-header {
				height:var(--ys-site-header-height,auto);
			}';
		}
		if ( 0 < $mobile ) {
			$css .= Style_Sheet::add_media_query(
				":root {
					--ys-site-header-height:{$mobile}px;
				}",
				'',
				'sm'
			);
		}
		if ( 0 < $tablet ) {
			$css .= Style_Sheet::add_media_query(
				":root {
					--ys-site-header-height:{$tablet}px;
				}",
				'sm'
			);
		}
		if ( 0 < $pc ) {
			$css .= Style_Sheet::add_media_query(
				":root {
					--ys-site-header-height:{$pc}px;
				}",
				'md'
			);
		}

		return $css;
	}

	/**
	 * 固定ヘッダー用CSS
	 */
	public static function get_fixed_header_css() {
		if ( ! Option::get_option_by_bool( 'ys_header_fixed', false ) ) {
			return '';
		}
		$css    = '
		.has-fixed-header .site-header {
			position: fixed;
			top:0;
			left:0;
			width:100%;
			z-index:var(--z-index-header);
		}';
		$pc     = Option::get_option_by_int( 'ys_header_fixed_height_pc', 0 );
		$tablet = Option::get_option_by_int( 'ys_header_fixed_height_tablet', 0 );
		$mobile = Option::get_option_by_int( 'ys_header_fixed_height_mobile', 0 );
		if ( 0 < $pc || 0 < $tablet || 0 < $mobile ) {
			$css .= 'body.has-fixed-header {
				padding-top:var(--ys-site-header-height,0);
			}';
		}

		return $css;
	}



}

new Header();
