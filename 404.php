<?php
/**
 * 404ページテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

get_header(); ?>
<div class="content-container">
	<?php
	ys_get_template_part( 'template-parts/404/content' );
	get_sidebar();
	?>
</div><!-- .container -->
<?php get_footer(); ?>
