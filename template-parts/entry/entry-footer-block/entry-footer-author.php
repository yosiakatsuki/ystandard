<?php
	/**
	 * 投稿者表示
	 */
	if( ys_is_active_entry_footer_author() ) : ?>
	<aside class="entry__footer-author">
		<h2 class="entry__footer-author-title">この記事を書いた人</h2>
		<?php get_template_part( 'template-parts/author/profile-box' ); ?>
	</aside><!-- .entry__footer-author -->
<?php endif; ?>