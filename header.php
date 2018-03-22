<!DOCTYPE html>
<?php
/**
 * ヘッダーテンプレート
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * *********************************************************************************
 *
 *     Head内にタグを追記したいあなたへ
 *
 *     yStandardではAMPフォーマット対応の為にheadタグをheader.php以外のファイルに書いています
 *     もし、Google Fontsや広告など、何かタグを追加しようとこのファイルをひらいたのであれば、
 *     yStandardでは user-custom-head.php というファイルに追加したいタグを書き込むだけで
 *     headに出力出来るようになっています。
 *
 *     https://wp-ystandard.com/ で配布している子テーマでは上書き用のファイルが含まれていますので
 *     子テーマの user-custom-head.php を編集して下さい。
 *
 *     自分で追加した部分も見やすくなりますのでぜひご活用下さい。
 *
 * *********************************************************************************
 */
if ( ys_is_amp() ) {
	/**
	 * AMPフォーマットの場合
	 */
	get_template_part( 'template-parts/amp/amp-head' );

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
		<?php do_action( 'ys_site_header_prepend' ); ?>
		<div class="header__container container">
			<div class="<?php ys_the_header_type_class(); ?>">
				<div class="site-branding header__branding">
					<?php
					/**
					 * ヘッダーロゴ
					 */
					$logo  = ys_get_header_logo();
					$class = 'site-title header__title color__site-title';
					if ( ! is_singular() || is_front_page() ) {
						printf( '<h1 class="%s clear-headline">%s</h1>', $class, $logo );
					} else {
						printf( '<div class="%s clear-headline">%s</div>', $class, $logo );
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
			</div><!-- .header_row -->
		</div><!-- .header__container -->
		<?php do_action( 'ys_site_header_append' ); ?>
	</header><!-- .header .site-header -->
	<?php do_action( 'ys_after_site_header' ); ?>
	<?php
	/**
	 * ヒーローエリア
	 */
	get_template_part( 'template-parts/hero/hero', ys_get_hero_template() );
	?>
	<div id="content" class="site-content site__content">
	<?php
	/**
	 * パンくずリスト
	 */
	get_template_part( 'template-parts/breadcrumbs/breadcrumbs' );