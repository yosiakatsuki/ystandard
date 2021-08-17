<?php
/**
 * ブロックエディター
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

/**
 * Class Block_Editor
 *
 * @package ystandard
 */
class Block_Editor {

	/**
	 * Panel Name.
	 */
	const PANEL_NAME = 'ys_block_editor';

	/**
	 * Block_Editor constructor.
	 */
	public function __construct() {
		if ( class_exists( 'WP_Block_Editor_Context' ) ) {
			add_filter( 'allowed_block_types_all', [ $this, 'disallow_block_types' ], 10, 2 );
		}
		add_filter( 'ys_disallowed_block_types_all', [ $this, 'disallow_fse_blocks' ], 1 );
		add_action( 'customize_register', [ $this, 'customize_register' ], 9 );
	}

	/**
	 * 使用できるブロックを制限する
	 *
	 * @param bool|array               $allowed_block_types 許可するブロック.
	 * @param \WP_Block_Editor_Context $editor_context      コンテキスト.
	 *
	 * @return bool|array
	 */
	public function disallow_block_types( $allowed_block_types, $editor_context ) {

		$registered_blocks = \WP_Block_Type_Registry::get_instance()->get_all_registered();
		if ( ! is_array( $allowed_block_types ) ) {
			$allowed_block_types = array_keys( $registered_blocks );
		}
		$disallowed_block_types = apply_filters(
			'ys_disallowed_block_types_all',
			[],
			$editor_context,
			$allowed_block_types
		);

		if ( ! empty( $block_editor_context->post ) ) {
			// Post.
			$disallowed_block_types = apply_filters(
				'ys_disallowed_block_types_post',
				$disallowed_block_types,
				$editor_context,
				$allowed_block_types
			);
		} else {
			// Widget.
			$disallowed_block_types = apply_filters(
				'ys_disallowed_block_types_widget',
				$disallowed_block_types,
				$editor_context,
				$allowed_block_types
			);
		}

		if ( is_array( $disallowed_block_types ) && ! empty( $disallowed_block_types ) ) {
			$allowed_block_types = array_values(
				array_diff(
					$allowed_block_types,
					$disallowed_block_types
				)
			);
		}

		return $allowed_block_types;
	}

	/**
	 * FSE関連ブロックを削除
	 *
	 * @param array $disallowed_block_types 除外ブロックリスト.
	 *
	 * @return array
	 */
	public function disallow_fse_blocks( $disallowed_block_types ) {

		if ( apply_filters( 'ys_enable_fse_block_types', false ) ) {
			return $disallowed_block_types;
		}

		return array_merge(
			$disallowed_block_types,
			[
				'core/loginout',
				'core/page-list',
				'core/post-content',
				'core/post-date',
				'core/post-excerpt',
				'core/post-featured-image',
				'core/post-terms',
				'core/post-title',
				'core/post-template',
				'core/query-loop',
				'core/query',
				'core/query-pagination',
				'core/query-pagination-next',
				'core/query-pagination-numbers',
				'core/query-pagination-previous',
				'core/query-title',
				'core/site-logo',
				'core/site-title',
				'core/site-tagline',
			]
		);
	}

	/**
	 * カスタマイザー追加
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 */
	public function customize_register( $wp_customize ) {
		$customizer = new Customize_Control( $wp_customize );

		$customizer->add_panel(
			[
				'panel'       => self::PANEL_NAME,
				'title'       => '[ys]ブロックエディター',
				'description' => 'ブロックエディター関連の設定',
			]
		);
	}
}

new Block_Editor();
