<?php
/**
 * Template Name:投稿ヘッダーなし 1カラム
 * Template Post Type:post,page
 * Description:アイキャッチ画像・タイトル・パンくずなどがないテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

defined( 'ABSPATH' ) || die();

get_header(); ?>
<div class="container">
	<?php
	while ( have_posts() ) :
		the_post();
		if ( is_page() ) {
			ys_get_template_part( 'template-parts/page/content' );
		} else {
			ys_get_template_part( 'template-parts/single/content' );
		}
	endwhile;
	?>
</div>
<?php get_footer(); ?>
