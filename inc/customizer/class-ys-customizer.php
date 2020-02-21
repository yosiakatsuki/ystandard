<?php
/**
 * カスタマイザーコントロール
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * カスタマイザーコントロール
 */
class YS_Customizer {

	/**
	 * カスタマイザー
	 *
	 * @var WP_Customize_Manager
	 */
	private $wp_customize = null;

	/**
	 * デフォルト値
	 *
	 * @var array
	 */
	private $defaults = array();

	/**
	 * パネル
	 *
	 * @var string
	 */
	private $panel = '';

	/**
	 * コンストラクタ
	 *
	 * @param WP_Customize_Manager $wp_customize カスタマイザー.
	 */
	public function __construct( $wp_customize ) {
		$this->defaults     = $this->get_default_option();
		$this->wp_customize = $wp_customize;
	}

	/**
	 * パネル追加
	 *
	 * @param array $args パラメーター.
	 */
	public function add_panel( $args ) {
		if ( isset( $args['panel'] ) ) {
			$args = wp_parse_args(
				$args,
				array(
					'title'           => '',
					'priority'        => 160,
					'description'     => '',
					'active_callback' => '',
				)
			);
			$this->wp_customize->add_panel(
				$args['panel'],
				$args
			);
			/**
			 * パネル情報をセット
			 */
			$this->panel = $args['panel'];
		}
	}

	/**
	 * セクション追加
	 *
	 * @param array $args パラメーター.
	 */
	public function add_section( $args ) {
		if ( isset( $args['section'] ) ) {
			$args = wp_parse_args(
				$args,
				array(
					'title'           => '',
					'priority'        => 160,
					'description'     => '',
					'active_callback' => '',
					'panel'           => $this->panel,
				)
			);
			$this->wp_customize->add_section(
				$args['section'],
				$args
			);
			/**
			 * セクション情報をセット
			 */
			$this->defaults = $this->parse_args(
				array(
					'section' => $args['section'],
				)
			);
		}
	}

	/**
	 * Add setting and control : color
	 *
	 * @param array $args オプション.
	 */
	public function add_color( $args ) {
		$args = $this->parse_args( $args );
		$this->wp_customize->add_setting(
			$args['id'],
			array(
				'default'           => $args['default'],
				'type'              => $args['setting_type'],
				'transport'         => $args['transport'],
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);
		$this->wp_customize->add_control(
			new WP_Customize_Color_Control(
				$this->wp_customize,
				$args['id'],
				array(
					'label'       => $args['label'],
					'description' => $args['description'],
					'section'     => $args['section'],
					'priority'    => $args['priority'],
					'settings'    => $args['id'],
				)
			)
		);
	}

	/**
	 * Add setting and control : image
	 *
	 * @param array $args オプション.
	 */
	public function add_image( $args ) {
		$args = $this->parse_args( $args );
		$this->wp_customize->add_setting(
			$args['id'],
			array(
				'type'      => $args['setting_type'],
				'transport' => $args['transport'],
			)
		);
		$this->wp_customize->add_control(
			new WP_Customize_Image_Control(
				$this->wp_customize,
				$args['id'],
				array(
					'label'       => $args['label'],
					'description' => $args['description'],
					'section'     => $args['section'],
					'priority'    => $args['priority'],
					'settings'    => $args['id'],
				)
			)
		);
	}

	/**
	 * Add setting and control : radio
	 *
	 * @param array $args オプション.
	 */
	public function add_radio( $args ) {
		$args = $this->parse_args( $args );
		$this->wp_customize->add_setting(
			$args['id'],
			array(
				'default'           => $args['default'],
				'type'              => $args['setting_type'],
				'transport'         => $args['transport'],
				'sanitize_callback' => array( $this, 'sanitize_select' ),
			)
		);
		$this->wp_customize->add_control(
			$args['id'],
			array(
				'label'       => $args['label'],
				'description' => $args['description'],
				'section'     => $args['section'],
				'priority'    => $args['priority'],
				'type'        => 'radio',
				'settings'    => $args['id'],
				'choices'     => $args['choices'],
			)
		);
	}

	/**
	 * Add setting and control : radio
	 *
	 * @param array $args オプション.
	 */
	public function add_image_label_radio( $args ) {
		$args = $this->parse_args( $args );
		$this->wp_customize->add_setting(
			$args['id'],
			array(
				'default'           => $args['default'],
				'type'              => $args['setting_type'],
				'transport'         => $args['transport'],
				'sanitize_callback' => array( $this, 'sanitize_select' ),
			)
		);
		$this->wp_customize->add_control(
			new YS_Customize_Image_Label_Radio_Control(
				$this->wp_customize,
				$args['id'],
				array(
					'label'       => $args['label'],
					'description' => $args['description'],
					'section'     => $args['section'],
					'priority'    => $args['priority'],
					'type'        => 'radio',
					'settings'    => $args['id'],
					'choices'     => $args['choices'],
				)
			)
		);
	}

	/**
	 * Add setting and control : checkbox
	 *
	 * @param array $args オプション.
	 */
	public function add_checkbox( $args ) {
		$args = $this->parse_args( $args );
		$this->wp_customize->add_setting(
			$args['id'],
			array(
				'default'           => $args['default'],
				'type'              => $args['setting_type'],
				'transport'         => $args['transport'],
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			)
		);
		$this->wp_customize->add_control(
			$args['id'],
			array(
				'label'       => $args['label'],
				'description' => $args['description'],
				'section'     => $args['section'],
				'priority'    => $args['priority'],
				'type'        => 'checkbox',
				'settings'    => $args['id'],
			)
		);
	}

	/**
	 * Add setting and control : text
	 *
	 * @param array $args オプション.
	 */
	public function add_text( $args ) {
		if ( ! isset( $args['sanitize_callback'] ) ) {
			$args['sanitize_callback'] = 'sanitize_text_field';
		}
		$args = $this->parse_args( $args );
		$this->wp_customize->add_setting(
			$args['id'],
			array(
				'default'           => $args['default'],
				'type'              => $args['setting_type'],
				'transport'         => $args['transport'],
				'sanitize_callback' => $args['sanitize_callback'],
			)
		);
		$this->wp_customize->add_control(
			$args['id'],
			array(
				'label'       => $args['label'],
				'description' => $args['description'],
				'section'     => $args['section'],
				'priority'    => $args['priority'],
				'type'        => 'text',
				'settings'    => $args['id'],
				'input_attrs' => $args['input_attrs'],
			)
		);
	}

	/**
	 * Add setting and control : number
	 *
	 * @param array $args オプション.
	 */
	public function add_number( $args ) {
		$args = $this->parse_args( $args );
		$this->wp_customize->add_setting(
			$args['id'],
			array(
				'default'           => $args['default'],
				'type'              => $args['setting_type'],
				'transport'         => $args['transport'],
				'sanitize_callback' => array( $this, 'sanitize_number' ),
			)
		);
		$this->wp_customize->add_control(
			$args['id'],
			array(
				'label'       => $args['label'],
				'description' => $args['description'],
				'section'     => $args['section'],
				'priority'    => $args['priority'],
				'type'        => 'number',
				'settings'    => $args['id'],
				'input_attrs' => $args['input_attrs'],
			)
		);
	}

	/**
	 * Add setting and control : textarea
	 *
	 * @param array $args オプション.
	 */
	public function add_textarea( $args ) {
		$args = $this->parse_args( $args );
		$this->wp_customize->add_setting(
			$args['id'],
			array(
				'default'           => $args['default'],
				'type'              => $args['setting_type'],
				'transport'         => $args['transport'],
				'sanitize_callback' => $args['sanitize_callback'],
			)
		);
		$this->wp_customize->add_control(
			$args['id'],
			array(
				'label'       => $args['label'],
				'description' => $args['description'],
				'section'     => $args['section'],
				'priority'    => $args['priority'],
				'type'        => 'textarea',
				'settings'    => $args['id'],
			)
		);
	}

	/**
	 * Add setting and control : textarea(no html)
	 *
	 * @param array $args オプション.
	 */
	public function add_plain_textarea( $args ) {
		$args = $this->parse_args( $args );
		/**
		 * サニタイズのセット
		 */
		$args['sanitize_callback'] = array( $this, 'sanitize_plain_text' );
		$this->add_textarea( $args );
	}

	/**
	 * Add setting and control : url
	 *
	 * @param array $args オプション.
	 */
	public function add_url( $args ) {
		$args = $this->parse_args( $args );
		$this->wp_customize->add_setting(
			$args['id'],
			array(
				'default'           => $args['default'],
				'type'              => $args['setting_type'],
				'transport'         => $args['transport'],
				'sanitize_callback' => 'esc_url_raw',
			)
		);
		$this->wp_customize->add_control(
			$args['id'],
			array(
				'label'       => $args['label'],
				'description' => $args['description'],
				'section'     => $args['section'],
				'priority'    => $args['priority'],
				'type'        => 'url',
				'settings'    => $args['id'],
			)
		);
	}

	/**
	 * Add setting and control : label
	 *
	 * @param array $args オプション.
	 */
	public function add_label( $args ) {
		$args = $this->parse_args( $args );
		$this->wp_customize->add_setting(
			$args['id'],
			array(
				'type'      => 'option',
				'transport' => 'postMessage',
			)
		);
		$this->wp_customize->add_control(
			$args['id'],
			array(
				'label'       => $args['label'],
				'description' => $args['description'],
				'section'     => $args['section'],
				'priority'    => $args['priority'],
				'type'        => 'hidden',
				'settings'    => $args['id'],
			)
		);
	}

	/**
	 * Add setting and control : section label.
	 *
	 * @param string $label 文字.
	 * @param array  $args  オプション.
	 */
	public function add_section_label( $label, $args = array() ) {
		if ( ! isset( $args['id'] ) ) {
			$args['id'] = 'ys_' . substr( md5( $label ), 0, 10 );
		}
		$args['label'] = $label;
		$args          = $this->parse_args( $args );
		$this->wp_customize->add_setting(
			$args['id'],
			array(
				'type'      => 'option',
				'transport' => 'postMessage',
			)
		);
		$this->wp_customize->add_control(
			new YS_Customize_Section_Label_Control(
				$this->wp_customize,
				$args['id'],
				array(
					'label'       => $args['label'],
					'description' => $args['description'],
					'section'     => $args['section'],
					'priority'    => $args['priority'],
					'type'        => 'hidden',
					'settings'    => $args['id'],
				)
			)
		);
	}

	/**
	 * デフォルト値のセット
	 *
	 * @param array $args オプション.
	 *
	 * @return array
	 */
	public function parse_args( $args ) {
		/**
		 * デフォルト指定チェック
		 */
		if ( ! isset( $args['default'] ) ) {
			if ( isset( $args['id'] ) ) {
				$args['default'] = ys_get_option_default( $args['id'] );
			}
		}

		return wp_parse_args( $args, $this->defaults );
	}

	/**
	 * カスタマイザーのデフォルト値を取得
	 */
	public function get_default_option() {
		return array(
			'setting_type'      => 'option',
			'description'       => '',
			'transport'         => 'refresh',
			'priority'          => 1,
			'default'           => '',
			'input_attrs'       => array(),
			'sanitize_callback' => '',
		);
	}

	/**
	 * Select,radio
	 *
	 * @param string $input   input.
	 * @param object $setting setting.
	 *
	 * @return string
	 */
	public static function sanitize_select( $input, $setting ) {
		$input   = sanitize_key( $input );
		$choices = $setting->manager->get_control( $setting->id )->choices;

		return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
	}

	/**
	 * Checkbox
	 *
	 * @param string $value   input.
	 * @param object $setting setting.
	 *
	 * @return bool
	 */
	public static function sanitize_checkbox( $value, $setting ) {
		if ( 'option' === $setting->manager->get_setting( $setting->id )->type ) {
			return ( ( isset( $value ) && ys_to_bool( $value ) ) ? true : '' );
		}

		return ( ( isset( $value ) && ys_to_bool( $value ) ) ? true : false );
	}

	/**
	 * Number
	 *
	 * @param string $number  number.
	 * @param object $setting setting.
	 *
	 * @return int
	 */
	public static function sanitize_number( $number, $setting ) {
		if ( 1 !== preg_match( '/^\d+$/', $number ) ) {
			return $setting->default;
		}
		$attr = $setting->manager->get_control( $setting->id )->input_attrs;
		$min  = ( isset( $attr['min'] ) ? $attr['min'] : $number );
		$max  = ( isset( $attr['max'] ) ? $attr['max'] : $number );
		$step = ( isset( $attr['step'] ) ? $attr['step'] : 1 );

		return ( $min <= $number && $number <= $max && is_int( $number / $step ) ? $number : $setting->default );
	}

	/**
	 * Plain text
	 *
	 * @param string $value value.
	 *
	 * @return string
	 */
	public static function sanitize_plain_text( $value ) {
		$value = wp_strip_all_tags( $value, true );

		return $value;
	}
}
