<?php
/**
 * 管理者向け機能
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

if ( ! function_exists( 'ys_no_self_ping' ) ) {
	/**
	 * セルフピンバック対策
	 *
	 * @param array $links links.
	 * @return void
	 */
	function ys_no_self_ping( &$links ) {
		$home = get_option( 'home' );
		foreach ( $links as $l => $link ) {
			if ( 0 === strpos( $link, $home ) ) {
				unset( $links[ $l ] );
			}
		}
	}
}
add_action( 'pre_ping', 'ys_no_self_ping' );