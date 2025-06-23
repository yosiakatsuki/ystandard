<?php
/**
 * 固定ページテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

get_header(); ?>
<div class="content-container">
	<?php
	while ( have_posts() ) :
		the_post();
		get_template_part( 'template-parts/page/content' );
	endwhile;

	get_sidebar();
	?>
</div>
<?php get_footer(); ?>
