<section class="no-results not-found">
	<header class="entry-header">
		<?php if ( is_search() ) : ?>
			<h2 class="entry-title">「<?php echo esc_html( get_search_query( false ) ); ?>」の検索結果が見つかりませんでした。</h2>
		<?php else : ?>
			<h2 class="entry-title">お探しのページは見つかりませんでした</h2>
		<?php endif; ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<h2>サイト内を検索する</h2>
		<?php get_search_form(); ?>


	</div><!-- .entry-content -->
</section><!-- .no-results -->