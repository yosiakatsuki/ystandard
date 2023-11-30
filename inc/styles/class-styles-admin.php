<?php
/**
 * 管理画面用CSS
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

/**
 * Class Styles_Admin
 *
 * @package ystandard
 */
class Styles_Admin {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_styles' ] );
	}

	/**
	 * 管理画面CSSの読み込み
	 *
	 * @param string $hook_suffix suffix.
	 *
	 * @return void
	 */
	public function admin_enqueue_styles( $hook_suffix ) {
		wp_enqueue_style( 'wp-block-library' );

		// Google Fonts.
		wp_enqueue_style(
			'ys-admin-style-google-fonts',
			'https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap'
		);

		// 管理画面用CSS.
		$handle = 'ys-admin-style';
		$path   = '/assets/css/ystandard-admin.css';
		wp_enqueue_style(
			$handle,
			get_template_directory_uri() . $path,
			[],
			filemtime( get_template_directory() . $path )
		);
	}
}

new Styles_Admin();
