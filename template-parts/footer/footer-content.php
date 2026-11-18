<?php
/**
 * フッターテンプレートパーツ
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

defined( 'ABSPATH' ) || die();

// 非表示設定や空のパーツによる不要な出力を避ける.
if ( ! ys_is_active_footer_template_part() ) {
	return;
}
?>
<div class="site-footer-contents block-content">
	<?php block_template_part( 'footer' ); ?>
</div>
