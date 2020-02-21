<?php
/**
 * 投稿タイプ - パーツ
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * Class YS_Post_Type_Parts
 */
class YS_Post_Type_Parts {

	/**
	 * YS_Post_Type_Parts constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_post_type' ), 20 );
	}

	/**
	 * 投稿タイプの登録
	 */
	public function register_post_type() {
		$labels = array(
			'name'               => 'パーツ',
			'add_new_item'       => 'パーツを追加',
			'edit_item'          => '編集',
			'new_item'           => '新規作成',
			'view_item'          => 'パーツを表示',
			'search_items'       => '検索',
			'not_found'          => '見つかりませんでした',
			'not_found_in_trash' => 'ゴミ箱にはありません',
		);
		register_post_type(
			'ys-parts',
			array(
				'labels'                => $labels,
				'public'                => false,
				'exclude_from_search'   => true,
				'publicly_queryable'    => false,
				'show_ui'               => true,
				'show_in_nav_menus'     => false,
				'show_in_menu'          => true,
				'menu_icon'             => 'dashicons-edit',
				'menu_position'         => 20,
				'description'           => 'サイト内で使用するコンテンツのパーツを登録できます。',
				'has_archive'           => false,
				'show_in_rest'          => true,
				'capability_type'       => 'post',
				'rest_base'             => 'ys-parts',
				'rest_controller_class' => 'WP_REST_Posts_Controller',
				'supports'              => array(
					'title',
					'editor',
					'revisions',
				),
			)
		);
	}
}

new YS_Post_Type_Parts();
