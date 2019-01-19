<?php
/**
 * ヘッダーロゴ
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

?>
<div class="<?php ys_the_header_col_class( array( 'site-branding', 'header__branding' ) ); ?>">
	<?php
	/**
	 * ヘッダーロゴ
	 */
	$logo  = ys_get_header_logo();
	$class = 'site-title header__title';
	if ( ! is_singular() || is_front_page() ) {
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
