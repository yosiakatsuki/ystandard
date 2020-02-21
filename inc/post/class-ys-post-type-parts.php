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
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'add_meta_boxes_ys-parts', array( $this, 'add_meta_box' ) );
		add_filter( 'manage_ys-parts_posts_columns', array( $this, 'add_columns_head' ) );
		add_action( 'manage_ys-parts_posts_custom_column', array( $this, 'add_custom_column' ), 10, 2 );
	}

	/**
	 * 投稿タイプの登録
	 */
	public function register_post_type() {
		$labels = array(
			'name'               => '[ys]パーツ',
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
				'hierarchical'          => true,
				'show_in_rest'          => true,
				'capability_type'       => 'page',
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

	/**
	 * メタボックス追加
	 */
	public function add_meta_box() {
		/**
		 * 投稿オプション
		 */
		add_meta_box(
			'ys_add_parts_shortcode_info',
			'ショートコード',
			array( $this, 'add_parts_shortcode_info' ),
			array( 'ys-parts' ),
			'side',
			'high'
		);
	}

	/**
	 * ショートコード表示メタボックスを追加
	 *
	 * @param WP_Post $post 投稿オブジェクト.
	 */
	public function add_parts_shortcode_info( $post ) {
		?>
		<div id="ys-ogp-description-section" class="meta-box__section">
			<?php if ( 'publish' === $post->post_status ) : ?>
				<input type="text" id="ys_parts_shortcode" class="meta-box__full-w" value='[ys_parts <?php echo 'id="' . esc_attr( $post->ID ) . '"'; ?>]' readonly onfocus="this.select();"/>
				<div class="meta-box__dscr">投稿・固定ページやウィジェットに表示するためのショートコード</div>
			<?php else: ?>
				<div class="meta-box__dscr">公開後にショートコードが表示されます。</div>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * カラムのヘッダーを追加
	 *
	 * @param array $defaults カラム.
	 *
	 * @return array
	 */
	public function add_columns_head( $defaults ) {
		$defaults['ys-parts'] = 'ショートコード';

		return $defaults;
	}

	/**
	 * カラム追加
	 *
	 * @param string $column_name カラム名.
	 * @param int    $post_ID     投稿ID.
	 */
	public function add_custom_column( $column_name, $post_ID ) {
		if ( $column_name === 'ys-parts' ) {
			echo '<input type="text" id="ys_parts_shortcode" class="meta-box__full-w" value=\'[ys_parts id="' . esc_attr( absint( $post_ID ) ) . '"]\' readonly onfocus="this.select();"/>';
//			echo '[ys_parts id="' . absint( $post_ID ) . '"]';
		}
	}
}

new YS_Post_Type_Parts();
