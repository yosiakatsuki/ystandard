<?php
/**
 * グローバルナビゲーション(AMP)
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

defined( 'ABSPATH' ) || die();

if ( ! has_nav_menu( 'global' ) ) {
	return;
}
?>
<amp-sidebar id="mobile-menu" layout="nodisplay" side="right" class="amp-nav">
	<?php ys_global_nav_toggle_button( 'close' ); ?>
	<nav class="global-nav__container">
		<?php
		wp_nav_menu(
			[
				'theme_location' => 'global',
				'menu_class'     => 'global-nav__menu',
				'menu_id'        => 'global-nav__menu-amp',
				'container'      => false,
				'depth'          => 2,
				'fallback_cb'    => '',
				'walker'         => new YS_Walker_Global_Nav_Menu(),
			]
		);
		?>
	</nav>
</amp-sidebar>

