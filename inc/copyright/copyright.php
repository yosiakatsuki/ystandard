<?php
/**
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */
/**
 * フッター copyright
 */
if( ! function_exists( 'ys_get_footer_site_info' ) ) {
	function ys_get_footer_site_info() {
		$copy = ys_get_copyright();
		$poweredby = ys_get_poweredby();
		return $copy . $poweredby;
	}
}
function ys_the_footer_site_info() {
	echo ys_get_footer_site_info();
}
/**
 * copyright
 */
if( ! function_exists( 'ys_get_copyright' ) ) {
	function ys_get_copyright() {
		$year = ys_get_option( 'ys_copyright_year' );
		if( '' == $year ) {
			$year = date_i18n( 'Y' );
		}
		$url = esc_url( home_url( '/' ) );
		$blog_name = get_bloginfo( 'name' );
		$copy = sprintf(
							'Copyright &copy; %s <a href="%s" rel="home">%s</a> All Rights Reserved.',
							$year,
							$url,
							$blog_name
						);
		$copy = apply_filters( 'ys_copyright', $copy );
		return sprintf(
							'<p id="footer-copy" class="footer__copy">%s</p>',
							$copy
						);
	}
}
/**
 * poweredby
 */
if( ! function_exists( 'ys_get_poweredby' ) ) {
	function ys_get_poweredby() {
		$theme = '<a href="https://wp-ystandard.com" target="_blank" rel="nofollow">yStandard Theme</a> by <a href="https://yosiakatsuki.net/blog/" target="_blank" rel="nofollow">yosiakatsuki</a> ';
		$theme = apply_filters( 'ys_poweredby_theme', $theme );
		$url = __( 'https://wordpress.org/' );
		$powerdby = sprintf(
									'Powered by <a href="%s" target="_blank" rel="nofollow">WordPress</a>',
									$url
								);
		$html = sprintf(
							'<p id="footer-poweredby" class="footer__poweredby">%s%s</p>',
							$theme,
							$powerdby
						);
		return apply_filters( 'ys_poweredby', $html );
	}
}