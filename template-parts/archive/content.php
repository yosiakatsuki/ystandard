<?php
/**
 * 記事一覧テンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

?>
<main id="main" class="site-main content__main archive__main flex__col">
	<?php do_action( 'ys_site_main_prepend' ); ?>
	<?php if ( have_posts() ) : ?>
		<?php
		/**
		 * アーカイブヘッダーの読み込み
		 */
		get_template_part( 'template-parts/archive/header' );
		?>
		<div class="archive__list">
			<?php
			$num = 1;
			while ( have_posts() ) :
				the_post();
				get_template_part(
					'template-parts/archive/details',
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