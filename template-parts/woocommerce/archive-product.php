<?php
/**
 * 記事一覧テンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );
?>
<div class="container">
	<div class="content__wrap">
		<?php if ( have_posts() ) : ?>
			<main id="main" class="archive__main site-main">
				<?php
				do_action( 'woocommerce_before_main_content' );
				do_action( 'ys_site_main_prepend' );
				?>
				<header class="woocommerce-products-header archive__header">
					<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
						<h1 class="woocommerce-products-header__title page-title archive__page-title"><?php woocommerce_page_title(); ?></h1>
					<?php endif; ?>
					<?php do_action( 'woocommerce_archive_description' ); ?>
				</header>
				<?php
				if ( woocommerce_product_loop() ) {
					do_action( 'woocommerce_before_shop_loop' );

					woocommerce_product_loop_start();

					if ( wc_get_loop_prop( 'total' ) ) {
						while ( have_posts() ) {
							the_post();
							do_action( 'woocommerce_shop_loop' );

							wc_get_template_part( 'content', 'product' );
						}
					}

					woocommerce_product_loop_end();
					do_action( 'woocommerce_after_shop_loop' );
				} else {
					do_action( 'woocommerce_no_products_found' );
				}
				do_action( 'woocommerce_after_main_content' );
				?>
			</main>
		<?php endif; ?>
		<?php do_action( 'woocommerce_sidebar' ); ?>
	</div>
</div><!-- .container -->
<?php get_footer( 'shop' ); ?>
