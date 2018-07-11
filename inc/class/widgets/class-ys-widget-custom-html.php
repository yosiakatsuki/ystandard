<?php
/**
 * 表示表示付きカスタムHTML
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 表示条件付きカスタムHTML
 */
class YS_Widget_Custom_HTML extends YS_Widget_Base {
	/**
	 * Whether or not the widget has been registered yet.
	 *
	 * @var bool
	 */
	protected $registered = false;

	/**
	 * Default instance.
	 *
	 * @var array
	 */
	protected $default_instance = array(
		'title'      => '',
		'content'    => '',
		'taxonomy'   => '',
		'start_date' => '',
		'start_time' => '',
		'end_flag'   => 0,
		'end_date'   => '',
		'end_time'   => '',
	);

	/**
	 * WP_Widget_Custom_HTML
	 *
	 * @var WP_Widget_Custom_HTML
	 */
	protected $wp_widget_custom_html;

	/**
	 * ウィジェットID
	 *
	 * @var string
	 */
	protected $ys_widget_id = 'ys_custom_html';
	/**
	 * ウィジェット名
	 *
	 * @var string
	 */
	protected $ys_widget_name = '[ys]表示条件付きカスタムHTML';

	/**
	 * ウィジェットオプション
	 *
	 * @var array
	 */
	protected $ys_widget_ops = array(
		'classname'                   => 'ys_widget_custom_html',
		'description'                 => '表示条件付きカスタムHTML',
		'customize_selective_refresh' => true,
	);
	/**
	 * コントロールオプション
	 *
	 * @var array
	 */
	protected $ys_control_ops = array(
		'width'  => 400,
		'height' => 350,
	);

	/**
	 * Sets up a new Custom HTML widget instance.
	 */
	public function __construct() {
		parent::__construct(
			$this->ys_widget_id,
			$this->ys_widget_name,
			$this->ys_widget_ops,
			$this->ys_control_ops
		);
		/**
		 * 初期値更新
		 */
		$this->default_instance = wp_parse_args(
			YS_Widget_Utility::get_default_date(),
			$this->default_instance
		);
		/**
		 * WP_Widget_Custom_HTML
		 */
		$this->wp_widget_custom_html = new WP_Widget_Custom_HTML();
		/**
		 * 設定書き換え
		 */
		$this->wp_widget_custom_html->id_base         = $this->ys_widget_id;
		$this->wp_widget_custom_html->name            = $this->ys_widget_name;
		$this->wp_widget_custom_html->widget_options  = wp_parse_args(
			$this->ys_widget_ops,
			$this->wp_widget_custom_html->widget_options
		);
		$this->wp_widget_custom_html->control_options = wp_parse_args(
			$this->ys_control_ops,
			$this->wp_widget_custom_html->control_options
		);
	}

	/**
	 * ウィジェット出力
	 *
	 * @param array $args     args.
	 * @param array $instance instance.
	 */
	public function widget( $args, $instance ) {
		$instance = array_merge( $this->default_instance, $instance );
		if ( ! $this->is_during_the_period( $instance ) ) {
			return;
		}
		/**
		 * タクソノミー判断
		 */
		if ( ! $this->has_term( $instance ) ) {
			return;
		}

		$this->wp_widget_custom_html->widget( $args, $instance );
	}

	/**
	 * 設定保存
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 *
	 * @return array Settings to save or bool false to cancel saving.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance               = array_merge( $this->default_instance, $old_instance );
		$instance['title']      = sanitize_text_field( $new_instance['title'] );
		$instance['start_date'] = $this->sanitize_date( $new_instance['start_date'], $this->default_instance['start_date'] );
		$instance['start_time'] = $this->sanitize_time( $new_instance['start_time'], $this->default_instance['end_time'] );
		$instance['end_flag']   = $this->sanitize_checkbox( $new_instance['end_flag'] );
		$instance['end_date']   = $this->sanitize_date( $new_instance['end_date'], $this->default_instance['end_date'] );
		$instance['end_time']   = $this->sanitize_time( $new_instance['end_time'], $this->default_instance['end_time'] );
		if ( current_user_can( 'unfiltered_html' ) ) {
			$instance['content'] = $new_instance['content'];
		} else {
			$instance['content'] = wp_kses_post( $new_instance['content'] );
		}
		$instance['taxonomy'] = $new_instance['taxonomy'];

		return $instance;
	}

	/**
	 * ウィジェット設定フォーム
	 *
	 * @param array $instance Current instance.
	 *
	 * @returns void
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, $this->default_instance );
		?>
		<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" class="title sync-input" type="hidden" value="<?php echo esc_attr( $instance['title'] ); ?>"/>
		<textarea id="<?php echo $this->get_field_id( 'content' ); ?>" name="<?php echo $this->get_field_name( 'content' ); ?>" class="content sync-input" hidden><?php echo esc_textarea( $instance['content'] ); ?></textarea>
		<div class="ys-admin-section">
			<h4>掲載開始条件</h4>
			<p>
				<label for="<?php echo $this->get_field_id( 'start_date' ); ?>">開始日付</label>
				<input class="" id="<?php echo $this->get_field_id( 'start_date' ); ?>" name="<?php echo $this->get_field_name( 'start_date' ); ?>" type="date" value="<?php echo esc_attr( $instance['start_date'] ); ?>"/><br>
				<label for="<?php echo $this->get_field_id( 'start_time' ); ?>">開始時間</label>
				<input class="" id="<?php echo $this->get_field_id( 'start_time' ); ?>" name="<?php echo $this->get_field_name( 'start_time' ); ?>" type="time" value="<?php echo esc_attr( $instance['start_time'] ); ?>"/>
			</p>
		</div>
		<div class="ys-admin-section">
			<h4>掲載終了条件</h4>
			<p>
				<label for="<?php echo $this->get_field_id( 'end_flag' ); ?>">
					<input type="checkbox" id="<?php echo $this->get_field_id( 'end_flag' ); ?>" name="<?php echo $this->get_field_name( 'end_flag' ); ?>" value="1" <?php checked( $instance['end_flag'], 1 ); ?> />終了条件を有効にする</label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'end_date' ); ?>">終了日付</label>
				<input class="" id="<?php echo $this->get_field_id( 'end_date' ); ?>" name="<?php echo $this->get_field_name( 'end_date' ); ?>" type="date" value="<?php echo esc_attr( $instance['end_date'] ); ?>"/><br>
				<label for="<?php echo $this->get_field_id( 'end_time' ); ?>">終了時間</label>
				<input class="" id="<?php echo $this->get_field_id( 'end_time' ); ?>" name="<?php echo $this->get_field_name( 'end_time' ); ?>" type="time" value="<?php echo esc_attr( $instance['end_time'] ); ?>"/>
			</p>
		</div>
		<div class="ys-admin-section">
			<h4>掲載するカテゴリー・タグ</h4>
			<p>
				<?php $this->the_taxonomies_select_html( $this, $instance['taxonomy'] ); ?><br>
				<span class="ystandard-info--sub">※カテゴリー・タグを選択した場合、投稿詳細ページ かつ 該当のカテゴリー・タグをもつ投稿ページしか表示されません。（一覧ページなどでは表示されません）</span>
			</p>
		</div>
		<?php
	}

	/**
	 * Add hooks for enqueueing assets when registering all widget instances of this widget class.
	 *
	 * @param integer $number Optional. The unique order number of this widget instance
	 *                        compared to other instances of the same class. Default -1.
	 */
	public function _register_one( $number = - 1 ) {
		parent::_register_one( $number );
		$this->wp_widget_custom_html->_register_one( $number );
	}

	/**
	 * Filter gallery shortcode attributes.
	 *
	 * Prevents all of a site's attachments from being shown in a gallery displayed on a
	 * non-singular template where a $post context is not available.
	 *
	 * @param array $attrs Attributes.
	 *
	 * @return array Attributes.
	 */
	public function _filter_gallery_shortcode_attrs( $attrs ) {
		return $this->wp_widget_custom_html->_filter_gallery_shortcode_attrs( $attrs );
	}

	/**
	 * Loads the required scripts and styles for the widget control.
	 */
	public function enqueue_admin_scripts() {
		$this->wp_widget_custom_html->enqueue_admin_scripts();
	}
}