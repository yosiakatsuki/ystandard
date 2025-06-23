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
		$priority_setting = Customizer::get_priority( 'ys_post_type_option' );

		foreach ( $this->managed_post_types as $post_type_data ) {
			$post_type = $post_type_data['name'];
			$label     = $post_type_data['label'];

			// 標準投稿タイプの優先度調整. post はそのまま、pageは+1、それ以外は + 10.
			if ( 'post' === $post_type ) {
				$priority = $priority_setting;
			} elseif ( 'page' === $post_type ) {
				$priority = $priority_setting + 1;
			} else {
				$priority = $priority_setting + 10;
			}

			new Post_Type_Customizer( $wp_customize, $post_type, $label, $priority );
		}
	}
}

new Post_Types_Manager();
