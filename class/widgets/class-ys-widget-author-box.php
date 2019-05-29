<?php
/**
 * 投稿者ボックス
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 投稿者ボックス
 */
class YS_Widget_Author_Box extends YS_Widget_Base {
	/**
	 * ウィジェットID
	 *
	 * @var string
	 */
	private $widget_id = 'ys_profile_box';
	/**
	 * ウィジェット名
	 *
	 * @var string
	 */
	private $widget_name = '[ys]著者情報表示';

	/**
	 * ウィジェットオプション
	 *
	 * @var array
	 */
	public $widget_options = array(
		'classname'                   => 'ys_profile_box',
		'description'                 => '著者情報を表示します',
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
			array_merge(
				YS_Shortcode_Author_Box::SHORTCODE_PARAM,
				array( 'title' => '' )
			)
		);
	}

	/**
	 * ウィジェット出力
	 *
	 * @param array $args     args.
	 * @param array $instance instance.
	 */
	public function widget( $args, $instance ) {

		$author_name_tag = 'h2';
		$widget_title    = '';
		if ( ! empty( $instance['title'] ) ) {
			$widget_title = $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
			/**
			 * タイトルがあれば投稿者はh3で作成
			 */
			$author_name_tag = 'h3';
		}
		/**
		 * ショートコード用にパラメーター調整
		 */
		$instance['title']           = '';
		$instance['author_name_tag'] = $author_name_tag;
		/**
		 * ショートコード実行
		 */
		$sc_result = ys_do_shortcode(
			'ys_author',
			array_merge( $this->default_instance, $instance ),
			null,
			false
		);
		if ( $sc_result ) {
			echo $args['before_widget'];
			echo $widget_title;
			echo $sc_result;
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
		/**
		 * ユーザーリストの作成
		 */
		$user_list = $this->get_author_list();
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'タイトル:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>">
		</p>
		<div class="ys-admin-section">
			<h4>デフォルトユーザー</h4>
			<p>
				<select name="<?php echo $this->get_field_name( 'default_user_name' ); ?>">
					<option value="">選択して下さい</option>
					<?php foreach ( $user_list as $key => $value ) : ?>
						<option
							value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $instance['default_user_name'] ); ?>><?php echo $value; ?>
						</option>
					<?php endforeach; ?>
				</select><br>
				<span class="ystandard-info--sub">※TOPページや一覧ページなどに表示するユーザー</span>
			</p>
		</div>
		<div class="ys-admin-section">
			<h4>特定のユーザーの表示</h4>
			<p>
				<select name="<?php echo $this->get_field_name( 'user_name' ); ?>">
					<option value="">選択して下さい</option>
					<?php foreach ( $user_list as $key => $value ) : ?>
						<option
							value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $instance['user_name'] ); ?>><?php echo $value; ?>
						</option>
					<?php endforeach; ?>
				</select><br>
				<span class="ystandard-info--sub">※特定のユーザー情報を常に表示する場合は選択して下さい。</span><br><span class="ystandard-info--sub">※デフォルトユーザーと特定のユーザーの両方を指定している場合、特定ユーザーの表示が優先されます。</span>
			</p>
		</div>
		<div class="ys-admin-section">
			<h4>レイアウト</h4>
			<label>
				<input type="radio" name="<?php echo $this->get_field_name( 'layout' ); ?>" value="horizon" <?php checked( $instance['layout'], 'horizon' ); ?>>レスポンシブ
			</label>
			<label>
				<input type="radio" name="<?php echo $this->get_field_name( 'layout' ); ?>" value="vertical" <?php checked( $instance['layout'], 'vertical' ); ?>>縦一列
			</label><br>
			<span class="ystandard-info--sub">※サイドバーなどに表示する場合は「縦一列」がおすすめです。</span>
		</div>
		<div class="ys-admin-section">
			<h4>リンク設定</h4>
			<p>
				<label for="<?php echo $this->get_field_id( 'enable_archive_link' ); ?>">
					<input type="checkbox" id="<?php echo $this->get_field_id( 'enable_archive_link' ); ?>" name="<?php echo $this->get_field_name( 'enable_archive_link' ); ?>" value="1" <?php checked( $this->sanitize_checkbox( $instance['enable_archive_link'] ), true ); ?> />投稿者の記事一覧ページへのリンクを作成する</label><br>
				<label for="<?php echo $this->get_field_id( 'enable_archive_button' ); ?>">
					<input type="checkbox" id="<?php echo $this->get_field_id( 'enable_archive_button' ); ?>" name="<?php echo $this->get_field_name( 'enable_archive_button' ); ?>" value="1" <?php checked( $this->sanitize_checkbox( $instance['enable_archive_button'] ), true ); ?> />「記事一覧」ボタンを表示する</label>
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
		 * Author Box 設定
		 */
		$instance['default_user_name']     = $this->sanitize_author( $new_instance['default_user_name'] );
		$instance['user_name']             = $this->sanitize_author( $new_instance['user_name'] );
		$instance['layout']                = $this->sanitize_layout( $new_instance['layout'] );
		$instance['enable_archive_link']   = $this->sanitize_checkbox( $new_instance['enable_archive_link'] );
		$instance['enable_archive_button'] = $this->sanitize_checkbox( $new_instance['enable_archive_button'] );

		return $instance;
	}

	/**
	 * ユーザーリストの作成
	 *
	 * @return array
	 */
	private function get_author_list() {
		/**
		 * クエリの作成
		 */
		$user_query = new WP_User_Query(
			array(
				'role__not_in' => 'Subscriber',
			)
		);
		/**
		 * ユーザーリスト作成
		 */
		$user_list = array();
		$users     = $user_query->get_results();
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

	/**
	 * レイアウト設定のサニタイズ
	 *
	 * @param string $layout レイアウト.
	 *
	 * @return string
	 */
	private function sanitize_layout( $layout ) {

		if ( 'vertical' === $layout ) {
			return $layout;
		}

		return 'horizon';
	}
}