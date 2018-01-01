<?php
/**
 * ピンバックURLの出力
 */
if( ! function_exists( 'ys_the_pingback_url' ) ) {
	function ys_the_pingback_url() {
		if ( is_singular() && pings_open( get_queried_object() ) ){
			echo '<link rel="pingback" href="' . get_bloginfo( 'pingback_url' ) . '">';
		}
	}
}
add_action( 'wp_head', 'ys_the_pingback_url' );