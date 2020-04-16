<?php
/**
 * サイトヘッダーテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */
?>

<header id="masthead" class="site-header">
	<?php do_action( 'ys_site_header_prepend' ); ?>
	<div class="container">
		<div class="site-header__content">
			<?php
			/**
			 * サイトタイトル・ロゴの出力
			 */
			ys_get_template_part( 'template-parts/header/header-logo' );
			/**
			 * グローバルナビゲーション
			 */
			ys_get_template_part( 'template-parts/header/global-nav' );
			?>
		</div>
	</div>
	<?php do_action( 'ys_site_header_append' ); ?>
</header>
