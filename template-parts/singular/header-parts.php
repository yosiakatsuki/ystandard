<?php
/**
 * 記事先頭部分テンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

if ( ys_is_active_publish_date() || ys_is_display_author_data() ) :
	?>
	<div class="singular-header-meta flex flex--j-between flex--a-center text-sub text--sm">
		<?php get_template_part( 'template-parts/singular/singular-date' ); ?>
		<?php get_template_part( 'template-parts/author/small-author' ); ?>
	</div><!-- .entry-meta -->
	<?php
endif;