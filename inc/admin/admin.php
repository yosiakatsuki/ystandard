<?php
/**
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */
/**
 * 管理者向け機能
 */
/**
 * セルフピンバック対策
 */
if( ! function_exists( 'ys_no_self_ping' ) ) {
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