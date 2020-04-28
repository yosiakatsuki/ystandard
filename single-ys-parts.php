<?php
/**
 * [ys]パーツ プレビューテンプレート
 * ※プレビュー専用の為、外部ユーザーからは閲覧できません。
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

get_header(); ?>
<div class="container">
	<div class="content__wrap">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<main id="main" class="content__main site-main" style="margin-top: 5em;margin-bottom: 5em;">
				<article id="post-<?php the_ID(); ?>" <?php post_class( [ 'singular-article' ] ); ?>>
					<div class="entry-content">
						<?php the_content(); ?>
					</div>
				</article>
			</main>
		<?php endwhile; ?>
	</div>
</div>
<?php get_footer(); ?>
