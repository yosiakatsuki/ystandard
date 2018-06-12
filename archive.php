<?php
/**
 * 記事一覧テンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

get_header(); ?>
<div class="container">
	<div class="content-area content__wrap">
		<main id="main" class="site-main content__main archive__main">
			<?php do_action( 'ys_site_main_prepend' ); ?>
			<?php if ( have_posts() ) : ?>
				<?php
				/**
				 * アーカイブヘッダーの読み込み
				 */
				get_template_part( 'template-parts/archive/archive-header', ys_get_archive_header_template() );
				?>
				<div class="archive__list">
					<?php
					$num = 1;
					while ( have_posts() ) :
						the_post();
						get_template_part(
							'template-parts/content/archive',
							ys_get_archive_template_type()
						);
						/**
						 * インフィード広告表示
						 */
						ys_get_template_ad_infeed( $num, ys_get_archive_template_type() );
						$num ++;
					endwhile;
					?>
				</div><!-- .archive__list -->
				<?php
				/**
				 * ページネーション
				 */
				get_template_part( 'template-parts/pagination/pagination' );
				?>
			<?php else : ?>
				<?php get_template_part( 'template-parts/content/none' ); ?>
			<?php endif; ?>
			<?php do_action( 'ys_site_main_append' ); ?>
		</main><!-- .site-main -->
		<?php get_sidebar(); ?>
	</div><!-- .content-area -->
</div><!-- .container -->
<?php get_footer(); ?>
