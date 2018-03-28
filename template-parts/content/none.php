<?php
/**
 * 結果なしテンプレート
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

?>
<section class="no-results not-found">
	<header class="page-header entry__header">
		<h2 class="page-title entry__title clear-headline">
			<?php if ( is_search() ) : ?>
				「<?php echo esc_html( get_search_query( false ) ); ?>」の検索結果が見つかりませんでした。
			<?php else : ?>
				お探しのページは見つかりませんでした
			<?php endif; ?>
		</h2>
	</header><!-- .entry-header -->

	<div class="no-results__search">
		<h2 class="no-results__headline">サイト内を検索する</h2>
		<?php get_search_form(); ?>
	</div><!-- .no-results__search -->
</section><!-- .no-results -->