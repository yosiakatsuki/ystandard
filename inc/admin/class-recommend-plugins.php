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
		if ( apply_filters( 'ys_disable_recommend_plugins', false ) ) {
			return;
		}
		require_once get_template_directory() . '/library/TGM-Plugin-Activation/class-tgm-plugin-activation.php';
		add_action( 'tgmpa_register', [ $this, 'tgmpa_register' ] );
		Notice::set_notice( [ $this, 'notice' ] );
		add_filter( 'tgmpa_notice_action_links', [ $this, 'action_links' ] );
	}

	/**
	 * Notice
	 */
	public function notice() {
		global $pagenow, $hook_suffix;
		if ( 'themes.php' === $pagenow && 'appearance_page_tgmpa-install-plugins' === $hook_suffix ) {
			Notice::manual( Admin::manual_link( 'ystd-plugin' ) );
		}
	}

	/**
	 * オススメプラグイン通知メッセージ
	 *
	 * @param array $action_links Action Links.
	 *
	 * @return mixed
	 */
	public function action_links( $action_links ) {
		$action_links['manual'] = Admin::manual_link( 'ystd-plugin', '', true );

		return $action_links;
	}

	/**
	 * TGM Plugin Activation 実行
	 */
	public function tgmpa_register() {
		if ( apply_filters( 'ys_disable_tgmpa_register', false ) ) {
			return;
		}
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
