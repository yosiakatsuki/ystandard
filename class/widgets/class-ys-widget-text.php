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
	private $widget_id = 'ys_text';
	/**
	 * ウィジェット名
	 *
	 * @var string
	 */
	private $widget_name = '[ys]表示条件付きテキスト';

	/**
	 * ウィジェットオプション
	 *
	 * @var array
	 */
	public $widget_options = array(
		'classname'   => 'ys_widget_text',
		'description' => '表示条件付きテキスト',
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
	 * Sets up a new Custom HTML widget instance.
	 */
	public function __construct() {
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
				'title'  => '',
				'text'   => '',
				'filter' => false, // For back-compat.
				'visual' => null, // Must be explicitly defined.
			)
		);
		/**
		 * WP_Widget_Text
		 */
		$this->wp_widget_text = new WP_Widget_Text();
		/**
		 * 設定書き換え
		 */
		$this->wp_widget_text->id_base         = $this->widget_id;
		$this->wp_widget_text->name            = $this->widget_name;
		$this->wp_widget_text->widget_options  = wp_parse_args(
			$this->widget_options,
			$this->wp_widget_text->widget_options
		);
		$this->wp_widget_text->control_options = wp_parse_args(
			$this->control_options,
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

		$instance = $this->update_base_options( $new_instance, $old_instance );


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
		<?php
		endif;
		$this->form_ys_advanced( $instance );
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
	 * @param array      $instance Instance data.
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