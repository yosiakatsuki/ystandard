<?php
/**
 * コメントテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * TODO:ショートコード化
 */
if ( ! ys_is_amp() && ( comments_open() || get_comments_number() ) ) : ?>
	<div class="post-comments singular-footer__block">
		<?php comments_template(); ?>
	</div>
<?php endif; ?>