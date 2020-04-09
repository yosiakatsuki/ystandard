<?php
/**
 * 記事先頭部分テンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 日付と著者情報
 */
if ( ys_is_active_publish_date()  ) :
	?>
	<div class="singular-header-meta">
	</div><!-- .entry-meta -->
	<?php
endif;
