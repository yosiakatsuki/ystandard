<?php
/**
 * フッターサブウィジェットテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

defined( 'ABSPATH' ) || die();

if ( ! ys_get_sub_footer_contents() ) {
	return;
}
?>
<div class="sub-footer">
	<div class="sub-footer-container">
		<div class="sub-footer__content">
			<?php echo ys_get_sub_footer_contents(); ?>
		</div>
	</div>
</div>
