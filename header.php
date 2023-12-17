<?php
/**
 * ヘッダーテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */

defined( 'ABSPATH' ) || die();

echo '<!DOCTYPE html>' . PHP_EOL;
?>
<html <?php language_attributes(); ?>>
<head <?php ys_the_head_attr(); ?>>
	<?php
	/**
	 * wp_head
	 * Charsetやビューポート関連などのmetaはclass-head.phpで出力しています。
	 * フックでのカスタマイズが難しい場合、カスタマイズ用テンプレート template-parts/head/head.php を使って子テーマでカスタマイズしてください。
	 *
	 * @see inc/head/class-head.php
	 * @see template-parts/head/head.php
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
// サイトのヘッダー.
ys_get_template_part( 'template-parts/header/site-header' );
// サイトヘッダーの後に挿入するコンテンツ
do_action( 'ys_after_site_header' );
?>
<div id="content" class="site-content">
