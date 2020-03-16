<?php
/**
 * 404ページテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

get_header(); ?>
<div class="container">
	<div class="content__wrapper">
		<?php
		ys_get_template_part( 'template-parts/404/content' );

		get_sidebar();
		?>
	</div>
</div><!-- .container -->
<?php get_footer(); ?>
