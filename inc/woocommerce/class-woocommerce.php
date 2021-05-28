<?php
/**
 * WooCommerce
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || exit;

/**
 * Class WooCommerce
 *
 * @package ystandard
 */
class WooCommerce {

	/**
	 * WooCommerce constructor.
	 */
	public function __construct() {
		$support = apply_filters( 'ys_woocommerce_support', class_exists( '\woocommerce' ) );
		if ( ! $support ) {
			return;
		}
		add_action( 'after_setup_theme', [ $this, 'setup' ] );
		add_filter( 'woocommerce_template_loader_files', [ $this, 'template_loader_files' ] );
		add_action( 'template_redirect', [ __CLASS__, 'remove_action' ] );
	}


	/**
	 * Setup
	 */
	public function setup() {
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
	}

	/**
	 * WooCommerce用テンプレート
	 *
	 * @param array $files Files.
	 *
	 * @return array
	 */
	public function template_loader_files( $files ) {
		return $this->get_template_loader_default_file();
	}

	/**
	 * WooCommerce用テンプレート取得
	 *
	 * @return array
	 */
	private function get_template_loader_default_file() {
		$default_files = [];
		if ( ! function_exists( 'is_product_taxonomy' ) || ! function_exists( 'wc_get_page_id' ) ) {
			return $default_files;
		}

		$woo_template_dir = apply_filters( 'ys_woocommerce_template_dir', 'template-parts/woocommerce/' );

		if ( is_singular( 'product' ) ) {
			$default_files[] = $woo_template_dir . 'single-product.php';
		} elseif ( is_product_taxonomy() ) {
			$object = get_queried_object();

			if ( is_tax( 'product_cat' ) || is_tax( 'product_tag' ) ) {
				$default_files[] = $woo_template_dir . 'taxonomy-' . $object->taxonomy . '.php';
			}
			$default_files[] = $woo_template_dir . 'archive-product.php';
		} elseif ( is_post_type_archive( 'product' ) || is_page( wc_get_page_id( 'shop' ) ) ) {
			$default_files[] = $woo_template_dir . 'archive-product.php';
		}

		return apply_filters( 'ys_woocommerce_template_files', $default_files );
	}

	/**
	 * フック削除
	 */
	public static function remove_action() {
		remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
	}
}

new WooCommerce();
