<?php
/**
 * 記事投稿者テンプレート
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 非表示に設定されていれば出力しない
 */
if ( ! ys_is_display_author_data() ) {
	return;
}
?>
<div class="author-small flex flex--a-center">
	<figure class="author-small__figure">
		<?php ys_the_author_avatar( false, 24 ); ?>
	</figure>
	<p class="author-small__name"><?php ys_the_author_name(); ?></p>
</div><!-- .entry-list__author -->