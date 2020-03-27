<?php
/**
 * 記事取得ウィジェット基本クラス
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 記事取得ウィジェット基本クラス
 */
abstract class YS_Widget_Get_Posts extends YS_Widget_Base {

	/**
	 * Default instance.
	 *
	 * @var array
	 */
	protected $default_instance = YS_Shortcode_Get_Posts::SHORTCODE_PARAM;

	/**
	 * ウィジェットID
	 *
	 * @var string
	 */
	protected $widget_id = '';
	/**
	 * ウィジェット名
	 *
	 * @var string
	 */
	protected $widget_name = '';

	/**
	 * デフォルトタイトル
	 *
	 * @var string
	 */
	protected $default_title = '';

	/**
	 * 実行するショートコード
	 *
	 * @var string
	 */
	protected $shortcode = '';

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
		$this->default_instance = array_merge( $this->default_instance, $args );
		/**
		 * 初期値セット
		 */
		$this->default_instance['title'] = $this->default_title;
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
		$sc_args          = array_merge( $this->default_instance, $instance );
		$sc_args['title'] = '';
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

		/**
		 * フォーム作成
		 */
		$this->form_title( $instance );
		/**
		 * 表示設定
		 */
		$this->form_display( $instance );
		/**
		 * 画像設定
		 */
		$this->form_image( $instance );
		/**
		 * ウィジェット独自の設定
		 */
		$this->form_widget( $instance );
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
		 * 基本設定保存
		 */
		$instance['count']          = $this->sanitize_num(
			$new_instance['count'],
			$this->default_instance['count']
		);
		$instance['show_excerpt']   = $this->sanitize_checkbox( $new_instance['show_excerpt'] );
		$instance['list_type']      = $new_instance['list_type'];
		$instance['col_sp']         = $this->sanitize_col(
			$new_instance['col_sp'],
			$this->default_instance['col_sp']
		);
		$instance['col_tablet']     = $this->sanitize_col(
			$new_instance['col_tablet'],
			$this->default_instance['col_tablet']
		);
		$instance['col_pc']         = $this->sanitize_col(
			$new_instance['col_pc'],
			$this->default_instance['col_pc']
		);
		$instance['show_img']       = $this->sanitize_checkbox( $new_instance['show_img'] );
		$instance['thumbnail_size'] = $new_instance['thumbnail_size'];
		/**
		 * ウィジェット設定保存
		 */
		$instance = $this->update_widget_option( $instance, $new_instance, $old_instance );

		return $instance;
	}

	/**
	 * ウィジェット設定の作成
	 *
	 * @param array $instance     更新用オプション.
	 * @param array $new_instance 新しいオプション.
	 * @param array $old_instance 以前のオプション.
	 *
	 * @return array
	 */
	abstract protected function update_widget_option( $instance, $new_instance, $old_instance );

	/**
	 * タイトル設定
	 *
	 * @param array $instance instance.
	 */
	private function form_title( $instance ) {
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'タイトル:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>">
		</p>
		<?php
	}

	/**
	 * 表示設定
	 *
	 * @param array $instance instance.
	 */
	private function form_display( $instance ) {
		?>
		<div class="ys-widget-option__section">
			<h4>表示設定</h4>
			<p>
				<label for="<?php echo $this->get_field_id( 'count' ); ?>">表示する投稿数:</label>
				<input class="tiny-text" id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>" type="number" step="1" min="1" value="<?php echo $instance['count']; ?>" size="3"/>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'list_type' ); ?>">表示タイプ:</label>
				<select name="<?php echo $this->get_field_name( 'list_type' ); ?>">
					<?php
					$list_type = YS_Shortcode_Get_Posts::LIST_TYPE;
					foreach ( $list_type as $key => $value ) :
						?>
						<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $instance['list_type'] ); ?>><?php echo $value; ?></option>
					<?php endforeach; ?>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'show_excerpt' ); ?>">
					<input type="checkbox" id="<?php echo $this->get_field_id( 'show_excerpt' ); ?>" name="<?php echo $this->get_field_name( 'show_excerpt' ); ?>" value="1" <?php checked( Utility::to_bool( $instance['show_excerpt'] ), true ); ?> />抜粋を表示する
				</label>
			</p>
		</div>
		<div class="ys-widget-option__section">
			<h4>表示列数設定</h4>
			<p>
				<label for="<?php echo $this->get_field_id( 'col_sp' ); ?>"><i class="fas fa-mobile-alt fa-fw"></i></label>
				<input class="tiny-text" id="<?php echo $this->get_field_id( 'col_sp' ); ?>" name="<?php echo $this->get_field_name( 'col_sp' ); ?>" type="number" step="1" min="1" max="6" value="<?php echo $this->sanitize_col( $instance['col_sp'] ); ?>" size="1"/><br>
				<label for="<?php echo $this->get_field_id( 'col_tablet' ); ?>"><i class="fas fa-tablet-alt fa-fw"></i></label>
				<input class="tiny-text" id="<?php echo $this->get_field_id( 'col_tablet' ); ?>" name="<?php echo $this->get_field_name( 'col_tablet' ); ?>" type="number" step="1" min="1" max="6" value="<?php echo $this->sanitize_col( $instance['col_tablet'] ); ?>" size="1"/><br>
				<label for="<?php echo $this->get_field_id( 'col_pc' ); ?>"><i class="fas fa-desktop fa-fw"></i></label>
				<input class="tiny-text" id="<?php echo $this->get_field_id( 'col_pc' ); ?>" name="<?php echo $this->get_field_name( 'col_pc' ); ?>" type="number" step="1" min="1" max="6" value="<?php echo $this->sanitize_col( $instance['col_pc'] ); ?>" size="1"/><br>
				<span class="ys-widget-option__sub">※表示タイプが「横スライド」の場合、列設定は無視されます。</span>
			</p>
		</div>
		<?php
	}

	/**
	 * 画像設定
	 *
	 * @param array $instance instance.
	 */
	private function form_image( $instance ) {
		?>
		<div class="ys-widget-option__section">
			<h4>画像設定</h4>
			<p>
				<label for="<?php echo $this->get_field_id( 'show_img' ); ?>">
					<input type="checkbox" id="<?php echo $this->get_field_id( 'show_img' ); ?>" name="<?php echo $this->get_field_name( 'show_img' ); ?>" value="1" <?php checked( Utility::to_bool( $instance['show_img'] ), true ); ?> />アイキャッチ画像を表示する
				</label><br/>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'thumbnail_size' ); ?>">表示する画像のサイズ:</label><br/>
				<select name="<?php echo $this->get_field_name( 'thumbnail_size' ); ?>">
					<?php foreach ( $this->get_image_sizes() as $key => $value ) : ?>
						<option
							value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $instance['thumbnail_size'] ); ?>><?php echo $key; ?>
							(横:<?php echo esc_html( $value['width'] ); ?> x
							縦:<?php echo esc_html( $value['height'] ); ?>)
						</option>
					<?php endforeach; ?>
				</select><br/>
				<span class="ys-widget-option__sub">※「thumbnail」の場合、75pxの正方形で表示されます<br>※それ以外の画像はウィジェットの横幅に対して16:9の比率で表示されます。</span>
				<span class="ys-widget-option__sub">※表示タイプが「カード」「横スライド」の場合、「表示する画像サイズ」は「thumbnail」以外を選択して下さい。</span>
			</p>
		</div>
		<?php
	}

	/**
	 * ウィジェットごとの設定
	 *
	 * @param array $instance instance.
	 */
	abstract protected function form_widget( $instance );

	/**
	 * カラム数のサニタイズ
	 *
	 * @param int $col     カラム.
	 * @param int $default デフォルト.
	 *
	 * @return int
	 */
	protected function sanitize_col( $col, $default = 1 ) {
		if ( ! is_numeric( $col ) ) {
			$col = $this->sanitize_col( $default, 1 );
		}
		if ( 1 > $col ) {
			$col = $this->sanitize_col( $default, 1 );
		}
		if ( 6 < $col ) {
			$col = $this->sanitize_col( $default, 6 );
		}

		return $col;
	}
}
