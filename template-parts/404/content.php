<?php
/**
 * 結果なしテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

?>
<main id="main" class="content__main site-main no-results not-found">
	<?php do_action( 'ys_content_main_prepend' ); ?>
	<div class="no-results__header not-found__header">
		<h2 class="singular__title entry__title no-results__title not-found__title">
			<?php if ( is_search() ) : ?>
				<?php echo esc_html( '"' . get_search_query( false ) . '"' ); ?>の検索結果が見つかりませんでした。
			<?php else : ?>
				お探しのページは見つかりませんでした
			<?php endif; ?>
		</h2>
	</div>

	<div class="no-results__content entry-content">
		<div class="no-results__search">
			<p class="no-results__search-title">サイト内を検索する</p>
			<?php get_search_form(); ?>
		</div>
		<?php do_action( 'ys_no_results_content' ); ?>
	</div>
	<?php do_action( 'ys_content_main_append' ); ?>
</main>
