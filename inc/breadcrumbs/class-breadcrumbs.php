<?php
/**
 * パンくずリスト
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

use ystandard\utils\JSON;
use ystandard\utils\Post_Type;

defined( 'ABSPATH' ) || die();

/**
 * Class Breadcrumbs
 *
 * @package ystandard
 */
class Breadcrumbs {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'wp_footer', [ $this, 'structured_data' ], 11 );
		add_action( 'get_header', [ $this, 'add_breadcrumbs' ] );
		add_action( 'ys_get_css_custom_properties_args', [ $this, 'add_css_vars' ] );
	}

	/**
	 * パンくず用色設定
	 *
	 * @param array $css_vars CSS Vars.
	 *
	 * @return array
	 */
	public function add_css_vars( $css_vars ) {
		$breadcrumb_text = Enqueue_Utility::get_css_var(
			'breadcrumbs-text',
			Option::get_option( 'ys_color_breadcrumbs_text', '#656565' )
		);

		return array_merge(
			$css_vars,
			$breadcrumb_text
		);
	}

	/**
	 * パンくず表示用フィルターのセット
	 */
	public function add_breadcrumbs() {
		$filter   = [
			'header' => 'ys_after_site_header',
			'footer' => 'ys_before_site_footer',
		];
		$position = Option::get_option( 'ys_breadcrumbs_position', 'footer' );
		if ( ! isset( $filter[ $position ] ) ) {
			return;
		}
		add_action( $filter[ $position ], [ $this, 'show_breadcrumbs' ] );
	}

	/**
	 * パンくずリスト表示
	 */
	public function show_breadcrumbs() {
		if ( ! Breadcrumbs_Data::is_show_breadcrumbs() ) {
			return;
		}
		ob_start();
		Template::get_template_part(
			'template-parts/breadcrumbs/breadcrumbs',
			'',
			[ 'breadcrumbs' => Breadcrumbs_Data::get_breadcrumbs() ]
		);
		echo ob_get_clean();
	}

	/**
	 * パンくずリスト構造化データ出力
	 */
	public function structured_data() {
		if ( ! Breadcrumbs_Data::is_show_breadcrumbs() ) {
			return;
		}
		$items = Breadcrumbs_Data::get_breadcrumbs();
		if ( ! is_array( $items ) || empty( $items ) ) {
			return;
		}
		$item_list_element = [];
		$position          = 1;
		foreach ( $items as $item ) {
			if ( isset( $item['item'] ) && ! empty( $item['item'] ) ) {
				$item['position']    = $position;
				$item_list_element[] = $item;
				$position ++;
			}
		}
		$breadcrumbs = [
			'@context'        => 'https://schema.org',
			'@type'           => 'BreadcrumbList',
			'name'            => _x( 'Breadcrumb', 'structured-data', 'ystandard' ),
			'itemListElement' => $item_list_element,
		];

		$breadcrumbs = apply_filters( 'ys_get_json_ld_data', $breadcrumbs, 'breadcrumblist' );

		JSON::the_json_ld( $breadcrumbs );
	}
}

new Breadcrumbs();
