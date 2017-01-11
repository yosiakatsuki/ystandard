<!DOCTYPE html>
<?php
	// html~headタグは関数で出力
	ys_template_the_head_tag();
?>

<body <?php body_class(); ?>>
<?php
 	if(ys_is_amp()) {
		// Google Analytics
		ys_extras_add_googleanarytics();
	}
?>
<div id="page" class="site">
	<div class="site-inner">

		<header id="masthead" class="site-header color-site-header" role="banner" itemscope id="site-header" itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
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
		?>

		<div id="content" class="site-content wrap clearfix">