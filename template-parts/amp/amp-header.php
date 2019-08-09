<?php
/**
 * AMP用 headerテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

?><!DOCTYPE html>
<html <?php ys_the_html_attr(); ?>>
<head>
	<meta charset="utf-8">
	<script async src="https://cdn.ampproject.org/v0.js"></script>
	<meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
	<style amp-boilerplate>body {
			-webkit-animation: -amp-start 8s steps(1, end) 0s 1 normal both;
			-moz-animation: -amp-start 8s steps(1, end) 0s 1 normal both;
			-ms-animation: -amp-start 8s steps(1, end) 0s 1 normal both;
			animation: -amp-start 8s steps(1, end) 0s 1 normal both
		}

		@-webkit-keyframes -amp-start {
			from {
				visibility: hidden
			}
			to {
				visibility: visible
			}
		}

		@-moz-keyframes -amp-start {
			from {
				visibility: hidden
			}
			to {
				visibility: visible
			}
		}

		@-ms-keyframes -amp-start {
			from {
				visibility: hidden
			}
			to {
				visibility: visible
			}
		}

		@-o-keyframes -amp-start {
			from {
				visibility: hidden
			}
			to {
				visibility: visible
			}
		}

		@keyframes -amp-start {
			from {
				visibility: hidden
			}
			to {
				visibility: visible
			}
		}</style>
	<noscript>
		<style amp-boilerplate>body {
				-webkit-animation: none;
				-moz-animation: none;
				-ms-animation: none;
				animation: none
			}</style>
	</noscript>

	<meta name="format-detection" content="telephone=no"/>
	<meta itemscope id="EntityOfPageid" itemprop="mainEntityOfPage" itemType="https://schema.org/WebPage" itemid="<?php echo the_permalink(); ?>"/>

	<script async custom-element="amp-social-share" src="https://cdn.ampproject.org/v0/amp-social-share-0.1.js"></script>
	<script async custom-element="amp-sidebar" src="https://cdn.ampproject.org/v0/amp-sidebar-0.1.js"></script>
	<?php if ( ys_is_enable_google_analytics() ) : ?>
		<script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>
	<?php endif; ?>
	<link rel="stylesheet" href="<?php echo ys_get_font_awesome_css_url(); ?>">
	<?php
	/**
	 * AMP用wp_head的な処理
	 */
	ys_amp_head();
	?>
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