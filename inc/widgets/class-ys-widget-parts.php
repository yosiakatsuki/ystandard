<?php
/**
 * [ys]パーツ表示ウィジェット
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * Class YS_Widget_Parts
 */
class YS_Widget_Parts extends YS_Widget_Base {

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
	public $widget_options = array(
		'classname'   => 'ys_widget_parts',
		'description' => '[ys]パーツで作成した内容を表示するウィジェット',
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
	 * WordPress でウィジェットを登録
	 */
	function __construct() {
		parent::__construct(
			$this->widget_id,
			$this->widget_name,
			$this->widget_options,
			$this->control_options
		);
		/**
		 * 初期値セット
		 */
		$this->set_default_instance( YS_Shortcode_Parts::SHORTCODE_PARAM );
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
		$sc_result = ys_do_shortcode(
			'ys_parts',
			array_merge(
				$this->default_instance,
				$instance,
				array( 'use_entry_content' => '1' )
			),
			null,
			false
		);
		if ( $sc_result ) {
			echo $args['before_widget'];
			echo $sc_result;
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
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'parts_id' ); ?>">表示するパーツ：</label>
			<span style="display: block;margin-top: .25em;">
			<?php
			wp_dropdown_pages(
				array(
					'name'              => $this->get_field_name( 'parts_id' ),
					'id'                => $this->get_field_id( 'parts_id' ),
					'echo'              => true,
					'show_option_none'  => __( '&mdash; Select &mdash;' ),
					'option_none_value' => '0',
					'selected'          => $instance['parts_id'],
					'post_type'         => 'ys-parts',
					'post_status'       => 'publish',
				)
			)
			?>
			</span>
		</p>

		<?php
		/**
		 * 共通設定
		 */
		$this->form_ys_advanced( $instance );
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
		/**
		 * 共通設定保存
		 */
		$instance             = $this->update_base_options( $new_instance, $old_instance );
		$instance['parts_id'] = $new_instance['parts_id'];

		return $instance;
	}
}
