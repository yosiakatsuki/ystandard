<?php
/**
 * ヘッダーロゴ
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

?>
<div class="site-branding header__branding">
	<?php
	/**
	 * ヘッダーロゴ
	 */
	$logo  = ys_get_header_logo();
	$class = 'site-title header__title';
	if ( is_front_page() || is_404() ) {
		printf( '<h1 class="%s clear-h">%s</h1>', $class, $logo );
	} else {
		printf( '<div class="%s clear-h">%s</div>', $class, $logo );
	}
	/**
	 * 概要
	 */
	ys_the_blog_description();
	?>
</div><!-- .site-branding -->
