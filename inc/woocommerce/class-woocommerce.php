<?php
/**
 * WooCommerce
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

/**
 * Class WooCommerce
 *
 * @package ystandard
 */
class WooCommerce {

	/**
	 * Store the shop page ID.
	 *
	 * @var integer
	 */
	private static $shop_page_id = 0;

	/**
	 * WooCommerce constructor.
	 */
	public function __construct() {
		$support = apply_filters( 'ys_woocommerce_support', class_exists( '\woocommerce' ) );
		if ( ! $support ) {
			return;
		}
		add_action( 'after_setup_theme', [ __CLASS__, 'setup' ] );

		add_action( 'template_redirect', [ __CLASS__, 'init' ] );
		add_action( 'template_redirect', [ __CLASS__, 'remove_action' ] );

		add_filter( Enqueue_Utility::FILTER_INLINE_CSS, [ __CLASS__, 'enqueue_inline_css' ] );
		/**
		 * Content
		 */
		add_action( 'woocommerce_before_main_content', [ __CLASS__, 'wrapper_html_start' ], 5 );
		add_action( 'woocommerce_before_main_content', [ __CLASS__, 'main_html_start' ] );
		add_action( 'woocommerce_after_main_content', [ __CLASS__, 'main_html_end' ] );
		add_action( 'woocommerce_after_main_content', [ __CLASS__, 'get_sidebar' ] );
		add_action( 'woocommerce_after_main_content', [ __CLASS__, 'wrapper_html_end' ], 15 );
	}


	/**
	 * Setup
	 */
	public static function setup() {
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
	}

	/**
	 * Init
	 */
	public static function init() {
		self::$shop_page_id = wc_get_page_id( 'shop' );

		if ( 0 < self::$shop_page_id ) {
			if ( is_product() ) {
				remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
				add_action( 'woocommerce_single_product_summary', [ __CLASS__, 'single_product_title' ], 5 );
			}
		}
	}

	/**
	 * アクションフック削除
	 */
	public static function remove_action() {
		remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
		remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper' );
		remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end' );
		remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar' );
	}

	/**
	 * WooCommerce用調整CSS
	 *
	 * @param string $css CSS.
	 *
	 * @return string
	 */
	public static function enqueue_inline_css( $css ) {
		$css .= '.woocommerce-products-header .woocommerce-products-header__title {margin-top:0;}';

		return $css;
	}

	/**
	 * 商品タイトル
	 */
	public static function single_product_title() {
		the_title( '<h1 class="product_title">', '</h1>' );
	}

	/**
	 * WooCommerceページ用サイドバー
	 */
	public static function get_sidebar() {
		if ( function_exists( 'woocommerce_get_sidebar' ) ) {
			woocommerce_get_sidebar();

			return;
		}
		get_sidebar( 'shop' );
	}

	/**
	 * コンテンツ ラッパー Start
	 */
	public static function wrapper_html_start() {
		echo apply_filters( 'ys_woo_wrapper_html_start', '<div class="container"><div class="content__wrap">' );
	}

	/**
	 * コンテンツ メイン Start
	 */
	public static function main_html_start() {
		echo apply_filters( 'ys_woo_main_html_start', '<main id="main" class="content__main site-main">' );
	}

	/**
	 * コンテンツ メイン End
	 */
	public static function main_html_end() {
		echo apply_filters( 'ys_woo_main_html_end', '</main>' );
	}

	/**
	 * コンテンツ ラッパー End
	 */
	public static function wrapper_html_end() {
		echo apply_filters( 'ys_woo_wrapper_html_end', '</div></div>' );
	}
}

new WooCommerce();
