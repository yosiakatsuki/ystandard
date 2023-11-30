<?php
/**
 * [ys]パーツの管理画面関連処理
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */

namespace ystandard;

use ystandard\utils\Admin_Notice;

defined( 'ABSPATH' ) || die();

/**
 * Class Parts_Admin
 *
 * @package ystandard
 */
class Parts_Admin {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$post_type = Parts::POST_TYPE;
		add_action( "add_meta_boxes_{$post_type}", [ $this, 'add_meta_box' ] );
		add_filter( "manage_{$post_type}_posts_columns", [ $this, 'add_columns_head' ] );
		add_action( "manage_{$post_type}_posts_custom_column", [ $this, 'add_custom_column' ], 10, 2 );
		add_filter( 'pre_get_posts', [ $this, 'set_order' ] );
		add_action( 'admin_notices', [ $this, 'add_manual_link_notice' ] );
	}

	/**
	 * [ys]パーツ一覧を取得
	 *
	 * @param bool $show_option_none 選択なしを表示するか.
	 *
	 * @return array
	 */
	public static function get_parts_list( $show_option_none = false ) {

		$list = [];
		// 「選択なし」の追加.
		if ( $show_option_none ) {
			$list[0] = __( '&mdash; Select &mdash;' );
		}

		$parts = get_posts(
			[
				'post_type'      => Parts::POST_TYPE,
				'posts_per_page' => - 1,
			]
		);

		// パーツのリストを作成.
		foreach ( $parts as $data ) {
			$list[ $data->ID ] = $data->post_title;
		}

		return $list;
	}


	/**
	 * マニュアルリンクの追加
	 *
	 * @return void
	 */
	public function add_manual_link_notice() {
		global $pagenow;
		$post_type = Content::get_post_type();
		$manual    = Admin::manual_link( 'manual/ys-parts' );
		// パーツの投稿一覧でマニュアルリンクを表示
		if ( 'edit.php' === $pagenow && Parts::POST_TYPE === $post_type ) {
			Admin_Notice::manual( $manual );
		}
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
			_x( 'ショートコード', 'ys-parts-meta-box', 'ystandard' ),
			[ $this, 'add_parts_shortcode_info' ],
			[ Parts::POST_TYPE ],
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
		// 公開されていない場合ショートコードも表示しない.
		if ( 'publish' !== $post->post_status ) {
			return;
		}
		?>
		<div>
			<label for="ys_parts_shortcode">ショートコード</label>
			<div class="copy-form">
				<input type="text" id="ys_parts_shortcode" class="copy-form__target"
					   value='[ys_parts <?php echo 'parts_id="' . esc_attr( $post->ID ) . '"'; ?>]' readonly
					   onfocus="this.select();"/>
				<button class="copy-form__button button action">
					<?php echo ys_get_icon( 'clipboard' ); ?>
				</button>
				<div class="copy-form__info">コピーしました！</div>
			</div>
			<div class="meta-box__dscr">パーツを表示するためのショートコード</div>
		</div>
		<?php
	}

	/**
	 * カラムのヘッダーを追加
	 *
	 * @param array $post_columns カラム.
	 *
	 * @return array
	 */
	public function add_columns_head( $post_columns ) {
		$post_columns['shortcode'] = 'ショートコード';

		return $post_columns;
	}

	/**
	 * カラム追加
	 *
	 * @param string $column_name カラム名.
	 * @param int $post_ID 投稿ID.
	 */
	public function add_custom_column( $column_name, $post_ID ) {
		if ( 'shortcode' === $column_name ) {
			?>
			<div class="copy-form">
				<input type="text" class="copy-form__target"
					   value='[ys_parts parts_id="<?php echo esc_attr( absint( $post_ID ) ); ?>"]' readonly
					   onfocus="this.select();"/>
				<button type="button" class="copy-form__button button action">
					<?php echo ys_get_icon( 'clipboard' ); ?>
				</button>
				<div class="copy-form__info">コピーしました！</div>
			</div>
			<?php
		}
	}

	/**
	 * 管理画面並び替え(日付順にする)
	 *
	 * @param \WP_Query $query query.
	 */
	public function set_order( $query ) {
		if ( is_admin() ) {
			if ( isset( $query->query['post_type'] ) && Parts::POST_TYPE === $query->query['post_type'] ) {
				// パーツ一覧画面で並び替えを指定していない場合は日付順にする.
				if ( ! filter_input( INPUT_GET, 'orderby' ) ) {
					$query->set( 'orderby', 'date' );
					$query->set( 'order', 'DESC' );
				}
			}
		}
	}


}

new Parts_Admin();
