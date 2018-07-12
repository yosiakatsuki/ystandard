<?php
/**
 * 表示表示付きテキスト
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 表示条件付きテキスト
 */
class YS_Widget_Text extends YS_Widget_Base {
	/**
	 * Whether or not the widget has been registered yet.
	 *
	 * @var bool
	 */
	protected $registered = false;

	/**
	 * Default instance.
	 *
	 * @var array
	 */
	protected $default_instance = array(
		'title'      => '',
		'text'       => '',
		'filter'     => false, // For back-compat.
		'visual'     => null, // Must be explicitly defined.
		'taxonomy'   => '',
		'start_date' => '',
		'start_time' => '',
		'end_flag'   => 0,
		'end_date'   => '',
		'end_time'   => '',
	);

	/**
	 * WP_Widget_Text
	 *
	 * @var WP_Widget_Text
	 */
	private $wp_widget_text;

	/**
	 * ウィジェットID
	 *
	 * @var string
	 */
	private $ys_widget_id = 'ys_text';
	/**
	 * ウィジェット名
	 *
	 * @var string
	 */
	private $ys_widget_name = '[ys]表示条件付きテキスト';

	/**
	 * ウィジェットオプション
	 *
	 * @var array
	 */
	private $ys_widget_ops = array(
		'classname'                   => 'ys_widget_text',
		'description'                 => '表示条件付きテキスト',
		'customize_selective_refresh' => true,
	);
	/**
	 * コントロールオプション
	 *
	 * @var array
	 */
	private $ys_control_ops = array(
		'width'  => 400,
		'height' => 350,
	);

	/**
	 * Sets up a new Custom HTML widget instance.
	 */
	public function __construct() {
		parent::__construct(
			$this->ys_widget_id,
			$this->ys_widget_name,
			$this->ys_widget_ops,
			$this->ys_control_ops
		);
		/**
		 * 初期値更新
		 */
		$this->default_instance = wp_parse_args(
			YS_Widget_Utility::get_default_date(),
			$this->default_instance
		);
		/**
		 * WP_Widget_Text
		 */
		$this->wp_widget_text = new WP_Widget_Text();
		/**
		 * 設定書き換え
		 */
		$this->wp_widget_text->id_base         = $this->ys_widget_id;
		$this->wp_widget_text->name            = $this->ys_widget_name;
		$this->wp_widget_text->widget_options  = wp_parse_args(
			$this->ys_widget_ops,
			$this->wp_widget_text->widget_options
		);
		$this->wp_widget_text->control_options = wp_parse_args(
			$this->ys_control_ops,
			$this->wp_widget_text->control_options
		);
	}

	/**
	 * ウィジェット出力
	 *
	 * @param array $args     args.
	 * @param array $instance instance.
	 */
	public function widget( $args, $instance ) {
		$instance = array_merge( $this->default_instance, $instance );
		if ( ! $this->is_during_the_period( $instance ) ) {
			return;
		}
		/**
		 * タクソノミー判断
		 */
		if ( ! $this->has_term( $instance ) ) {
			return;
		}

		$this->wp_widget_text->widget( $args, $instance );
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

		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		if ( current_user_can( 'unfiltered_html' ) ) {
			$instance['text'] = $new_instance['text'];
		} else {
			$instance['text'] = wp_kses_post( $new_instance['text'] );
		}

		$instance['filter'] = ! empty( $new_instance['filter'] );

		// Upgrade 4.8.0 format.
		if ( isset( $old_instance['filter'] ) && 'content' === $old_instance['filter'] ) {
			$instance['visual'] = true;
		}
		if ( 'content' === $new_instance['filter'] ) {
			$instance['visual'] = true;
		}

		if ( isset( $new_instance['visual'] ) ) {
			$instance['visual'] = ! empty( $new_instance['visual'] );
		}

		// Filter is always true in visual mode.
		if ( ! empty( $instance['visual'] ) ) {
			$instance['filter'] = true;
		}

		$instance['start_date'] = $this->sanitize_date( $new_instance['start_date'], $this->default_instance['start_date'] );
		$instance['start_time'] = $this->sanitize_time( $new_instance['start_time'], $this->default_instance['end_time'] );
		$instance['end_flag']   = $this->sanitize_checkbox( $new_instance['end_flag'] );
		$instance['end_date']   = $this->sanitize_date( $new_instance['end_date'], $this->default_instance['end_date'] );
		$instance['end_time']   = $this->sanitize_time( $new_instance['end_time'], $this->default_instance['end_time'] );
		$instance['taxonomy']   = $new_instance['taxonomy'];

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
		$instance = wp_parse_args(
			(array) $instance,
			$this->default_instance
		);
		?>
		<?php if ( ! $this->is_legacy_instance( $instance ) ) : ?>
			<?php

			if ( user_can_richedit() ) {
				add_filter( 'the_editor_content', 'format_for_editor', 10, 2 );
				$default_editor = 'tinymce';
			} else {
				$default_editor = 'html';
			}

			/** This filter is documented in wp-includes/class-wp-editor.php */
			$text = apply_filters( 'the_editor_content', $instance['text'], $default_editor );

			// Reset filter addition.
			if ( user_can_richedit() ) {
				remove_filter( 'the_editor_content', 'format_for_editor' );
			}

			// Prevent premature closing of textarea in case format_for_editor() didn't apply or the_editor_content filter did a wrong thing.
			$escaped_text = preg_replace( '#</textarea#i', '&lt;/textarea', $text );

			?>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" class="title sync-input" type="hidden" value="<?php echo esc_attr( $instance['title'] ); ?>">
			<textarea id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>" class="text sync-input" hidden><?php echo $escaped_text; ?></textarea>
			<input id="<?php echo $this->get_field_id( 'filter' ); ?>" name="<?php echo $this->get_field_name( 'filter' ); ?>" class="filter sync-input" type="hidden" value="on">
			<input id="<?php echo $this->get_field_id( 'visual' ); ?>" name="<?php echo $this->get_field_name( 'visual' ); ?>" class="visual sync-input" type="hidden" value="on">
		<?php else : ?>
			<input id="<?php echo $this->get_field_id( 'visual' ); ?>" name="<?php echo $this->get_field_name( 'visual' ); ?>" class="visual" type="hidden" value="">
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>"/>
			</p>
			<div class="notice inline notice-info notice-alt">
				<?php if ( ! isset( $instance['visual'] ) ) : ?>
					<p><?php _e( 'This widget may contain code that may work better in the &#8220;Custom HTML&#8221; widget. How about trying that widget instead?' ); ?></p>
				<?php else : ?>
					<p><?php _e( 'This widget may have contained code that may work better in the &#8220;Custom HTML&#8221; widget. If you haven&#8217;t yet, how about trying that widget instead?' ); ?></p>
				<?php endif; ?>
			</div>
			<p>
				<label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _e( 'Content:' ); ?></label>
				<textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>"><?php echo esc_textarea( $instance['text'] ); ?></textarea>
			</p>
			<p>
				<input id="<?php echo $this->get_field_id( 'filter' ); ?>" name="<?php echo $this->get_field_name( 'filter' ); ?>" type="checkbox"<?php checked( ! empty( $instance['filter'] ) ); ?> />&nbsp;<label for="<?php echo $this->get_field_id( 'filter' ); ?>"><?php _e( 'Automatically add paragraphs' ); ?></label>
			</p>
		<?php endif; ?>
		<div class="ys-admin-section">
			<h4>掲載開始条件</h4>
			<p>
				<label for="<?php echo $this->get_field_id( 'start_date' ); ?>">開始日付</label>
				<input class="" id="<?php echo $this->get_field_id( 'start_date' ); ?>" name="<?php echo $this->get_field_name( 'start_date' ); ?>" type="date" value="<?php echo esc_attr( $instance['start_date'] ); ?>"/><br>
				<label for="<?php echo $this->get_field_id( 'start_time' ); ?>">開始時間</label>
				<input class="" id="<?php echo $this->get_field_id( 'start_time' ); ?>" name="<?php echo $this->get_field_name( 'start_time' ); ?>" type="time" value="<?php echo esc_attr( $instance['start_time'] ); ?>"/>
			</p>
		</div>
		<div class="ys-admin-section">
			<h4>掲載終了条件</h4>
			<p>
				<label for="<?php echo $this->get_field_id( 'end_flag' ); ?>">
					<input type="checkbox" id="<?php echo $this->get_field_id( 'end_flag' ); ?>" name="<?php echo $this->get_field_name( 'end_flag' ); ?>" value="1" <?php checked( $instance['end_flag'], 1 ); ?> />終了条件を有効にする</label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'end_date' ); ?>">終了日付</label>
				<input class="" id="<?php echo $this->get_field_id( 'end_date' ); ?>" name="<?php echo $this->get_field_name( 'end_date' ); ?>" type="date" value="<?php echo esc_attr( $instance['end_date'] ); ?>"/><br>
				<label for="<?php echo $this->get_field_id( 'end_time' ); ?>">終了時間</label>
				<input class="" id="<?php echo $this->get_field_id( 'end_time' ); ?>" name="<?php echo $this->get_field_name( 'end_time' ); ?>" type="time" value="<?php echo esc_attr( $instance['end_time'] ); ?>"/>
			</p>
		</div>
		<div class="ys-admin-section">
			<h4>掲載するカテゴリー・タグ</h4>
			<p>
				<?php $this->the_taxonomies_select_html( $this, $instance['taxonomy'] ); ?><br>
				<span class="ystandard-info--sub">※カテゴリー・タグを選択した場合、投稿詳細ページ かつ 該当のカテゴリー・タグをもつ投稿ページしか表示されません。（一覧ページなどでは表示されません）</span>
			</p>
		</div>
		<?php
	}

	/**
	 * Add hooks for enqueueing assets when registering all widget instances of this widget class.
	 *
	 * @param integer $number Optional. The unique order number of this widget instance
	 *                        compared to other instances of the same class. Default -1.
	 */
	public function _register_one( $number = - 1 ) {
		parent::_register_one( $number );
		wp_add_inline_script( 'text-widgets', sprintf( 'wp.textWidgets.idBases.push( %s );', wp_json_encode( $this->id_base ) ) );
		if ( $this->is_preview() ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_preview_scripts' ) );
		}
		add_action( 'admin_print_scripts-widgets.php', array( $this, 'enqueue_admin_scripts' ) );
		add_action( 'admin_footer-widgets.php', array( 'WP_Widget_Text', 'render_control_template_scripts' ) );
	}

	/**
	 * Determines whether a given instance is legacy and should bypass using TinyMCE.
	 *
	 * @param array $instance Instance data.
	 *
	 * @type string      $text     Content.
	 * @type bool|string $filter   Whether autop or content filters should apply.
	 * @type bool        $legacy   Whether widget is in legacy mode.
	 * }
	 * @return bool Whether Text widget instance contains legacy data.
	 */
	public function is_legacy_instance( $instance ) {
		return $this->wp_widget_text->is_legacy_instance( $instance );
	}

	/**
	 * Enqueue preview scripts.
	 */
	public function enqueue_preview_scripts() {
		$this->wp_widget_text->enqueue_preview_scripts();
	}

	/**
	 * Loads the required scripts and styles for the widget control.
	 */
	public function enqueue_admin_scripts() {
		$this->wp_widget_text->enqueue_admin_scripts();
	}
}