<?php
/**
 * 投稿タイプ設定管理クラス
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

use ystandard\utils\Post_Type;
use ystandard\Customizer;

defined( 'ABSPATH' ) || die();

/**
 * Class Post_Types_Manager
 */
class Post_Types_Manager {

	/**
	 * 管理対象投稿タイプ
	 *
	 * @var array
	 */
	private $managed_post_types = [];

	/**
	 * Post_Types_Manager constructor.
	 */
	public function __construct() {
		add_action( 'customize_register', [ $this, 'customize_register' ], 10 );
	}

	/**
	 * 管理対象投稿タイプを取得
	 *
	 * @return array
	 */
	private function get_managed_post_types() {
		// パブリック投稿タイプを取得.
		$post_types = Post_Type::get_post_types();
		$managed    = [];

		foreach ( $post_types as $post_type_name => $post_type_label ) {
			$post_type_object = get_post_type_object( $post_type_name );

			$managed[ $post_type_name ] = [
				'name'         => $post_type_name,
				'label'        => $post_type_label,
				'hierarchical' => $post_type_object->hierarchical,
			];
		}

		return $managed;
	}

	/**
	 * カスタマイザー設定登録
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 */
	public function customize_register( $wp_customize ) {
		// 投稿タイプを取得.
		$this->managed_post_types = $this->get_managed_post_types();

		// 基本設定の後に配置.
		$priority_setting          = Customizer::get_priority( 'ys_post_type_option' );
		$custom_post_type_priority = $priority_setting + 10;

		foreach ( $this->managed_post_types as $post_type_data ) {
			$post_type = $post_type_data['name'];
			$label     = $post_type_data['label'];

			if ( 'post' === $post_type ) {
				// 標準の投稿設定を投稿タイプ別設定群の先頭に固定する.
				$priority = $priority_setting;
			} elseif ( 'page' === $post_type ) {
				// 標準の固定ページ設定を投稿の直後に固定する.
				$priority = $priority_setting + 1;
			} else {
				// 複数のカスタム投稿タイプが同じ優先度にならないよう、追加順に1刻みで割り当てる.
				$priority = $custom_post_type_priority;
				++$custom_post_type_priority;
			}

			new Post_Type_Customizer( $wp_customize, $post_type, $label, $priority );
		}
	}
}

new Post_Types_Manager();
