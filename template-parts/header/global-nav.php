<?php
/**
 * グローバルナビゲーションテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

if ( ! has_nav_menu( 'global' ) ) {
	return;
}
?>
<?php ys_global_nav_toggle_button(); ?>
<div class="<?php ys_global_nav_class( 'global-nav' ); ?>">
	<?php do_action( 'ys_global_nav_prepend' ); ?>
	<nav class="global-nav__container">
		<?php if ( ys_is_active_header_search_form() ) : ?>
			<div id="global-nav__search" class="global-nav__search">
				<?php get_search_form(); ?>
				<button id="global-nav__search-close" class="global-nav__search-close">
					<?php echo ys_get_icon( 'x' ); ?> 閉じる
				</button>
			</div>
		<?php endif; ?>
		<?php
		wp_nav_menu(
			[
				'theme_location' => 'global',
				'menu_class'     => 'global-nav__menu',
				'menu_id'        => 'global-nav__menu',
				'container'      => false,
				'depth'          => 2,
				'fallback_cb'    => '',
				'walker'         => new YS_Walker_Global_Nav_Menu(),
			]
		);
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

