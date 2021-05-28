<?php
/**
 * WooCommerce 商品詳細ページテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' ); ?>
<div class="container">
	<div class="content__wrap">
		<main id="main" class="content__main site-main">
			<?php
			do_action( 'woocommerce_before_main_content' );
			while ( have_posts() ) :
				the_post();
				wc_get_template_part( 'content', 'single-product' );
			endwhile;
			do_action( 'woocommerce_after_main_content' );
			?>
		</main>
		<?php do_action( 'woocommerce_sidebar' ); ?>
	</div>
</div><!-- .container -->
<?php get_footer( 'shop' ); ?>
