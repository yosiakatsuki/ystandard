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
class YS_Widget_Advertisement extends WP_Widget {

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
	 * デフォルト設定
	 *
	 * @var array
	 */
	private $default_instance = [
		'text' => '',
	];

	/**
	 * ウィジェットオプション
	 *
	 * @var array
	 */
	public $widget_options = [
		'classname'                   => 'ys_widget_advertisement',
		'description'                 => '404ページと結果0件の検索ページに出力しないテキストウィジェット',
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
		$this->default_instance = array_merge(
			$this->default_instance,
			\ystandard\Advertisement::SHORTCODE_ATTR
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
		// []の変換.
		$instance['text'] = strtr(
			$instance['text'],
			[
				'[' => '&#x5b;',
				']' => '&#x5d;',
			]
		);
		// ショートコード準備.
		$content = $instance['text'];
		unset( $instance['text'] );
		/**
		 * ショートコード実行
		 */
		$shortcode = \ystandard\Utility::do_shortcode(
			'ys_ad_block',
			$instance,
			$content,
			false
		);
		if ( $shortcode ) {
			echo $args['before_widget'];
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
		$manual   = \ystandard\Admin::manual_link( 'manual/widget-advertisement' );
		?>
		<?php if ( ! empty( $manual ) ) : ?>
			<p><?php echo $manual; ?></p>
		<?php endif; ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'タイトル:' ); ?></label>
			<input class="widefat sync-input" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'text' ); ?>">内容:</label>
			<textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>"><?php echo esc_textarea( $instance['text'] ); ?></textarea>
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
		$instance['title'] = $new_instance['title'];
		if ( current_user_can( 'unfiltered_html' ) ) {
			$instance['text'] = $new_instance['text'];
		} else {
			$instance['text'] = wp_kses_post( $new_instance['text'] );
		}

		return $instance;
	}
}
