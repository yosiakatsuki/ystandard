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
	<nav id="global-nav__menu" class="global-nav__menu">
		<?php
			wp_nav_menu( array(
				'theme_location' => 'global',
				'menu_class'     => 'row row--align-center',
				'container'      => false,
				'depth'          => 2,
				'fallback_cb'    => ''
			 ) );
		?>
	</nav><!-- .global-nav__menu -->
<?php
		endif;
	endif; ?>