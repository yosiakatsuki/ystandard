<?php
/**
 * プラグインおすすめ
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

/**
 * Class Recommend_Plugins
 *
 * @package ystandard
 */
class Recommend_Plugins {

	/**
	 * Recommend_Plugins constructor.
	 */
	public function __construct() {
		require_once get_template_directory() . '/library/TGM-Plugin-Activation/class-tgm-plugin-activation.php';
		add_action( 'tgmpa_register', [ $this, 'tgmpa_register' ] );
	}

	/**
	 * TGM Plugin Activation 実行
	 */
	public function tgmpa_register() {
		$plugins = [
			[
				'name'   => 'yStandard Blocks',
				'slug'   => 'ystandard-blocks',
				'source' => 'https://wp-ystandard.com/download/ystandard/plugin/ystandard-blocks/ystandard-blocks.zip',
			],
			[
				'name' => 'WP Multibyte Patch',
				'slug' => 'wp-multibyte-patch',
			],
			[
				'name' => 'Lazy Load - Optimize Images',
				'slug' => 'rocket-lazy-load',
			],
		];
		$config  = [
			'id'           => 'tgmpa',
			'default_path' => '',
			'menu'         => 'tgmpa-install-plugins',
			'parent_slug'  => 'themes.php',
			'capability'   => 'edit_theme_options',
			'has_notices'  => true,
			'dismissable'  => true,
			'dismiss_msg'  => '',
			'is_automatic' => false,
			'message'      => '',
		];

		tgmpa( $plugins, $config );
	}
}

new Recommend_Plugins();
