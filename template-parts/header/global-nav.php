<?php
/**
 * グローバルナビゲーションテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

if ( ! ys_has_global_nav() ) {
	return;
}
?>
<?php ys_global_nav_toggle_button(); ?>
<div class="<?php ys_global_nav_class( 'global-nav' ); ?>">
	<?php do_action( 'ys_global_nav_prepend' ); ?>
	<nav class="global-nav__container">
		<?php ys_get_template_part( 'template-parts/header/global-nav-search-form' ); ?>
		<?php
		do_action( 'ys_before_global_nav_menu' );
		wp_nav_menu(
			[
				'theme_location' => 'global',
				'menu_class'     => 'global-nav__menu',
				'menu_id'        => 'global-nav__menu',
				'container'      => false,
				'depth'          => 2,
				'fallback_cb'    => '',
				'walker'         => ys_global_nav_walker(),
			]
		);
		do_action( 'ys_after_global_nav_menu' );
		?>
	</nav>
	<?php do_action( 'ys_global_nav_before_search' ); ?>
	<?php if ( ys_is_active_header_search_form() ) : ?>
		<button id="global-nav__search-button" class="global-nav__search-button">
			<?php echo ys_get_icon( 'search' ); ?>
		</button>
	<?php endif; ?>
	<?php do_action( 'ys_global_nav_append' ); ?>
</div>

