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
		<div class="header__container container">
			<div class="header--1row">
				<div class="site-branding header__branding">
					<?php
						/**
						 * ヘッダーロゴ
						 */
						$logo = ys_get_header_logo();
						$class = 'site-title header__title color__site-title';
						if ( ! is_singular() || is_front_page() ) {
							printf( '<h1 class="%s">%s</h1>', $class, $logo );
						} else {
							printf( '<p class="%s">%s</p>', $class, $logo );
						}
						/**
						 * 概要
						 */
						ys_the_blog_description();
					 ?>
				</div><!-- .site-branding -->
				<div class="header__nav">
					<?php get_template_part( 'template-parts/nav/global-nav' ); ?>
				</div><!-- .header__nav -->
			</div><!-- .row -->
		</div><!-- .header__container -->
	</header><!-- .header .site-header -->
	<div id="content" class="site-content site__content">
		<?php
		/**
		* パンくず リスト
		*/
		get_template_part( 'template-parts/breadcrumbs/breadcrumbs' );
		?>