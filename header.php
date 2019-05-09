<!DOCTYPE html>
<html <?php ys_the_html_attr(); ?>>
<?php
/**
 * ヘッダーテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
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
	get_template_part( 'template-parts/header/head' );
}
?>
<!-- head -->
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
	?>
	<div id="content" class="site-content site__content">
<?php
/**
 * パンくずリスト
 */
get_template_part( 'template-parts/parts/breadcrumbs' );