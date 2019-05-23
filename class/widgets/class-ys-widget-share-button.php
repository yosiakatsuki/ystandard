<?php
/**
 * シェアボタン
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * シェアボタン
 */
class YS_Widget_Share_Button extends YS_Widget_Base {
	/**
	 * Default instance.
	 *
	 * @var array
	 */
	protected $default_instance = array(
		'title'                => '',
		'twitter'              => true,
		'facebook'             => true,
		'hatenabookmark'       => true,
		'pocket'               => true,
		'line'                 => true,
		'feedly'               => true,
		'rss'                  => true,
		'col_sp'               => 3,
		'col_tablet'           => 3,
		'col_pc'               => 6,
		'twitter_via_user'     => '',
		'twitter_related_user' => '',
	);

	/**
	 * ウィジェットID
	 *
	 * @var string
	 */
	private $widget_id = 'ys_share_button';
	/**
	 * ウィジェット名
	 *
	 * @var string
	 */
	private $widget_name = '[ys]シェアボタン';

	/**
	 * ウィジェットオプション
	 *
	 * @var array
	 */
	public $widget_options = array(
		'classname'                   => 'ys_share_button',
		'description'                 => 'シェアボタンを表示します',
		'customize_selective_refresh' => true,
	);

	/**
	 * Sets up a new Custom HTML widget instance.
	 */
	public function __construct() {
		parent::__construct(
			$this->widget_id,
			$this->widget_name,
			$this->widget_options
		);
		/**
		 * 初期値セット
		 */
		$this->set_default_instance(
			$this->default_instance
		);
	}

	/**
	 * ウィジェット出力
	 *
	 * @param array $args     args.
	 * @param array $instance instance.
	 */
	public function widget( $args, $instance ) {

		echo $args['before_widget'];
		/**
		 * ショートコード実行
		 */
		ys_do_shortcode(
			'ys_share_button',
			array(
				'title'                => $instance['title'],
				'twitter'              => $instance['twitter'],
				'facebook'             => $instance['facebook'],
				'hatenabookmark'       => $instance['hatenabookmark'],
				'pocket'               => $instance['pocket'],
				'line'                 => $instance['line'],
				'feedly'               => $instance['feedly'],
				'rss'                  => $instance['rss'],
				'col_sp'               => $instance['col_sp'],
				'col_tablet'           => $instance['col_tablet'],
				'col_pc'               => $instance['col_pc'],
				'twitter_via_user'     => $instance['twitter_via_user'],
				'twitter_related_user' => $instance['twitter_related_user'],
			)
		);
		echo $args['after_widget'];
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
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'タイトル:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>">
		</p>
		<div class="ys-admin-section">
			<h4>表示するシェアボタン</h4>
			<p>
				<label for="<?php echo $this->get_field_id( 'twitter' ); ?>">
					<input type="checkbox" id="<?php echo $this->get_field_id( 'twitter' ); ?>" name="<?php echo $this->get_field_name( 'twitter' ); ?>" value="1" <?php checked( $this->sanitize_checkbox( $instance['twitter'] ), true ); ?> />Twitter</label><br>
				<label for="<?php echo $this->get_field_id( 'facebook' ); ?>">
					<input type="checkbox" id="<?php echo $this->get_field_id( 'facebook' ); ?>" name="<?php echo $this->get_field_name( 'facebook' ); ?>" value="1" <?php checked( $this->sanitize_checkbox( $instance['facebook'] ), true ); ?> />Facebook</label><br>
				<label for="<?php echo $this->get_field_id( 'hatenabookmark' ); ?>">
					<input type="checkbox" id="<?php echo $this->get_field_id( 'hatenabookmark' ); ?>" name="<?php echo $this->get_field_name( 'hatenabookmark' ); ?>" value="1" <?php checked( $this->sanitize_checkbox( $instance['hatenabookmark'] ), true ); ?> />はてなブックマーク</label><br>
				<label for="<?php echo $this->get_field_id( 'pocket' ); ?>">
					<input type="checkbox" id="<?php echo $this->get_field_id( 'pocket' ); ?>" name="<?php echo $this->get_field_name( 'pocket' ); ?>" value="1" <?php checked( $this->sanitize_checkbox( $instance['pocket'] ), true ); ?> />Pocket</label><br>
				<label for="<?php echo $this->get_field_id( 'line' ); ?>">
					<input type="checkbox" id="<?php echo $this->get_field_id( 'line' ); ?>" name="<?php echo $this->get_field_name( 'line' ); ?>" value="1" <?php checked( $this->sanitize_checkbox( $instance['line'] ), true ); ?> />LINE</label><br>
				<label for="<?php echo $this->get_field_id( 'feedly' ); ?>">
					<input type="checkbox" id="<?php echo $this->get_field_id( 'feedly' ); ?>" name="<?php echo $this->get_field_name( 'feedly' ); ?>" value="1" <?php checked( $this->sanitize_checkbox( $instance['feedly'] ), true ); ?> />Feedly</label><br>
				<label for="<?php echo $this->get_field_id( 'rss' ); ?>">
					<input type="checkbox" id="<?php echo $this->get_field_id( 'rss' ); ?>" name="<?php echo $this->get_field_name( 'rss' ); ?>" value="1" <?php checked( $this->sanitize_checkbox( $instance['rss'] ), true ); ?> />RSS</label><br>
			</p>
		</div>
		<div class="ys-admin-section">
			<h4>Twitter詳細設定</h4>
			<p>
				<label for="<?php echo $this->get_field_id( 'twitter_via_user' ); ?>">投稿ユーザー（via）アカウント名</label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'twitter_via_user' ); ?>" name="<?php echo $this->get_field_name( 'twitter_via_user' ); ?>" type="text" value="<?php echo esc_attr( $instance['twitter_via_user'] ); ?>"><br>
				<span class="ystandard-info--sub">※「@」なしのTwitterユーザー名を入力して下さい。<br>例：Twitterユーザー名…「@yosiakatsuki」→入力…「yosiakatsuki」<br>未入力の場合viaは設定されません。</span>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'twitter_related_user' ); ?>">おすすめアカウント名</label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'twitter_related_user' ); ?>" name="<?php echo $this->get_field_name( 'twitter_related_user' ); ?>" type="text" value="<?php echo esc_attr( $instance['twitter_related_user'] ); ?>"><br>
				<span class="ystandard-info--sub">※ツイート後に表示するおすすめアカウントの設定。<br>「@」なしのTwitterユーザー名を入力して下さい。<br>例：Twitterユーザー名…「@yosiakatsuki」→入力…「yosiakatsuki」<br>複数のアカウントをおすすめ表示する場合はカンマで区切って下さい。<br>未入力の場合おすすめアカウントは設定されません。</span>
			</p>
		</div>
		<div class="ys-admin-section">
			<h4>表示列数設定</h4>
			<p>
				<label for="<?php echo $this->get_field_id( 'col_sp' ); ?>">スマートフォン</label>
				<input class="tiny-text" id="<?php echo $this->get_field_id( 'col_sp' ); ?>" name="<?php echo $this->get_field_name( 'col_sp' ); ?>" type="number" value="<?php echo esc_attr( $instance['col_sp'] ); ?>" min="1" max="6" size="3"><br>
				<label for="<?php echo $this->get_field_id( 'col_tablet' ); ?>">タブレット</label>
				<input class="tiny-text" id="<?php echo $this->get_field_id( 'col_tablet' ); ?>" name="<?php echo $this->get_field_name( 'col_tablet' ); ?>" type="number" value="<?php echo esc_attr( $instance['col_tablet'] ); ?>" min="1" max="6" size="3"><br>
				<label for="<?php echo $this->get_field_id( 'col_pc' ); ?>">PC</label>
				<input class="tiny-text" id="<?php echo $this->get_field_id( 'col_pc' ); ?>" name="<?php echo $this->get_field_name( 'col_pc' ); ?>" type="number" value="<?php echo esc_attr( $instance['col_pc'] ); ?>" min="1" max="6" size="3">
			</p>
		</div>
		<?php
		/**
		 * 共通設定
		 */
		$this->form_ys_advanced( $instance );
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
		/**
		 * シェアボタン
		 */
		$instance['twitter']              = $this->sanitize_checkbox( $new_instance['twitter'] );
		$instance['facebook']             = $this->sanitize_checkbox( $new_instance['facebook'] );
		$instance['hatenabookmark']       = $this->sanitize_checkbox( $new_instance['hatenabookmark'] );
		$instance['pocket']               = $this->sanitize_checkbox( $new_instance['pocket'] );
		$instance['line']                 = $this->sanitize_checkbox( $new_instance['line'] );
		$instance['feedly']               = $this->sanitize_checkbox( $new_instance['feedly'] );
		$instance['rss']                  = $this->sanitize_checkbox( $new_instance['rss'] );
		$instance['twitter_via_user']     = esc_attr( $new_instance['twitter_via_user'] );
		$instance['twitter_related_user'] = esc_attr( $new_instance['twitter_related_user'] );
		$instance['col_sp']               = $this->sanitize_col( $new_instance['col_sp'] );
		$instance['col_tablet']           = $this->sanitize_col( $new_instance['col_tablet'] );
		$instance['col_pc']               = $this->sanitize_col( $new_instance['col_pc'] );

		return $instance;
	}

	/**
	 * 列数設定のサニタイズ
	 *
	 * @param string $value 列数.
	 *
	 * @return int
	 */
	private function sanitize_col( $value ) {

		if ( 1 > $value ) {
			return 1;
		}
		if ( 6 < $value ) {
			return 6;
		}

		return $value;
	}
}