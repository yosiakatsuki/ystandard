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
	 * WP_Widget_Custom_HTML
	 *
	 * @var WP_Widget_Custom_HTML
	 */
	private $wp_widget_custom_html;

	/**
	 * ウィジェットID
	 *
	 * @var string
	 */
	private $widget_id = 'ys_custom_html';
	/**
	 * ウィジェット名
	 *
	 * @var string
	 */
	private $widget_name = '[ys]yStandardカスタムHTML';

	/**
	 * ウィジェットオプション
	 *
	 * @var array
	 */
	public $widget_options = array(
		'classname'   => 'ys_widget_custom_html',
		'description' => '表示条件機能がついたyStandard拡張カスタムHTMLウィジェット',
	);
	/**
	 * コントロールオプション
	 *
	 * @var array
	 */
	public $control_options = array(
		'width'  => 400,
		'height' => 350,
	);

	/**
	 * Sets up a new Custom HTML widget instance.
	 */
	public function __construct() {
		parent::__construct(
			$this->widget_id,
			$this->widget_name,
			$this->widget_options,
			$this->control_options
		);
		/**
		 * 初期値セット
		 */
		$this->set_default_instance(
			array(
				'title'   => '',
				'content' => '',
			)
		);
		/**
		 * WP_Widget_Custom_HTML
		 */
		$this->wp_widget_custom_html = new WP_Widget_Custom_HTML();
		/**
		 * 設定書き換え
		 */
		$this->wp_widget_custom_html->id_base         = $this->widget_id;
		$this->wp_widget_custom_html->name            = $this->widget_name;
		$this->wp_widget_custom_html->widget_options  = wp_parse_args(
			$this->widget_options,
			$this->wp_widget_custom_html->widget_options
		);
		$this->wp_widget_custom_html->control_options = wp_parse_args(
			$this->control_options,
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
		/**
		 * テーマ独自設定条件の確認
		 */
		if ( ! $this->is_active_ystandard_widget( $instance ) ) {
			return;
		}
		/**
		 * ウィジェット内容表示
		 */
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
		/**
		 * 共通設定保存
		 */
		$instance = $this->update_base_options( $new_instance, $old_instance );

		if ( current_user_can( 'unfiltered_html' ) ) {
			$instance['content'] = $new_instance['content'];
		} else {
			$instance['content'] = wp_kses_post( $new_instance['content'] );
		}

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
		<?php
		/**
		 * 共通設定
		 */
		$this->form_ys_advanced( $instance );
	}

	/**
	 * Add hooks for enqueueing assets when registering all widget instances of this widget class.
	 *
	 * @param integer $number Optional. The unique order number of this widget instance
	 *                        compared to other instances of the same class. Default -1.
	 */
	public function _register_one( $number = - 1 ) {
		parent::_register_one( $number );
		wp_add_inline_script( 'custom-html-widgets', sprintf( 'wp.customHtmlWidgets.idBases.push( %s );', wp_json_encode( $this->id_base ) ) );
		add_action( 'admin_print_scripts-widgets.php', array( $this, 'enqueue_admin_scripts' ) );
		add_action( 'admin_footer-widgets.php', array( 'WP_Widget_Custom_HTML', 'render_control_template_scripts' ) );
		add_action( 'admin_head-widgets.php', array( 'WP_Widget_Custom_HTML', 'add_help_text' ) );
	}

	/**
	 * Loads the required scripts and styles for the widget control.
	 */
	public function enqueue_admin_scripts() {
		$this->wp_widget_custom_html->enqueue_admin_scripts();
	}
}