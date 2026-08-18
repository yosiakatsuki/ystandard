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
	<div class="header-container">
		<div class="site-header__content">
			<?php
			/**
			 * サイトタイトル・ロゴの出力
			 */
			get_template_part( 'template-parts/header/header-logo' );
			/**
			 * グローバルナビゲーション
			 */
			get_template_part( 'template-parts/navigation/global-nav' );
			?>
		</div>
	</div>
	<?php do_action( 'ys_site_header_append' ); ?>
</header>
