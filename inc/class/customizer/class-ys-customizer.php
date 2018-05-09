<?php
/**
 * カスタマイザーコントロール
 *
 * @package ystandard
 * @author yosiakatsuki
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
			$this->defaults = $this->parse_args( array(
				'section' => $args['section'],
			) );
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
					'description' => $args['description'],
					'label'       => $args['label'],
					'section'     => $args['section'],
					'settings'    => $args['id'],
					'priority'    => $args['priority'],
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
					'description' => $args['description'],
					'label'       => $args['label'],
					'section'     => $args['section'],
					'settings'    => $args['id'],
					'priority'    => $args['priority'],
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
				'choices'     => $args['choices'],
				'description' => $args['description'],
				'label'       => $args['label'],
				'priority'    => $args['priority'],
				'section'     => $args['section'],
				'settings'    => $args['id'],
				'type'        => 'radio',
			)
		);
	}
	/**
	 * デフォルト値のセット
	 *
	 * @param array $args オプション.
	 */
	public function parse_args( $args ) {
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
	 * @param string $input input.
	 * @param object $setting setting.
	 */
	public function sanitize_select( $input, $setting ) {
		$input   = sanitize_key( $input );
		$choices = $setting->manager->get_control( $setting->id )->choices;
		return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
	}
}
