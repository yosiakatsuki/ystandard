<?php
/**
 * フッターサブウィジェットテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

defined( 'ABSPATH' ) || die();

if ( ! ys_get_footer_sub_contents() ) {
	return;
}
?>
<div class="footer-sub">
	<div class="container">
		<div class="footer-sub__content">
			<?php echo ys_get_footer_sub_contents(); ?>
		</div>
	</div>
</div>
