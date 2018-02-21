<?php
/**
 * AMP footer関連
 */
/**
 * AMPフォーマットフッター処理
 */
function ys_amp_footer() {
	do_action( 'ys_amp_footer' );
}

/**
 * フッターで処理する内容を登録
 */
add_action( 'ys_amp_footer', 'ys_the_json_ld' );

function ys_the_amp_slider() {
	?>
	<amp-sidebar id="sidebar" layout="nodisplay" side="right" class="amp-slider color__nav-bg--sp">
		<button class="global-nav__btn global-nav__amp-btn--close" on="tap:sidebar.close">
			<span class="top"></span>
			<span class="middle"></span>
			<span class="bottom"></span>
		</button>
		<nav id="global-nav--amp" class="global-nav--amp color__nav-bg--sp">
			<?php
				wp_nav_menu( array(
					'theme_location' => 'global',
					'menu_class'     => 'global-nav__menu row flex--a-center list-style--none',
					'menu_id'        => 'global-nav__menu',
					'container'      => false,
					'depth'          => 2,
					'walker'         => new YS_Walker_Global_Nav_Menu
				 ) );
			?>
	</nav><!-- .main-navigation -->
</amp-sidebar>
<?php
}
add_action( 'ys_amp_footer', 'ys_the_amp_slider' );