<!DOCTYPE html>
<?php
	// html~headタグは関数で出力
	ys_html_head();
?>

<body <?php body_class(); ?>>
<?php
 	if(ys_is_amp()) {
		// Google Analytics
		ys_wphead_add_googleanarytics();
	}
?>
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
						<?php if(ys_is_amp()): ?>
							<button class="menu-toggle-label" on='tap:sidebar.toggle'>
								<span class="top"></span>
								<span class="middle"></span>
								<span class="bottom"></span>
							</button>
						<?php else: //AMP?>
							<input type="checkbox" id="menu-toggle" class="menu-toggle" hidden />
							<label  class="menu-toggle-label" for="menu-toggle">
								<span class="top"></span>
								<span class="middle"></span>
								<span class="bottom"></span>
							</label>
							<label class="menu-toggle-cover" for="menu-toggle"></label>
							<div id="site-header-menu" class="site-header-menu">
								<nav id="site-navigation" class="main-navigation" role="navigation">
									<?php
										wp_nav_menu( array(
											'theme_location' => 'gloval',
											'menu_class'		 => 'gloval-menu',
											'container_class' => 'menu-global-container',
											'depth'          => 2
										 ) );
									?>
								</nav><!-- .main-navigation -->
							</div><!-- .site-header-menu -->
						<?php endif; //AMP?>
					<?php endif; ?>
				</div><!-- .wrap -->
			</div><!-- .site-header-main -->

		</header><!-- .site-header -->

		<div id="content" class="<?php echo ys_style_content_class(array('site-content','wrap','clearfix')); ?>">