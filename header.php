<!DOCTYPE html>
<?php
	if( ys_is_amp() ) {
		/**
		 * AMPフォーマットの場合
		 */
		get_template_part( 'template-parts/amp/head-amp' );

	} else {
		/**
		 * 通常フォーマットの場合
		 */
		get_template_part( 'template-parts/head/head' );
	}
?>
<!-- head -->
<body <?php body_class(); ?>>
<?php do_action( 'ys_body_prepend' ); ?>
<div id="page" class="site">
	<header id="masthead" class="header site-header color__site-header">
		<div class="header__container wrap">
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
		</div><!-- .header__container -->
	</header><!-- .header .site-header -->
	<?php
		// パンくず
		ys_breadcrumb();
	?>
	<div id="content" class="site-content">