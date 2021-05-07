<?php
/**
 * 検索フォームテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<input type="search" class="search-field" placeholder="<?php _e( 'Search' ); ?>" value="<?php echo get_search_query(); ?>" name="s" aria-label="<?php _e( 'Search' ); ?>"/>
	<?php do_action( 'ys_search_form' ); ?>
	<button type="submit" class="search-submit" aria-label="search"><?php echo ys_get_icon( 'search' ); ?></button>
</form>
