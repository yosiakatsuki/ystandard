<?php
/**
 * カスタムロゴ関連
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */
namespace ystandard\utils;

defined( 'ABSPATH' ) || die();

/**
 * Class Logo
 *
 * @package ystandard
 */
class Logo {
	/**
	 * カスタムロゴIDを取得
	 *
	 * @param int $blog_id Optional. ID of the blog in question. Default is the ID of the current blog.
	 *
	 * @return int
	 */
	public static function get_custom_logo_id( $blog_id = 0 ) {
		$switched_blog = false;

		if ( is_multisite() && ! empty( $blog_id ) && get_current_blog_id() !== (int) $blog_id ) {
			switch_to_blog( $blog_id );
			$switched_blog = true;
		}

		$custom_logo_id = get_theme_mod( 'custom_logo' );

		if ( $switched_blog ) {
			restore_current_blog();
		}

		return $custom_logo_id;
	}
}
