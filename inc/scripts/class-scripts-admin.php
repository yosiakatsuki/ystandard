<?php
/**
 * 管理画面関連のJS
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

/**
 * class Scripts_Admin.
 *
 * @package ystandard
 */
class Scripts_Admin {

	/**
	 * JSのハンドル名
	 */
	const HANDLE = 'ys-admin-scripts';

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );
	}

	/**
	 * 管理画面-JavaScriptの読み込み
	 *
	 * @param string $hook_suffix suffix.
	 *
	 * @return void
	 */
	public function admin_enqueue_scripts( $hook_suffix ) {
		// メディアアップローダーの読み込み.
		wp_enqueue_media();
		// 管理画面用のスクリプトの読み込み.
		$path = '/assets/js/ystandard-admin.js';
		wp_enqueue_script(
			self::HANDLE,
			get_template_directory_uri() . $path,
			[ 'jquery' ],
			filemtime( get_template_directory() . $path ),
			[
				'in_footer' => true,
				'strategy'  => 'defer',
			]
		);
	}
}

new Scripts_Admin();
