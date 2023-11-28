<?php
/**
 * 固定ページ関連
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */

namespace ystandard\post_type;

defined( 'ABSPATH' ) || die();

/**
 * Class Page
 *
 * @package ystandard\post_type
 */
class Page {

	/**
	 * Page constructor.
	 */
	public function __construct() {
		add_action( 'after_setup_theme', [ $this, 'add_post_type_support' ] );
	}

	/**
	 * 固定ページで機能を有効化
	 *
	 * @return void
	 */
	public function add_post_type_support() {
		add_post_type_support( 'page', 'excerpt' );
	}
}

new Page();
