<?php
/**
 * 広告表示用テキストウィジェット
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 広告表示用テキストウィジェット
 */
class YS_AD_Text_Widget extends WP_Widget {
	/**
	 * WordPress でウィジェットを登録
	 */
	function __construct() {
		parent::__construct(
			'ys_ad_text_widget',
			'[ys]広告表示用テキストウィジェット',
			array( 'description' => '404ページと結果0件の検索ページに出力しないテキストウィジェット' )
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
		global $wp_query;
		$text = ! empty( $instance['text'] ) ? $instance['text'] : '';
		if ( '' === $text ) {
			return;
		}
		$text = apply_filters( 'ys_advertisement_content', $text );
		$html = do_shortcode( '[ys_ad_block class="widget ys-ad-widget"]' . $text . '[/ys_ad_block]' );
		$html = apply_filters( 'ys_ad_text_widget_text', $html, $instance, $this );
		echo $html;
	}

	/**
	 * バックエンドのウィジェットフォーム
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance データベースからの前回保存された値.
	 */
	public function form( $instance ) {
		$text = ! empty( $instance['text'] ) ? $instance['text'] : '';
		?><p>
				<label for="<?php echo $this->get_field_id( 'text' ); ?>">内容:</label>
				<textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>"><?php echo esc_textarea( $text ); ?></textarea>
			</p>
		<?php
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
		$instance = array();
		if ( current_user_can( 'unfiltered_html' ) ) {
			$instance['text'] = $new_instance['text'];
		} else {
			$instance['text'] = wp_kses_post( $new_instance['text'] );
		}
		return $instance;
	}
}
