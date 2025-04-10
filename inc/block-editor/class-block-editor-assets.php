<?php
/**
 * ブロックエディター 管理画面CSS
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

/**
 * Class Block_Editor_Assets
 *
 * @package ystandard
 */
class Block_Editor_Assets {

	/**
	 * ブロックエディター用インラインCSSフック名
	 */
	const BLOCK_EDITOR_ASSETS_HOOK = 'ys_block_editor_assets_inline_css';

	/**
	 * Block_Editor_Assets constructor.
	 */
	public function __construct() {
		add_action( 'enqueue_block_assets', [ $this, 'enqueue_block_editor_assets' ], 11 );
		add_action( 'after_setup_theme', [ $this, 'enqueue_editor_style' ] );
	}

	/**
	 * ブロックエディタのスタイル追加
	 */
	public function enqueue_block_editor_assets() {
		if ( ! is_admin() ) {
			return;
		}
		$file = '/css/block-editor-assets.css';
		wp_enqueue_style(
			'ys-block-editor-assets',
			get_template_directory_uri() . $file,
			[],
			filemtime( get_template_directory() . $file )
		);
		$custom_property        = Enqueue_Styles::get_css_vars_selector( 'body .editor-styles-wrapper' );
		$custom_property_preset = Enqueue_Styles::get_css_vars_selector_preset( 'body .editor-styles-wrapper' );
		wp_add_inline_style(
			'ys-block-editor-assets',
			apply_filters( self::BLOCK_EDITOR_ASSETS_HOOK, $custom_property . $custom_property_preset )
		);
	}

	/**
	 * Enqueue block editor style
	 */
	public function enqueue_editor_style() {
		add_theme_support( 'editor-styles' );
		add_editor_style( 'css/block-editor.css' );
		add_editor_style( 'style.css' );
	}
}

new Block_Editor_Assets();
