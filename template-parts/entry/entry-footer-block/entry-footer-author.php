<?php
/**
 * 記事下投稿者プロフィール
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 投稿者表示
 */
if ( ys_is_display_author_data() ) : ?>
<aside class="entry__footer-author author--2col">
	<h2 class="entry__footer-title">この記事を書いた人</h2>
	<?php get_template_part( 'template-parts/author/profile-box' ); ?>
</aside><!-- .entry__footer-author -->
<?php endif; ?>