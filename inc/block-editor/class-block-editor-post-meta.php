<?php
/**
 * ブロックエディター 投稿設定.
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

/**
 * Class Block_Editor_Post_Meta
 *
 * @package ystandard
 */
class Block_Editor_Post_Meta {

	/**
	 * スクリプトハンドル.
	 */
	const SCRIPT_HANDLE = 'ys-block-editor-post-meta';

	/**
	 * Block_Editor_Post_Meta constructor.
	 */
	public function __construct() {
		add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_scripts' ] );
	}

	/**
	 * 投稿設定用スクリプトを読み込む.
	 */
	public function enqueue_scripts() {
		$screen = get_current_screen();
		if ( ! $screen || empty( $screen->post_type ) ) {
			return;
		}

		$post_type = $screen->post_type;
		if ( Parts::POST_TYPE !== $post_type && ! Post_Meta::is_block_editor_post_type( $post_type ) ) {
			return;
		}

		$script_path = get_template_directory() . '/js/block-editor/post-meta.js';
		$asset_path  = get_template_directory() . '/js/block-editor/post-meta.asset.php';
		if ( ! is_readable( $script_path ) || ! is_readable( $asset_path ) ) {
			return;
		}

		$asset = require $asset_path;
		wp_enqueue_script(
			self::SCRIPT_HANDLE,
			get_template_directory_uri() . '/js/block-editor/post-meta.js',
			$asset['dependencies'],
			$asset['version'],
			true
		);
		wp_set_script_translations( self::SCRIPT_HANDLE, 'ystandard', get_template_directory() . '/languages' );
		$post = get_post();
		wp_add_inline_script(
			self::SCRIPT_HANDLE,
			'window.ystandardPostMetaSettings = ' . wp_json_encode(
				$this->get_script_settings( $post_type, $post instanceof \WP_Post ? $post->ID : 0 ),
				JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
			) . ';',
			'before'
		);
	}

	/**
	 * JavaScriptへ渡す設定を取得.
	 *
	 * @param string $post_type 投稿タイプ.
	 * @param int    $post_id   投稿ID.
	 *
	 * @return array
	 */
	private function get_script_settings( $post_type, $post_id ) {
		$fields = [];
		if ( Parts::POST_TYPE !== $post_type ) {
			foreach ( Post_Meta::get_meta_fields() as $key => $field ) {
				if ( ! Post_Meta::is_field_available_for_post_type( $field, $post_type ) ) {
					continue;
				}
				$fields[] = [
					'key'     => $key,
					'type'    => $field['type'],
					'control' => isset( $field['control'] ) ? $field['control'] : 'toggle',
					'panel'   => $field['panel'],
					'label'   => $field['label'],
					'help'    => isset( $field['help'] ) ? $field['help'] : '',
				];
			}
		}

		return [
			'postType'            => $post_type,
			'partsPostType'       => Parts::POST_TYPE,
			'postSettingsContext' => [
				'apiVersion' => 1,
				'postType'   => $post_type,
				'postId'     => $post_id,
			],
			'fields'              => $fields,
			'panels'              => [
				'post' => __( '[ys] 投稿設定', 'ystandard' ),
				'seo'  => __( '[ys] SEO設定', 'ystandard' ),
				'sns'  => __( '[ys] SNS設定', 'ystandard' ),
			],
		];
	}
}

new Block_Editor_Post_Meta();
