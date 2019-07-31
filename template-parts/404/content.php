<?php
/**
 * 結果なしテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

?>
<div class="flex__col content__wrap">
	<main id="main" class="site-main content__main no-results not-found">
		<?php do_action( 'ys_content_main_prepend' ); ?>
		<div class="no-results__header not-found__header">
			<h2 class="singular__title entry__title no-results__title not-found__title clear-headline">
				<?php if ( is_search() ) : ?>
					<?php echo esc_html( '"' . get_search_query( false ) . '"' ); ?>の検索結果が見つかりませんでした。
				<?php else : ?>
					お探しのページは見つかりませんでした
				<?php endif; ?>
			</h2>
		</div><!-- .entry-header -->

		<div class="no-results__search">
			<h2 class="no-results__headline">サイト内を検索する</h2>
			<?php get_search_form(); ?>
		</div><!-- .no-results__search -->
		<?php do_action( 'ys_content_main_append' ); ?>
	</main>
</div>