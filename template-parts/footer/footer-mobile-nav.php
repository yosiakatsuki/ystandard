<?php
/**
 * モバイルフッターメニューテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

defined( 'ABSPATH' ) || die();

if ( ! ys_show_footer_mobile_nav() ) {
	return;
}
?>
<nav id="footer-mobile-nav" class="<?php ys_the_mobile_footer_classes(); ?>">
	<div class="footer-mobile-nav-container">
		<?php ys_the_mobile_footer_menu(); ?>
	</div>
</nav>
