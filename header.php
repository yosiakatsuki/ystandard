<!DOCTYPE html>
<?php
	// html~headタグは関数で出力
	ys_htmlhead();
?>

<body <?php body_class(); ?>>
<div id="page" class="site">
	<div class="site-inner">

		<header id="masthead" class="site-header" role="banner">
			<div class="site-header-main">

				<div class="site-header-wrap wrap clearfix">
					<div class="site-branding">

						<?php
							$blog_title_html = '';
							if(has_custom_logo()) {
								$blog_title_html = get_custom_logo();
							} else {
								$blog_title_html = '<a href="'.esc_url( home_url( '/' ) ).'" rel="home">'.get_bloginfo( 'name' ).'</a>';
							}
						 ?>

						<?php if ( !is_singular() ) : ?>
							<h1 class="site-title"><?php echo $blog_title_html; ?></h1>
						<?php else : ?>
							<p class="site-title"><?php echo $blog_title_html; ?></p>
						<?php endif;

						$description = get_bloginfo( 'description', 'display' );
						if ( $description || is_customize_preview() ) : ?>
							<p class="site-description"><?php echo $description; ?></p>
						<?php endif; ?>
					</div><!-- .site-branding -->

					<?php if ( has_nav_menu( 'gloval' )) : ?>
						<div id="site-header-menu-toggle" class="site-header-menu-toggle">
							<input type="checkbox" id="menu-toggle" class="menu-toggle">
							<label class="menu-toggle-label" for="menu-toggle"><span class="menu-toggle-icon"></span></label>
						</div>

						<div id="site-header-menu" class="site-header-menu">
						<?php if ( has_nav_menu( 'gloval') ) : ?>
								<nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'twentysixteen' ); ?>">
									<?php
										wp_nav_menu( array(
											'theme_location' => 'gloval',
											'menu_class'		 => 'gloval-menu',
											'depth'          => 2
										 ) );
									?>
								</nav><!-- .main-navigation -->
							<?php endif; ?>
						</div><!-- .site-header-menu -->
					<?php endif; ?>
				</div><!-- .wrap -->
			</div><!-- .site-header-main -->

		</header><!-- .site-header -->

		<div id="content" class="site-content wrap">