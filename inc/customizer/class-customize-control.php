<?php
/**
 * カスタマイザーコントロール
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

use ystandard\helper\Trigger_Error;

defined( 'ABSPATH' ) || die();

/**
 * Class Customize_Control
 *
 * @package ystandard
 */
class Customize_Control {

	/**
	 * カスタマイザー
	 *
	 * @var \WP_Customize_Manager
	 */
	private $wp_customize = null;

	/**
	 * セクション
	 *
	 * @var string
	 */
	private $section = '';

	/**
	 * パネル
	 *
	 * @var string
	 */
	private $panel = '';

	/**
	 * コンストラクタ
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 */
	public function __construct( $wp_customize ) {
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
				[
					'title'           => '',
					'priority'        => Customizer::get_priority( $args['panel'] ),
					'description'     => '',
					'active_callback' => '',
				]
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
				[
					'title'           => '',
					'priority'        => Customizer::get_priority( $args['section'] ),
					'description'     => '',
					'active_callback' => '',
					'panel'           => $this->panel,
				]
			);
			$this->wp_customize->add_section(
				$args['section'],
				$args
			);
			/**
			 * セクション情報をセット
			 */
			$this->section = $args['section'];
		}
	}

	/**
	 * Add setting and control : color
	 *
	 * @param array $args オプション.
	 */
	public function add_color( $args ) {
		$args                      = $this->parse_args( $args );
		$args['sanitize_callback'] = 'sanitize_hex_color';
		$this->add_setting_and_control( $args, false );
		$palettes      = true;
		$color_palette = get_theme_support( 'editor-color-palette' );
		if ( $color_palette && is_array( $color_palette ) ) {
			$palettes = array_column( $color_palette[0], 'color' );
		}

		if ( class_exists( __NAMESPACE__ . '\Color_Control' ) ) {
			$this->wp_customize->add_control(
				new Color_Control(
					$this->wp_customize,
					$args['id'],
					self::get_control_args(
						$args,
						$args['id'],
						[ 'palette' => $palettes ]
					)
				)
			);
		} else {
			$this->wp_customize->add_control(
				new \WP_Customize_Color_Control(
					$this->wp_customize,
					$args['id'],
					self::get_control_args( $args, $args['id'] )
				)
			);
		}
	}

	/**
	 * Add setting and control : image
	 *
	 * @param array $args オプション.
	 */
	public function add_image( $args ) {
		$args = $this->parse_args( $args );
		$this->add_setting_and_control( $args, false );
		$this->wp_customize->add_control(
			new \WP_Customize_Image_Control(
				$this->wp_customize,
				$args['id'],
				self::get_control_args( $args, $args['id'] )
			)
		);
	}

	/**
	 * Add setting and control : radio
	 *
	 * @param array $args オプション.
	 */
	public function add_radio( $args ) {
		$args['control_type'] = 'radio';

		$this->add_setting_and_control( $args );
	}

	/**
	 * Add setting and control : select
	 *
	 * @param array $args オプション.
	 */
	public function add_select( $args ) {
		$args['control_type']      = 'select';
		$args['sanitize_callback'] = [ $this, 'sanitize_select' ];
		$this->add_setting_and_control( $args );
	}

	/**
	 * Add setting and control : label radio
	 *
	 * @param array $args オプション.
	 */
	public function add_image_label_radio( $args ) {
		$args                      = $this->parse_args( $args );
		$args['control_type']      = 'radio';
		$args['sanitize_callback'] = [ $this, 'sanitize_select' ];
		$this->add_setting_and_control( $args, false );
		$this->wp_customize->add_control(
			new Image_Label_Radio_Control(
				$this->wp_customize,
				$args['id'],
				self::get_control_args( $args, $args['id'] )
			)
		);
	}

	/**
	 * Add setting and control : checkbox
	 *
	 * @param array $args オプション.
	 */
	public function add_checkbox( $args ) {
		$args['control_type']      = 'checkbox';
		$args['sanitize_callback'] = [ $this, 'sanitize_checkbox' ];
		$this->add_setting_and_control( $args );
	}

	/**
	 * Add setting and control : text
	 *
	 * @param array $args オプション.
	 */
	public function add_text( $args ) {
		$args['control_type'] = 'text';
		if ( ! isset( $args['sanitize_callback'] ) ) {
			$args['sanitize_callback'] = 'sanitize_text_field';
		}
		$this->add_setting_and_control( $args );
	}

	/**
	 * Add setting and control : number
	 *
	 * @param array $args オプション.
	 */
	public function add_number( $args ) {
		$args['control_type']      = 'number';
		$args['sanitize_callback'] = [ $this, 'sanitize_number' ];
		$this->add_setting_and_control( $args );
	}

	/**
	 * Add setting and control : textarea
	 *
	 * @param array $args オプション.
	 */
	public function add_textarea( $args ) {
		$args['control_type'] = 'textarea';
		$this->add_setting_and_control( $args );
	}

	/**
	 * Add setting and control : textarea(no html)
	 *
	 * @param array $args オプション.
	 */
	public function add_plain_textarea( $args ) {
		/**
		 * サニタイズのセット
		 */
		$args['sanitize_callback'] = [ $this, 'sanitize_plain_text' ];
		$this->add_textarea( $args );
	}

	/**
	 * Add setting and control : url
	 *
	 * @param array $args オプション.
	 */
	public function add_url( $args ) {
		$args['control_type']      = 'url';
		$args['sanitize_callback'] = 'esc_url_raw';
		$this->add_setting_and_control( $args );
	}

	/**
	 * Add setting and control : label
	 *
	 * @param array $args オプション.
	 */
	public function add_label( $args ) {
		$args['control_type'] = 'hidden';
		$args['transport']    = 'postMessage';
		$this->add_setting_and_control( $args );
	}

	/**
	 * Add setting and control : section label.
	 *
	 * @param string $label 文字.
	 * @param array  $args  オプション.
	 */
	public function add_section_label( $label, $args = [] ) {
		$args                 = $this->parse_args( $args );
		$args['control_type'] = 'hidden';
		$args['transport']    = 'postMessage';
		$args['label']        = $label;
		if ( ! isset( $args['id'] ) ) {
			$args['id'] = 'ys_' . substr( md5( $label . $args['section'] ), 0, 40 );
		}
		$this->add_setting_and_control( $args, false );
		$this->wp_customize->add_control(
			new Section_Label_Control(
				$this->wp_customize,
				$args['id'],
				self::get_control_args( $args, $args['id'] )
			)
		);
	}

	/**
	 * 設定・コントロール追加
	 *
	 * @param array               $args    Args.
	 * @param object|null|boolean $control Control.
	 *
	 * @return void
	 */
	private function add_setting_and_control( $args, $control = null ) {
		$args = $this->parse_args( $args );
		$id   = $args['id'];
		/**
		 * 設定値分割.
		 */
		$setting = self::get_setting_args( $args, $id );
		if ( is_null( $control ) ) {
			$control = self::get_control_args( $args, $id );
		}

		$this->wp_customize->add_setting( $id, $setting );
		if ( is_array( $control ) ) {
			$this->wp_customize->add_control( $id, $control );
		} elseif ( false !== $control ) {
			$this->wp_customize->add_control( $control );
		}
	}


	/**
	 * 設定用パラメーターの抽出
	 *
	 * @param array  $args   パラメーター.
	 * @param string $id     ID.
	 * @param array  $option 追加パラメーター.
	 *
	 * @return array
	 */
	public static function get_setting_args( $args, $id = '', $option = [] ) {
		$setting_keys = [
			'setting_type'      => 'type',
			'transport'         => 'transport',
			'default'           => 'default',
			'sanitize_callback' => 'sanitize_callback',
		];

		return apply_filters(
			'ys_customize_get_setting_args__' . $id,
			array_merge(
				self::parse_customize_args( $args, $setting_keys ),
				$option
			)
		);
	}

	/**
	 * コントロール用パラメーターの抽出
	 *
	 * @param array  $args   パラメーター.
	 * @param string $id     ID.
	 * @param array  $option 追加パラメーター.
	 *
	 * @return array
	 */
	public static function get_control_args( $args, $id = '', $option = [] ) {
		$control_keys = [
			'control_type'    => 'type',
			'active_callback' => 'active_callback',
			'priority'        => 'priority',
			'section'         => 'section',
			'label'           => 'label',
			'description'     => 'description',
			'choices'         => 'choices',
			'input_attrs'     => 'input_attrs',
			'id'              => 'settings',
		];

		return apply_filters(
			'ys_customize_get_control_args__' . $id,
			array_merge(
				self::parse_customize_args( $args, $control_keys ),
				$option
			)
		);
	}

	/**
	 * カスタマイズコントロール用パラメーター作成
	 *
	 * @param array $args Args.
	 * @param array $keys 変換元キーと変換後キーの組み合わせ.
	 *
	 * @return array
	 */
	private static function parse_customize_args( $args, $keys ) {
		$result = [];
		foreach ( $keys as $key => $value ) {
			if ( array_key_exists( $key, $args ) ) {
				$result[ $value ] = $args[ $key ];
			}
		}

		return $result;
	}

	/**
	 * デフォルト値のセット
	 *
	 * @param array $args オプション.
	 *
	 * @return array
	 */
	public function parse_args( $args ) {
		$id = '';
		/**
		 * デフォルト指定チェック
		 */
		if ( ! isset( $args['default'] ) ) {
			$args['default'] = '';
		}
		if ( isset( $args['id'] ) ) {
			$id              = $args['id'];
			$args['default'] = Option::get_default( $id, $args['default'] );
		}

		/**
		 * セクション
		 */
		$args['section'] = isset( $args['section'] ) ? $args['section'] : $this->section;

		return apply_filters(
			'ys_customizer_parse_args__' . $id,
			wp_parse_args( $args, $this->get_default_option() ),
			$this->wp_customize,
			$id
		);
	}

	/**
	 * カスタマイザーのデフォルト値を取得
	 */
	public function get_default_option() {
		return [
			'setting_type'      => 'option',
			'description'       => '',
			'transport'         => 'refresh',
			'priority'          => 1,
			'default'           => '',
			'input_attrs'       => [],
			'sanitize_callback' => '',
			'active_callback'   => [],
		];
	}

	/**
	 * 設定のセクションを変更にする
	 *
	 * @param string $name    Name.
	 * @param string $section Section.
	 */
	public function set_section( $name, $section ) {
		$this->wp_customize->get_control( $name )->section = $section;
	}

	/**
	 * 設定をRefreshにする
	 *
	 * @param string $name Name.
	 */
	public function set_refresh( $name ) {
		$this->wp_customize->get_setting( $name )->transport = 'refresh';
	}

	/**
	 * 設定をpostMessageにする
	 *
	 * @param string $name Name.
	 */
	public function set_post_message( $name ) {
		$this->wp_customize->get_setting( $name )->transport = 'postMessage';
	}

	/**
	 * 設定をpostMessageにする
	 *
	 * @param string $name Name.
	 * @param string $dscr Description.
	 */
	public function set_section_description( $name, $dscr ) {
		$this->wp_customize->get_section( $name )->description = $dscr;
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
			return ( ( isset( $value ) && Utility::to_bool( $value ) ) ? true : '' );
		}

		return ( ( isset( $value ) && Utility::to_bool( $value ) ) ? true : false );
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
		if ( ! is_numeric( $number ) ) {
			return $setting->default;
		}
		$attr = $setting->manager->get_control( $setting->id )->input_attrs;
		$min  = ( isset( $attr['min'] ) ? $attr['min'] : $number );
		$max  = ( isset( $attr['max'] ) ? $attr['max'] : $number );

		return ( $min <= $number && $number <= $max ? $number : $setting->default );
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
