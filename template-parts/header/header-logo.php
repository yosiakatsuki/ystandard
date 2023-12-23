<?php
/**
 * ヘッダーロゴ部分のテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */

defined( 'ABSPATH' ) || die();
?>
<div class="site-branding">
	<?php
	do_action( 'ys_site_branding_prepend' );
	// ヘッダーロゴ.
	ys_the_header_logo();
	// キャッチフレーズ・概要.
	ys_the_blog_description();

	do_action( 'ys_site_branding_append' );
	?>
</div>
