<?php
/**
 * 最適化
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

/**
 * Class Optimisation
 *
 * @package ystandard
 */
class Optimisation {

	public function __construct() {
		if ( ! ys_is_active_emoji() ) {
			add_action( 'after_setup_theme', [ $this, 'remove_emoji' ] );
		}
		if ( ! ys_is_active_oembed() ) {
			add_action( 'after_setup_theme', [ $this, 'remove_oembed' ] );
		}
	}

	/**
	 * 絵文字の削除
	 */
	public function remove_emoji() {
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		add_filter( 'emoji_svg_url', '__return_false' );
	}

	/**
	 * Embed無効化
	 */
	public function remove_oembed() {
		add_filter( 'embed_oembed_discover', '__return_false' );
		remove_action( 'wp_head', 'rest_output_link_wp_head' );
		remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
		remove_action( 'wp_head', 'wp_oembed_add_host_js' );
	}
}
