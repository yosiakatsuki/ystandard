<?php
/**
 * フッター
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

/**
 * Class Parts
 *
 * @package ystandard
 */
class Parts {

	/**
	 * 投稿タイプ
	 */
	const POST_TYPE = 'ys-parts';

	/**
	 * ショートコード
	 */
	const SHORTCODE = 'ys_parts';

	/**
	 * ショートコードパラメーター
	 */
	const SHORTCODE_ATTR = [
		'parts_id'          => 0,
		'use_entry_content' => '',
	];


	/**
	 * Register
	 */
	public function register() {
		$post_type = self::POST_TYPE;
		add_action( 'init', [ $this, 'register_post_type' ], 9 );
		add_filter( 'pre_get_posts', [ $this, 'set_order' ] );
		add_action( "add_meta_boxes_${post_type}", [ $this, 'add_meta_box' ] );
		add_filter( "manage_${post_type}_posts_columns", [ $this, 'add_columns_head' ] );
		add_action( "manage_${post_type}_posts_custom_column", [ $this, 'add_custom_column' ], 10, 2 );
		// ショートコード.
		if ( ! shortcode_exists( self::SHORTCODE ) ) {
			add_shortcode( self::SHORTCODE, [ $this, 'do_shortcode' ] );
		}
		/**
		 * ウィジェット
		 */
		add_action( 'widgets_init', [ $this, 'register_widget' ] );
		Notice::set_notice( [ $this, 'manual' ] );
	}

	/**
	 * マニュアルリンク
	 */
	public function manual() {
		global $pagenow;
		$post_type = Content::get_post_type();
		$manual    = Admin::manual_link( 'ys-parts' );
		if ( 'edit.php' === $pagenow && Parts::POST_TYPE === $post_type ) {
			Notice::manual( '<p>' . $manual . '</p>' );
		}
	}

	/**
	 * ウィジェット登録
	 */
	public function register_widget() {
		\YS_Loader::require_file( __DIR__ . '/class-ys-widget-parts.php' );
		register_widget( 'YS_Widget_Parts' );
	}

	/**
	 * ショートコード実行
	 *
	 * @param array $atts Attributes.
	 *
	 * @return string
	 */
	public function do_shortcode( $atts ) {

		$atts = shortcode_atts( self::SHORTCODE_ATTR, $atts );
		/**
		 * ラッパー
		 */
		$wrap = '%s';
		if ( Utility::to_bool( $atts['use_entry_content'] ) ) {
			$wrap = '<div class="entry-content entry__content">%s</div>';
		}
		/**
		 * コンテンツ作成
		 */
		$parts_id = $atts['parts_id'];
		if ( is_numeric( $parts_id ) && 0 < $parts_id ) {
			$parts_id = (int) $parts_id;
		} else {
			return '';
		}
		$post = get_post( $parts_id );
		if ( ! $post ) {
			return '';
		}
		$content = $this->get_parts_content( $post->post_content );

		return sprintf( $wrap, $content );
	}

	/**
	 * パーツのコンテンツ部分取得
	 *
	 * @param string $content Content.
	 *
	 * @return string
	 */
	private function get_parts_content( $content ) {
		// TOC処理のキャンセル.
		$temp = apply_filters( 'ys_create_toc', true );
		remove_all_filters( 'ys_create_toc' );
		add_filter( 'ys_create_toc', '__return_false' );
		// [wpautop]のキャンセル.
		$priority      = has_filter( 'the_content', 'wpautop' );
		$remove_auto_p = false !== $priority && has_blocks( $content );
		if ( $remove_auto_p ) {
			remove_filter( 'the_content', 'wpautop', $priority );
		}
		// コンテンツ展開.
		do_action( 'ys_parts_content_before' );
		$content = apply_filters( 'the_content', $content );
		$content = str_replace( ']]>', ']]&gt;', $content );
		do_action( 'ys_parts_content_after' );
		// [wpautop]戻し.
		if ( $remove_auto_p ) {
			add_filter( 'the_content', '_restore_wpautop_hook', $priority + 1 );
		}

		// TOC処理戻し.
		$filter = true === $temp ? '__return_true' : '__return_false';
		add_filter( 'ys_create_toc', $filter );

		return $content;
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
		if ( $show_option_none ) {
			$list[0] = __( '&mdash; Select &mdash;' );
		}

		$parts = get_posts(
			[
				'post_type'      => self::POST_TYPE,
				'posts_per_page' => 0,
			]
		);

		foreach ( $parts as $data ) {
			$list[ $data->ID ] = $data->post_title;
		}

		return $list;
	}

	/**
	 * 投稿タイプの登録
	 */
	public function register_post_type() {
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
			self::POST_TYPE,
			[
				'labels'                => $labels,
				'public'                => $this->is_enable_preview(),
				'exclude_from_search'   => true,
				'publicly_queryable'    => $this->is_enable_preview(),
				'show_ui'               => true,
				'show_in_nav_menus'     => false,
				'show_in_menu'          => true,
				'menu_icon'             => Admin_Menu::get_menu_icon(),
				'menu_position'         => 20,
				'description'           => 'サイト内で使用するコンテンツのパーツを登録できます。',
				'has_archive'           => false,
				'hierarchical'          => true,
				'show_in_rest'          => true,
				'capability_type'       => 'page',
				'rest_base'             => self::POST_TYPE,
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
	 * プレビューを有効にするか
	 */
	private function is_enable_preview() {
		if ( ! is_user_logged_in() ) {
			return false;
		}

		return current_user_can( 'editor' ) || current_user_can( 'administrator' );
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
	public function add_parts_shortcode_info(
		$post
	) {
		if ( 'publish' !== $post->post_status ) {
			return;
		}
		?>
		<div id="ys-ogp-description-section" class="meta-box__section">
			<label for="ys_parts_shortcode" style="margin: 1em 0 0;display: block;">ショートコード</label>
			<div class="copy-form" style="margin: 0 0 1.5em;">
				<input type="text" id="ys_parts_shortcode" class="copy-form__target" value='[ys_parts <?php echo 'parts_id="' . esc_attr( $post->ID ) . '"'; ?>]' readonly onfocus="this.select();"/>
				<button class="copy-form__button button action">
					<?php echo ys_get_icon( 'clipboard' ); ?>
				</button>
				<div class="copy-form__info">コピーしました！</div>
			</div>
			<div class="meta-box__dscr">投稿・固定ページやウィジェットに表示するためのショートコード</div>
		</div>
		<?php
	}


	/**
	 * 管理画面並び替え
	 *
	 * @param \WP_Query $query query.
	 */
	public function set_order( $query ) {
		if ( is_admin() ) {
			if ( isset( $query->query['post_type'] ) && self::POST_TYPE === $query->query['post_type'] ) {
				$query->set( 'orderby', 'date' );
				$query->set( 'order', 'DESC' );
			}
		}
	}

	/**
	 * カラムのヘッダーを追加
	 *
	 * @param array $defaults カラム.
	 *
	 * @return array
	 */
	public function add_columns_head(
		$defaults
	) {
		$defaults['ys-parts'] = 'ショートコード';

		return $defaults;
	}

	/**
	 * カラム追加
	 *
	 * @param string $column_name カラム名.
	 * @param int    $post_ID     投稿ID.
	 */
	public function add_custom_column(
		$column_name, $post_ID
	) {
		if ( 'ys-parts' === $column_name ) {
			?>
			<div class="copy-form">
				<input type="text" class="copy-form__target" value='[ys_parts parts_id="<?php echo esc_attr( absint( $post_ID ) ); ?>"]' readonly onfocus="this.select();"/>
				<button class="copy-form__button button action">
					<?php echo ys_get_icon( 'clipboard' ); ?>
				</button>
				<div class="copy-form__info">コピーしました！</div>
			</div>
			<?php
		}
	}

}

$class_parts = new Parts();
$class_parts->register();
