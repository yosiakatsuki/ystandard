<?php
/**
 * カスタム投稿タイプ
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

/**
 * Class Custom_Post_Types
 *
 * @package ystandard
 */
class Custom_Post_Types {

	/**
	 * Custom_Post_Types constructor.
	 */
	public function __construct() {
		add_action( 'init', [ $this, 'register_ys_parts' ] );
		add_action( 'add_meta_boxes_ys-parts', [ $this, 'add_meta_box' ] );
		add_filter( 'manage_ys-parts_posts_columns', [ $this, 'add_columns_head' ] );
		add_action( 'manage_ys-parts_posts_custom_column', [ $this, 'add_custom_column' ], 10, 2 );
	}

	/**
	 * 投稿タイプの登録
	 */
	public function register_ys_parts() {
		$labels = [
			'name'               => '[ys]パーツ',
			'add_new_item'       => 'パーツを追加',
			'edit_item'          => '編集',
			'new_item'           => '新規作成',
			'view_item'          => 'パーツを表示',
			'search_items'       => '検索',
			'not_found'          => '見つかりませんでした',
			'not_found_in_trash' => 'ゴミ箱にはありません',
		];
		register_post_type(
			'ys-parts',
			[
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
				'supports'              => [
					'title',
					'editor',
					'revisions',
				],
			]
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
			[ $this, 'add_parts_shortcode_info' ],
			[ 'ys-parts' ],
			'side',
			'high'
		);
	}

	/**
	 * ショートコード表示メタボックスを追加
	 *
	 * @param \WP_Post $post 投稿オブジェクト.
	 */
	public function add_parts_shortcode_info( $post ) {
		if ( 'publish' !== $post->post_status ) {
			return;
		}
		?>
		<div id="ys-ogp-description-section" class="meta-box__section">
			<div class="copy-form" style="margin: 1em 0 2em;">
				<label>ショートコード
					<input type="text" id="ys_parts_shortcode" class="meta-box__input" value='[ys_parts <?php echo 'parts_id="' . esc_attr( $post->ID ) . '"'; ?>]' readonly onfocus="this.select();"/></label>
				<button class="copy-form__button button action">
					<i class="fas fa-clipboard"></i>
				</button>
				<div class="copy-form__info">コピーしました！</div>
			</div>
			<div class="meta-box__dscr">投稿・固定ページやウィジェットに表示するためのショートコード</div>
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
		if ( 'ys-parts' === $column_name ) {
			?>
			<div class="copy-form">
				<input type="text" id="ys_parts_shortcode" class="copy-form__target" value='[ys_parts parts_id="<?php echo esc_attr( absint( $post_ID ) ); ?>"]' readonly onfocus="this.select();"/>
				<button class="copy-form__button button action">
					<i class="fas fa-clipboard"></i>
				</button>
				<div class="copy-form__info">コピーしました！</div>
			</div>
			<?php
		}
	}

}

new Custom_Post_Types();
