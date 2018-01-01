<!DOCTYPE html>
<?php
	if( ys_is_amp() ) {
		/**
		 * AMPフォーマットの場合
		 */
		get_template_part( '/template-parts/amp/head-amp.php' );
		
	} else {
		/**
		 * 通常フォーマットの場合
		 */
		get_template_part( '/template-parts/head/head.php' );
	}
?>

<body <?php body_class(); ?>><div id="fb-root"></div>
<?php
 	if( ys_is_amp() ) {
		// Google Analytics
		ys_extras_add_googleanarytics();
	}
?>
<div id="page" class="site">
	<div class="site-inner">

		<header id="masthead" class="site-header color-site-header" itemprop="publisher" itemscope itemtype="https://schema.org/Organization" <?php ys_template_the_header_attr(); ?>>
			<div class="site-header-main wrap">

				<div class="site-header-wrap clearfix">
					<div class="site-branding">

						<?php
						// ロゴ
							ys_template_the_header_site_title_logo();
						 ?>
					</div><!-- .site-branding -->

					<?php
					// グローバルメニュー
						ys_template_the_header_global_menu();
					?>
				</div><!-- .wrap -->
			</div><!-- .site-header-main -->

		</header><!-- .site-header -->

		<?php
			// パンくず
			ys_breadcrumb();
			// ヒーローエリア
			ys_template_the_site_hero();
		?>

		<div id="content" class="site-content wrap clearfix" <?php ys_template_the_content_attr(); ?>>