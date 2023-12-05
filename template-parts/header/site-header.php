<?php
/**
 * サイトヘッダーテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

defined( 'ABSPATH' ) || die();
?>
<header id="masthead" class="site-header">
	<?php do_action( 'ys_site_header_prepend' ); ?>
	<div class="site-header__content">
		<?php
		// サイトタイトル・ロゴ.
		ys_get_template_part( 'template-parts/header/header-logo' );
		// グローバルナビゲーション.
		ys_get_template_part( 'template-parts/header/global-nav' );
		?>
	</div>
	<?php do_action( 'ys_site_header_append' ); ?>
</header>
