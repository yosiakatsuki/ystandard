<?php
/**
 * ヘッダーロゴ
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

?>
<div class="site-branding">
	<?php
	do_action( 'ys_site_branding_prepend' );
	/**
	 * ヘッダーロゴ
	 */
	$logo = ys_get_header_logo();
	if ( is_front_page() || is_home() || is_404() ) {
		printf( '<h1 class="site-title">%s</h1>', $logo );
	} else {
		printf( '<div class="site-title">%s</div>', $logo );
	}
	/**
	 * 概要
	 */
	ys_the_blog_description();
	do_action( 'ys_site_branding_append' );
	?>
</div>
