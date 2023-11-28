<?php
/**
 * ヘッダーテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */

defined( 'ABSPATH' ) || die();

echo '<!DOCTYPE html>';
?>
<html <?php language_attributes(); ?>>
<head <?php ys_the_head_attr(); ?>>
	<?php
	/**
	 * wp_head
	 * Charsetやビューポート関連などのmetaはclass-head.phpで出力しています。
	 *
	 * @see inc/head/class-head.php
	 */
	wp_head();
	?>
</head>
<body <?php body_class(); ?>>
<?php
/**
 * bodyタグの直後に挿入するコンテンツ
 * スクリーンリーダーテキストもこの処理で出力されます。
 */
wp_body_open();
// bodyタグの直後に挿入するコンテンツ
do_action( 'ys_body_prepend' );
/**
 * サイトのヘッダー出力
 *
 * @see template-parts/header/site-header.php
 */
ys_get_template_part( 'template-parts/header/site-header' );
// サイトヘッダーの直後に挿入するコンテンツ
do_action( 'ys_after_site_header' );
?>
<div id="content" class="site-content">
