<?php
/**
 * プロフィールボックス
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * プロフィールボックス
 */
class YS_Widget_Profile_Box extends YS_Widget_Base {
	/**
	 * Default instance.
	 *
	 * @var array
	 */
	protected $default_instance = array(
		'title'             => '',
		'default_user_name' => '',
		'user_name'         => '',
	);

	/**
	 * ウィジェットID
	 *
	 * @var string
	 */
	private $ys_widget_id = 'ys_profile_box';
	/**
	 * ウィジェット名
	 *
	 * @var string
	 */
	private $ys_widget_name = '[ys]著者情報表示';

	/**
	 * ウィジェットオプション
	 *
	 * @var array
	 */
	private $ys_widget_ops = array(
		'classname'                   => 'ys_profile_box',
		'description'                 => '著者情報を表示します',
		'customize_selective_refresh' => true,
	);

	/**
	 * Sets up a new Custom HTML widget instance.
	 */
	public function __construct() {
		parent::__construct(
			$this->ys_widget_id,
			$this->ys_widget_name,
			$this->ys_widget_ops
		);
	}

	/**
	 * ウィジェット出力
	 *
	 * @param array $args     args.
	 * @param array $instance instance.
	 */
	public function widget( $args, $instance ) {
		$instance = wp_parse_args( $instance, $this->default_instance );
		/**
		 * 変数
		 */
		$default_user_name = '';
		$user_name         = '';
		/**
		 * 設定取得
		 */
		if ( '' !== $instance['default_user_name'] ) {
			$default_user_name = ' default_user_name="' . $instance['default_user_name'] . '"';
		}
		if ( '' !== $instance['user_name'] ) {
			$user_name = ' user_name="' . $instance['user_name'] . '"';
		}

		echo $args['before_widget'];
		/**
		 * ウィジェットタイトル
		 */
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}

		/**
		 * ショートコード実行
		 */
		echo do_shortcode(
			sprintf(
				'[ys_author%s%s]',
				$default_user_name,
				$user_name
			)
		);
		echo $args['after_widget'];
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
		$new_instance = wp_parse_args( $new_instance, $this->default_instance );

		$instance = $old_instance;

		$instance['title']             = sanitize_text_field( $new_instance['title'] );
		$instance['default_user_name'] = $this->sanitize_author( $new_instance['default_user_name'] );
		$instance['user_name']         = $this->sanitize_author( $new_instance['user_name'] );

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
		$instance  = wp_parse_args(
			(array) $instance,
			$this->default_instance
		);
		$user_list = $this->get_author_list();
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'タイトル:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>">
		</p>
		<div class="ys-admin-section">
			<h4>デフォルトユーザー</h4>
			<p><span class="ystandard-info--sub">※TOPページや一覧ページなどに表示するユーザー</span></p>
			<select name="<?php echo $this->get_field_name( 'default_user_name' ); ?>">
				<option value="">選択して下さい</option>
				<?php foreach ( $user_list as $key => $value ) : ?>
					<option
						value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $instance['default_user_name'] ); ?>><?php echo $value; ?>
					</option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="ys-admin-section">
			<h4>特定のユーザーの表示</h4>
			<p><span class="ystandard-info--sub">※特定のユーザー情報を常に表示する場合は選択して下さい。</span><br><span class="ystandard-info--sub">※デフォルトユーザーと特定のユーザーの両方を指定している場合、特定ユーザーの表示が優先されます。</span></p>
			
			<select name="<?php echo $this->get_field_name( 'user_name' ); ?>">
				<option value="">選択して下さい</option>
				<?php foreach ( $user_list as $key => $value ) : ?>
					<option
						value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $instance['user_name'] ); ?>><?php echo $value; ?>
					</option>
				<?php endforeach; ?>
			</select>
		</div>
		<?php
	}

	/**
	 * ユーザーリストの作成
	 *
	 * @return array
	 */
	private function get_author_list() {
		$user_query = new WP_User_Query(
			array(
				'role__not_in' => 'Subscriber',
			)
		);
		$user_list  = array();
		$users      = $user_query->get_results();
		foreach ( $users as $user ) {
			$user_list[ $user->data->user_login ] = $user->data->display_name;
		}

		return $user_list;
	}

	/**
	 * ユーザー情報のサニタイズ
	 *
	 * @param string $user_name ユーザー名.
	 *
	 * @return string
	 */
	private function sanitize_author( $user_name ) {
		$user_list = $this->get_author_list();
		if ( array_key_exists( $user_name, $user_list ) ) {
			return $user_name;
		} else {
			return '';
		}
	}
}