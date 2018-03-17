<?php
/**
 * 検索フォームテンプレート
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<input type="search" class="search-field color__font-sub" placeholder="<?php _e( 'Search' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
	</label>
	<button type="submit" class="search-submit color__font-sub"><i class="fa fa-search" aria-hidden="true"></i></button>
</form>