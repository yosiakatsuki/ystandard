<?php
/**
 * 記事投稿者テンプレート
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

?>
<div class="entry__author">
	<figure class="entry__author-figure">
		<?php ys_the_author_avatar( false, 24 ); ?>
	</figure>
	<p class="entry__author-name color__font-sub"><?php ys_the_author_name(); ?></p>
</div><!-- .entry-list__author -->