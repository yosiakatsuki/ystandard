<?php
/**
 * 404ページテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

ys_get_header(); ?>
<div class="container">
	<div class="content__wrap">
		<div class="flex flex--row">
			<?php
			get_template_part( 'template-parts/404/content' );

			get_sidebar();
			?>
		</div>
	</div>
</div><!-- .container -->
<?php get_footer(); ?>
