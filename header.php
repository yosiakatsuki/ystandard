<?php
/**
 * ヘッダーテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

?><!DOCTYPE html>
<html <?php ys_the_html_attr(); ?>>
<head <?php ys_the_head_attr(); ?>>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="format-detection" content="telephone=no"/>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php do_action( 'ys_body_prepend' ); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'ystandard' ); ?></a>
	<header id="masthead" class="header site-header">
		<?php do_action( 'ys_site_header_prepend' ); ?>
		<div class="site-header__container container">
			<div class="<?php ys_the_header_row_class(); ?>">
				<?php
				/**
				 * サイトタイトル・ロゴの出力
				 */
				get_template_part( 'template-parts/header/header-logo' );
				?>

				<?php
				/**
				 * グローバルナビゲーション
				 */
				get_template_part( 'template-parts/header/global-nav' );
				?>
			</div><!-- .header_row -->
		</div><!-- .header__container -->
		<?php do_action( 'ys_site_header_append' ); ?>
	</header><!-- .header .site-header -->
	<?php do_action( 'ys_after_site_header' ); ?>
	<?php
	/**
	 * カスタムヘッダー
	 */
	get_template_part( 'template-parts/header/custom-header' );
	/**
	 * パンくずリスト
	 */
	get_template_part( 'template-parts/parts/breadcrumbs' );
	?>
	<div id="content" class="site-content site__content">
	