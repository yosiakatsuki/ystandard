<?php
/**
 * 広告表示用テキストウィジェット
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 広告表示用テキストウィジェット
 */
class YS_Widget_Advertisement extends YS_Widget_Base {

	/**
	 * ウィジェットID
	 *
	 * @var string
	 */
	private $widget_id = 'ys_widget_advertisement';
	/**
	 * ウィジェット名
	 *
	 * @var string
	 */
	private $widget_name = '[ys]広告表示用テキストウィジェット';

	/**
	 * ウィジェットオプション
	 *
	 * @var array
	 */
	public $widget_options = array(
		'classname'   => 'ys_widget_advertisement',
		'description' => '404ページと結果0件の検索ページに出力しないテキストウィジェット',
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
		$this->set_default_instance(
			array(
				'title' => YS_Shortcode_Advertisement::TITLE,
				'text'  => '',
			)
		);
	}

	/**
	 * ウィジェットのフロントエンド表示
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     ウィジェットの引数.
	 * @param array $instance データベースの保存値.
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		/**
		 * ショートコード実行
		 */
		ys_do_shortcode(
			'ys_ad_block',
			array(
				'class' => 'widget ys-ad-widget',
			),
			$instance['text'],
			true
		);
		echo $args['after_widget'];
	}

	/**
	 * バックエンドのウィジェットフォーム
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance データベースからの前回保存された値.
	 */
	public function form( $instance ) {
		$instance = wp_parse_args(
			(array) $instance,
			$this->default_instance
		);
		?>
		<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" class="title sync-input" type="hidden" value="<?php echo esc_attr( $instance['title'] ); ?>">
		<label for="<?php echo $this->get_field_id( 'text' ); ?>">内容:</label>
		<textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>"><?php echo esc_textarea( $instance['text'] ); ?></textarea>
		<?php
		/**
		 * 共通設定
		 */
		$this->form_ys_advanced( $instance );
	}

	/**
	 * ウィジェットフォームの値を保存用にサニタイズ
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance 保存用に送信された値.
	 * @param array $old_instance データベースからの以前保存された値.
	 *
	 * @return array 保存される更新された安全な値
	 */
	public function update( $new_instance, $old_instance ) {
		/**
		 * 共通設定保存
		 */
		$instance = $this->update_base_options( $new_instance, $old_instance );
		if ( current_user_can( 'unfiltered_html' ) ) {
			$instance['text'] = $new_instance['text'];
		} else {
			$instance['text'] = wp_kses_post( $new_instance['text'] );
		}

		return $instance;
	}
}
