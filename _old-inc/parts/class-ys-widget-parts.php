<?php
/**
 * [ys]パーツ表示ウィジェット
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

defined( 'ABSPATH' ) || die();

/**
 * Class YS_Widget_Parts
 */
class YS_Widget_Parts extends WP_Widget {

	/**
	 * ウィジェットID
	 *
	 * @var string
	 */
	private $widget_id = 'ys_widget_parts';
	/**
	 * ウィジェット名
	 *
	 * @var string
	 */
	private $widget_name = '[ys]パーツ表示ウィジェット';

	/**
	 * ウィジェットオプション
	 *
	 * @var array
	 */
	public $widget_options = [
		'classname'   => 'ys_widget_parts',
		'description' => '[ys]パーツで作成した内容を表示するウィジェット',
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
	 * デフォルト設定
	 *
	 * @var array
	 */
	private $default_instance = [
		'parts_id'          => 0,
		'use_entry_content' => true,
		'title'             => '',
	];

	/**
	 * WordPress でウィジェットを登録
	 */
	function __construct() {
		parent::__construct(
			$this->widget_id,
			$this->widget_name,
			$this->widget_options,
			$this->control_options
		);
	}

	/**
	 * ウィジェットのフロントエンド表示
	 *
	 * @param array $args     ウィジェットの引数.
	 * @param array $instance データベースの保存値.
	 *
	 * @see WP_Widget::widget()
	 */
	public function widget( $args, $instance ) {
		/**
		 * ショートコード実行
		 */
		$shortcode = \ystandard\Utility::do_shortcode(
			\ystandard\Parts::SHORTCODE,
			array_merge(
				$this->default_instance,
				$instance
			),
			null,
			false
		);
		if ( $shortcode ) {
			echo $args['before_widget'];

			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
			}
			echo $shortcode;
			echo $args['after_widget'];
		}
	}

	/**
	 * バックエンドのウィジェットフォーム
	 *
	 * @param array $instance データベースからの前回保存された値.
	 *
	 * @see WP_Widget::form()
	 */
	public function form( $instance ) {
		$instance = wp_parse_args(
			(array) $instance,
			$this->default_instance
		);
		$manual   = \ystandard\Admin::manual_link( 'manual/contents-template' );
		?>
		<?php if ( ! empty( $manual ) ) : ?>
			<?php echo $manual; ?>
		<?php endif; ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'タイトル:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'parts_id' ); ?>">表示するパーツ：</label>
			<span class="widget-form__section">
			<?php
			$html = wp_dropdown_pages(
				[
					'name'              => $this->get_field_name( 'parts_id' ),
					'id'                => $this->get_field_id( 'parts_id' ),
					'echo'              => false,
					'show_option_none'  => __( '&mdash; Select &mdash;' ),
					'option_none_value' => '0',
					'selected'          => $instance['parts_id'],
					'post_type'         => 'ys-parts',
					'post_status'       => 'publish',
					'sort_column'       => 'post_date',
					'sort_order'        => 'DESC',
				]
			);
			if ( $html ) {
				echo $html;
			} else {
				echo '<span>[ys]パーツがありません。</span>';
			}
			?>
			</span>
		</p>
		<?php
	}

	/**
	 * ウィジェットフォームの値を保存用にサニタイズ
	 *
	 * @param array $new_instance 保存用に送信された値.
	 * @param array $old_instance データベースからの以前保存された値.
	 *
	 * @return array 保存される更新された安全な値
	 * @see WP_Widget::update()
	 */
	public function update( $new_instance, $old_instance ) {

		$instance['title']    = $new_instance['title'];
		$instance['parts_id'] = $new_instance['parts_id'];

		return $instance;
	}
}
