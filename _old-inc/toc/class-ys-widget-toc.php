<?php
/**
 * 目次
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

defined( 'ABSPATH' ) || die();

/**
 * 目次
 */
class YS_Widget_TOC extends WP_Widget {
	/**
	 * ウィジェットID
	 *
	 * @var string
	 */
	private $widget_id = 'ys_toc';
	/**
	 * ウィジェット名
	 *
	 * @var string
	 */
	private $widget_name = '[ys]目次';

	/**
	 * デフォルト設定
	 *
	 * @var array
	 */
	private $default_instance = [
		'title' => '',
	];

	/**
	 * ウィジェットオプション
	 *
	 * @var array
	 */
	public $widget_options = [
		'classname'                   => 'ys_toc',
		'description'                 => '目次を表示します',
		'customize_selective_refresh' => true,
	];

	/**
	 * コントロールオプション
	 *
	 * @var array
	 */
	public $control_options = [
		'width'  => 400,
		'height' => 350,
	];

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
		$this->default_instance = array_merge(
			$this->default_instance,
			\ystandard\TOC::SHORTCODE_ATTR
		);
	}

	/**
	 * ウィジェット出力
	 *
	 * @param array $args     args.
	 * @param array $instance instance.
	 */
	public function widget( $args, $instance ) {

		if ( ! isset( $instance['title'] ) ) {
			$instance['title'] = '';
			if ( is_null( $instance['title'] ) ) {
				$instance['title'] = '目次';
			}
		}
		$widget_title = '';
		if ( ! empty( $instance['title'] ) ) {
			$widget_title = $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}

		$shortcode = \ystandard\Utility::do_shortcode(
			\ystandard\TOC::SHORTCODE,
			[ 'title' => '' ],
			null,
			false
		);
		if ( $shortcode ) {
			echo $args['before_widget'];
			echo $widget_title;
			echo $shortcode;
			echo $args['after_widget'];
		}

	}

	/**
	 * ウィジェット設定フォーム
	 *
	 * @param array $instance Current instance.
	 *
	 * @returns void
	 */
	public function form( $instance ) {
		$instance = wp_parse_args(
			(array) $instance,
			$this->default_instance
		);
		$manual   = \ystandard\Admin::manual_link( 'manual/widget-toc' );
		?>
		<?php if ( ! empty( $manual ) ) : ?>
			<?php echo $manual; ?>
		<?php endif; ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'タイトル:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>">
		</p>
		<?php
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
		$instance['title'] = $new_instance['title'];

		return $instance;
	}
}
