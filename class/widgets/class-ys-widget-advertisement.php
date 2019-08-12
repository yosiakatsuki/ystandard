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
			array_merge(
				YS_Shortcode_Advertisement::SHORTCODE_PARAM,
				array( 'class' => 'ys-ad-widget' )
			)
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
		// []の変換
		$instance['text'] = strtr(
			$instance['text'],
			array(
				'[' => '&#x5b;',
				']' => '&#x5d;',
			)
		);
		/**
		 * ショートコード実行
		 */
		$sc_result = ys_do_shortcode(
			'ys_ad_block',
			array_merge( $this->default_instance, $instance ),
			$instance['text'],
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
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'タイトル:' ); ?></label>
			<input class="widefat sync-input" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'text' ); ?>">内容:</label>
			<textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>"><?php echo esc_textarea( $instance['text'] ); ?></textarea>
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
		$instance = $this->update_base_options( $new_instance, $old_instance );
		if ( current_user_can( 'unfiltered_html' ) ) {
			$instance['text'] = $new_instance['text'];
		} else {
			$instance['text'] = wp_kses_post( $new_instance['text'] );
		}

		return $instance;
	}
}
