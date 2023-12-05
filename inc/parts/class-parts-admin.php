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
use ystandard\utils\Manual;
use ystandard\utils\Post_Type;

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
		$post_type = Post_Type::get_post_type();
		$manual    = Manual::manual_link( 'manual/ys-parts' );
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
		?>
		<div>
			<?php
			if ( 'publish' === $post->post_status ) {
				self::the_copy_form( $post->ID );
			} else {
				self::the_not_publish_info();
			}
			?>
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
			$post = get_post( $post_ID );
			if ( 'publish' === $post->post_status ) {
				self::the_copy_form( $post_ID );
			} else {
				self::the_not_publish_info();
			}
		}
	}


	/**
	 * クリップボードコピーフォーム出力
	 *
	 * @param int $post_ID 投稿ID.
	 *
	 * @return void
	 */
	private static function the_copy_form( $post_ID ) {
		?>
		<div class="ys-clipboard-copy tw-flex tw-gap-2 tw-relative">
			<label class="tw-w-full">
				<input type="text" class="ys-clipboard-copy__target tw-block tw-w-full"
					   value='[ys_parts parts_id="<?php echo esc_attr( absint( $post_ID ) ); ?>"]' readonly
					   onfocus="this.select();"/>
			</label>
			<button type="button"
					class="ys-clipboard-copy__button button action tw-flex tw-items-center tw-justify-center tw-px-2">
				<?php echo do_shortcode( '[ys_icon name="clipboard"]' ); ?>
			</button>
			<span
				class="ys-clipboard-copy__info tw-text-fz-xxs tw-text-gray-600 tw-absolute tw-bottom-0 tw-left-0 tw-translate-y-full">コピーしました！</span>
		</div>
		<?php
	}

	/**
	 * パーツ未公開状態の説明
	 *
	 * @return void
	 */
	private static function the_not_publish_info() {
		?>
		<div class="tw-text-gray-600">
			<p class="tw-text-fz-xs tw-m-0 tw-mb-1">
				<?php _e( 'パーツが公開されていません。', 'ystandard' ); ?>
			</p>
			<p class="tw-text-fz-xs tw-m-0">
				<?php _e( 'パーツ公開後、ページを再読み込みすると表示用のショートコードが表示されます。', 'ystandard' ); ?>
			</p>
		</div>
		<?php
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
