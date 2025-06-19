<?php
/**
 * カスタマイザーコントロール
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

use ystandard\utils\Convert;

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
	 * ラベル.
	 *
	 * @param array $args オプション.
	 */
	public function add_label( $args ) {
		$args['control_type'] = 'hidden';
		$args['transport']    = 'postMessage';
		$this->add_setting( $args );
	}

	/**
	 * URL.
	 *
	 * @param array $args オプション.
	 */
	public function add_url( $args ) {
		$args['control_type']      = 'url';
		$args['sanitize_callback'] = 'esc_url_raw';
		$this->add_setting( $args );
	}

	/**
	 * テキストエリア.
	 *
	 * @param array $args オプション.
	 */
	public function add_textarea( $args ) {
		$args['control_type'] = 'textarea';
		$this->add_setting( $args );
	}

	/**
	 * プレーンテキスト.
	 *
	 * @param array $args オプション.
	 */
	public function add_plain_textarea( $args ) {
		// サニタイズのセット.
		$args['sanitize_callback'] = [ __CLASS__, 'sanitize_plain_text' ];
		$this->add_textarea( $args );
	}

	/**
	 * 数値.
	 *
	 * @param array $args オプション.
	 */
	public function add_number( $args ) {
		$args['control_type']      = 'number';
		$args['sanitize_callback'] = [ __CLASS__, 'sanitize_number' ];
		$this->add_setting( $args );
	}

	/**
	 * テキスト.
	 *
	 * @param array $args オプション.
	 */
	public function add_text( $args ) {
		$args['control_type'] = 'text';
		if ( ! isset( $args['sanitize_callback'] ) ) {
			$args['sanitize_callback'] = 'sanitize_text_field';
		}
		$this->add_setting( $args );
	}

	/**
	 * チェックボックス.
	 *
	 * @param array $args オプション.
	 */
	public function add_checkbox( $args ) {
		$args['control_type']      = 'checkbox';
		$args['sanitize_callback'] = [ __CLASS__, 'sanitize_checkbox' ];
		$this->add_setting( $args );
	}

	/**
	 * セレクト.
	 *
	 * @param array $args オプション.
	 */
	public function add_select( $args ) {
		$args['control_type']      = 'select';
		$args['sanitize_callback'] = [ __CLASS__, 'sanitize_select' ];
		$this->add_setting( $args );
	}

	/**
	 * ラジオボタン.
	 *
	 * @param array $args オプション.
	 */
	public function add_radio( $args ) {
		$args['control_type']      = 'radio';
		$args['sanitize_callback'] = [ __CLASS__, 'sanitize_select' ];

		$this->add_setting( $args );
	}

	/**
	 * 画像ラジオボタン.
	 *
	 * @param array $args オプション.
	 */
	public function add_image_label_radio( $args ) {
		$args                      = $this->parse_args( $args );
		$args['control_type']      = 'radio';
		$args['sanitize_callback'] = [ __CLASS__, 'sanitize_select' ];
		$this->add_setting( $args, false );
		if ( class_exists( __NAMESPACE__ . '\Image_Label_Radio_Control' ) ) {
			$this->wp_customize->add_control(
				new Image_Label_Radio_Control(
					$this->wp_customize,
					$args['id'],
					self::get_control_args( $args, $args['id'] )
				)
			);
			$this->do_action_after_add_setting( $args['id'], $args );
		}
	}

	/**
	 * 画像.
	 *
	 * @param array $args オプション.
	 */
	public function add_image( $args ) {
		$args = $this->parse_args( $args );
		// 設定追加
		$this->add_setting( $args, false );
		$this->wp_customize->add_control(
			new \WP_Customize_Image_Control(
				$this->wp_customize,
				$args['id'],
				self::get_control_args( $args, $args['id'] )
			)
		);
		$this->do_action_after_add_setting( $args['id'], $args );
	}

	/**
	 * カラー.
	 *
	 * @param array $args オプション.
	 */
	public function add_color( $args ) {
		$args = $this->parse_args( $args );
		// サニタイズ設定.
		$args['sanitize_callback'] = 'sanitize_hex_color';
		// 設定追加.
		$this->add_setting( $args, false );
		// パレット設定.
		$palettes      = true;
		$color_palette = get_theme_support( 'editor-color-palette' );
		if ( $color_palette && is_array( $color_palette ) ) {
			$palettes = array_column( $color_palette[0], 'color' );
		}

		// コントロール追加.
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
		$this->do_action_after_add_setting( $args['id'], $args );
	}

	/**
	 * セクションラベル.
	 *
	 * @param string $label 文字.
	 * @param array  $args オプション.
	 */
	public function add_section_label( $label, $args = [] ) {
		$args = $this->parse_args( $args );
		// 追加設定.
		$args['control_type'] = 'hidden';
		$args['transport']    = 'postMessage';
		$args['label']        = $label;
		// IDがなければ自動生成.
		if ( ! isset( $args['id'] ) ) {
			$args['id'] = 'ys_' . substr( md5( $label . $args['section'] ), 0, 40 );
		}
		// 設定のみ追加.
		$this->add_setting( $args, false );
		// セクションラベルコントロールの追加.
		if ( class_exists( __NAMESPACE__ . '\Section_Label_Control' ) ) {
			$this->wp_customize->add_control(
				new Section_Label_Control(
					$this->wp_customize,
					$args['id'],
					self::get_control_args( $args, $args['id'] )
				)
			);
			$this->do_action_after_add_setting( $args['id'], $args );
		}
	}

	/**
	 * パネル追加
	 *
	 * @param array $args パラメーター.
	 */
	public function add_panel( $args ) {
		if ( ! isset( $args['panel'] ) ) {
			return;
		}
		// デフォルト設定の追加.
		$args = wp_parse_args(
			$args,
			[
				'title'           => '',
				'priority'        => Customizer::get_priority( $args['panel'] ),
				'description'     => '',
				'active_callback' => '',
			]
		);
		// パネル追加.
		$this->wp_customize->add_panel(
			$args['panel'],
			$args
		);
		// パネル情報を保存.
		$this->panel = $args['panel'];
	}

	/**
	 * セクション追加
	 *
	 * @param array $args パラメーター.
	 */
	public function add_section( $args ) {
		if ( ! isset( $args['section'] ) ) {
			return;
		}
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
		// セクション追加.
		$this->wp_customize->add_section(
			$args['section'],
			$args
		);
		// セクション情報を保存.
		$this->section = $args['section'];
	}

	/**
	 * 設定用パラメーターの抽出
	 *
	 * @param array  $args パラメーター.
	 * @param string $id ID.
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
			"ys_customize_get_setting_args__{$id}",
			array_merge(
				self::parse_customize_args( $args, $setting_keys ),
				$option
			)
		);
	}

	/**
	 * コントロール用パラメーターの抽出
	 *
	 * @param array  $args パラメーター.
	 * @param string $id ID.
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
		// 設定名を取得.
		$setting_name = isset( $args['id'] ) ? $args['id'] : '';

		// デフォルト指定チェック.
		if ( ! isset( $args['default'] ) ) {
			$args['default'] = '';
		}
		if ( ! empty( $setting_name ) ) {
			$args['default'] = Option::get_default( $setting_name, $args['default'] );
		}

		// セクション指定チェック.
		$args['section'] = isset( $args['section'] ) ? $args['section'] : $this->section;

		return apply_filters(
			"ys_customizer_parse_args__{$setting_name}",
			wp_parse_args( $args, self::get_default_option() ),
			$this->wp_customize,
			$setting_name
		);
	}

	/**
	 * カスタマイザーのデフォルト値を取得
	 */
	public static function get_default_option() {
		return [
			'setting_type'      => 'option',
			'description'       => '',
			'transport'         => 'refresh',
			'priority'          => 10,
			'default'           => '',
			'input_attrs'       => [],
			'sanitize_callback' => '',
			'active_callback'   => [],
		];
	}

	/**
	 * 設定のセクションを変更にする
	 *
	 * @param string $name Name.
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
	 * 設定・コントロール追加
	 *
	 * @param array               $args Args.
	 * @param object|null|boolean $control Control.
	 *
	 * @return void
	 */
	private function add_setting( $args, $control = null ) {
		$args         = $this->parse_args( $args );
		$setting_name = $args['id'];
		// 設定値を分割.
		$setting = self::get_setting_args( $args, $setting_name );
		// コントロールの指定がなければ設定から変換してセット.
		if ( is_null( $control ) ) {
			$control = self::get_control_args( $args, $setting_name );
		}
		// 設定追加.
		$this->wp_customize->add_setting( $setting_name, $setting );
		// コントロール追加.
		$add_control = false;
		if ( is_array( $control ) ) {
			$this->wp_customize->add_control( $setting_name, $control );
			$add_control = true;
		} elseif ( false !== $control ) {
			$this->wp_customize->add_control( $control );
			$add_control = true;
		}
		// コントロール追加していたらアクションを実行.
		if ( $add_control ) {
			$this->do_action_after_add_setting( $setting_name, $args );
		}
	}

	/**
	 * 設定・コントロール追加後のアクション
	 *
	 * @param string $setting Setting Name.
	 * @param array  $args Args.
	 *
	 * @return void
	 */
	public function do_action_after_add_setting( $setting, $args ) {
		do_action( "ys_customizer_add_setting__{$setting}", $this->wp_customize, $args );
	}

	/**
	 * Select,radio
	 *
	 * @param string $input input.
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
	 * @param string $value input.
	 * @param object $setting setting.
	 *
	 * @return bool
	 */
	public static function sanitize_checkbox( $value, $setting ) {
		if ( 'option' === $setting->manager->get_setting( $setting->id )->type ) {
			return ( ( isset( $value ) && Convert::to_bool( $value ) ) ? true : '' );
		}

		return ( ( isset( $value ) && Convert::to_bool( $value ) ) ? true : false );
	}

	/**
	 * Number
	 *
	 * @param string $number number.
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
