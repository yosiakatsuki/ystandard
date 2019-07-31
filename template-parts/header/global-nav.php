<?php
/**
 * グローバルナビゲーションテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

if ( has_nav_menu( 'global' ) ) :
	?>
	<div class="<?php ys_the_header_col_class( 'nav', array( 'h-nav', 'rwd' ) ); ?>">
		<input type="checkbox" id="h-nav__toggle" class="h-nav__toggle" hidden>
		<label class="h-nav__btn" for="h-nav__toggle">
			<span class="hamburger">
				<span class="top"></span>
				<span class="middle"></span>
				<span class="bottom"></span>
			</span>
		</label>
		<nav id="h-nav__main" class="h-nav__main">
			<?php if ( ys_is_active_slide_menu_search_form() ) : ?>
				<div class="h-nav__search">
					<?php get_search_form(); ?>
				</div><!-- .global-nav__search -->
			<?php endif; ?>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'global',
					'menu_class'     => 'h-nav__menu row flex--a-center li-clear',
					'menu_id'        => 'h-nav__menu',
					'container'      => false,
					'depth'          => 2,
					'fallback_cb'    => '',
					'walker'         => new YS_Walker_Global_Nav_Menu(),
				)
			);
			?>
		</nav><!-- .global-nav -->
	</div><!-- .header__nav -->
<?php endif; ?>