<?php
	if ( has_nav_menu( 'global' ) ):
		if ( ys_is_amp() ): ?>
		<button class="global-nav__btn" on='tap:sidebar.toggle'>
			<span class="top"></span>
			<span class="middle"></span>
			<span class="bottom"></span>
		</button>
<?php else: ?>
	<input type="checkbox" id="header__nav-toggle" class="header__nav-toggle" hidden />
	<label  class="global-nav__btn" for="header__nav-toggle">
		<span class="top"></span>
		<span class="middle"></span>
		<span class="bottom"></span>
	</label>
	<label class="global-nav__cover" for="header__nav-toggle"></label>
	<nav id="global-nav" class="global-nav color__nav-bg--sp color__nav-bg--pc">
		<?php
			wp_nav_menu( array(
				'theme_location' => 'global',
				'menu_class'     => 'global-nav__menu row row--align-center list-style--none',
				'menu_id'        => 'global-nav__menu',
				'container'      => false,
				'depth'          => 2,
				'fallback_cb'    => '',
				'walker'         => new YS_Walker_Global_Nav_Menu
			 ) );
		?>
	</nav><!-- .global-nav -->
<?php
		endif;
	endif; ?>