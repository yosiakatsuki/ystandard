<?php
/**
 * カテゴリー表示クラス
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * YS_Widget_Post_Taxonomy
 */
class YS_Widget_Post_Taxonomy extends YS_Widget_Base {

	/**
	 * Default instance.
	 *
	 * @var array
	 */
	protected $default_instance = YS_Shortcode_Post_Taxonomy::SHORTCODE_PARAM;

	/**
	 * ウィジェットID
	 *
	 * @var string
	 */
	protected $widget_id = 'ys_post_taxonomy';
	/**
	 * ウィジェット名
	 *
	 * @var string
	 */
	protected $widget_name = '[ys]投稿カテゴリー・タグ表示';
	/**
	 * 実行するショートコード
	 *
	 * @var string
	 */
	protected $shortcode = 'ys_post_tax';

	/**
	 * ウィジェットオプション
	 *
	 * @var array
	 */
	public $widget_options = array(
		'classname'                   => '',
		'description'                 => '',
		'customize_selective_refresh' => true,
	);

	/**
	 * Sets up widget instance.
	 *
	 * @param array $args args.
	 */
	public function __construct( $args = array() ) {
		parent::__construct(
			$this->widget_id,
			$this->widget_name,
			$this->widget_options
		);
		/**
		 * 追加設定
		 */
		$this->default_instance = array_merge(
			$this->default_instance,
			array(
				'taxonomy_widget' => explode( '|', $this->default_instance['taxonomy'] ),
				'icon_widget'     => str_replace( '|', PHP_EOL, $this->default_instance['icon'] ),
			),
			$args
		);
		$this->set_default_instance(
			$this->default_instance
		);
	}

	/**
	 * ウィジェットの内容を出力
	 *
	 * @param array $args     args.
	 * @param array $instance instance.
	 */
	public function widget( $args, $instance ) {
		/**
		 * パラメーター整理
		 */
		$sc_args = array_merge( $this->default_instance, $instance );
		/**
		 * ショートコード実行
		 */
		$sc_result = ys_do_shortcode(
			$this->shortcode,
			$sc_args,
			null,
			false
		);
		/**
		 * 結果があれば内容表示
		 */
		if ( $sc_result ) {
			echo $args['before_widget'];
			/**
			 * ウィジェットタイトル
			 */
			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
			}

			echo $sc_result;

			echo $args['after_widget'];
		}
	}

	/**
	 * 管理用のオプションのフォームを出力
	 *
	 * @param array $instance ウィジェットオプション.
	 */
	public function form( $instance ) {
		$instance = wp_parse_args(
			(array) $instance,
			$this->default_instance
		);
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'タイトル:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>">
		</p>
		<div class="ys-widget-option__section">
			<h4>表示カテゴリー・タグ</h4>
			<div class="ys-widget-option__check-list">
				<?php $this->get_select_html( $instance ); ?>
			</div>
			<h4>アイコン設定</h4>
			<textarea id="<?php echo $this->get_field_id( 'icon_widget' ); ?>" name="<?php echo $this->get_field_name( 'icon_widget' ); ?>" class="content widefat" rows="5"><?php echo esc_textarea( $instance['icon_widget'] ); ?></textarea>
		</div>
		<?php
		/**
		 * 共通設定
		 */
		$this->form_ys_advanced( $instance );
	}

	/**
	 * ウィジェットオプションの保存処理
	 *
	 * @param array $new_instance 新しいオプション.
	 * @param array $old_instance 以前のオプション.
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		/**
		 * 共通設定保存
		 */
		$instance = $this->update_base_options( $new_instance, $old_instance );
		/**
		 * 設定保存
		 */
		$instance['taxonomy_widget'] = $new_instance['taxonomy_widget'];
		$instance['taxonomy']        = implode( '|', $new_instance['taxonomy_widget'] );
		$instance['icon_widget']     = $new_instance['icon_widget'];
		$instance['icon']            = str_replace(
			array( "\r\n", "\r", "\n" ),
			'|',
			$new_instance['icon_widget']
		);

		return $instance;
	}

	/**
	 * タクソノミー選択チェックボックス作成
	 *
	 * @param array $instance instance.
	 *
	 * @return string
	 */
	private function get_select_html( $instance ) {
		/**
		 * 投稿タイプ取得
		 */
		$post_type = get_post_types(
			array(
				'public'  => true,
				'show_ui' => true,
			)
		);
		if ( empty( $post_type ) ) {
			return '';
		}
		/**
		 * 投稿タイプごとにタクソノミーリストを作成
		 */
		$taxonomies = array();
		$post_type  = array_diff( $post_type, array( 'attachment' ) );
		foreach ( $post_type as $type ) {
			/**
			 * タクソノミー取得
			 */
			$tax        = get_taxonomies(
				array(
					'object_type' => array( $type ),
					'public'      => true,
					'show_ui'     => true,
				),
				'objects'
			);
			$taxonomies = array_merge( $taxonomies, $tax );
		}
		/**
		 * タクソノミー一覧作成
		 */
		foreach ( $taxonomies as $taxonomy ) {
			printf(
				'<label for="%s"><input type="checkbox" id="%s" name="%s" value="%s" %s />%s</label>',
				$this->get_field_id( 'taxonomy_widget' ) . $taxonomy->name,
				$this->get_field_id( 'taxonomy_widget' ) . $taxonomy->name,
				$this->get_field_name( 'taxonomy_widget' ) . '[]',
				$taxonomy->name,
				checked( ys_in_array( $taxonomy->name, $instance['taxonomy_widget'] ), true, false ),
				$taxonomy->label
			);
		}
	}
}
