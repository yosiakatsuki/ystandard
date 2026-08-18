<?php
/**
 * ブロックエディターの文字サイズ定義.
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

/**
 * Class Block_Editor_Font_Size
 *
 * @package ystandard
 */
class Block_Editor_Font_Size {

	/**
	 * ユーザー定義文字サイズの上限.
	 */
	const USER_FONT_SIZE_LIMIT = 6;

	/**
	 * 設定名の接頭辞.
	 */
	const OPTION_PREFIX = 'ys-block-editor-font-size-preset-';

	/**
	 * Block_Editor_Font_Size constructor.
	 */
	public function __construct() {
		add_filter( 'wp_theme_json_data_theme', [ $this, 'add_user_font_sizes_to_theme_json' ] );
		add_action( 'customize_register', [ $this, 'customize_register' ] );
	}

	/**
	 * ユーザー定義文字サイズをtheme.jsonのテーマ設定へ追加.
	 *
	 * @param \WP_Theme_JSON_Data $theme_json Theme.jsonデータ.
	 *
	 * @return \WP_Theme_JSON_Data
	 */
	public function add_user_font_sizes_to_theme_json( $theme_json ) {
		$data                = $theme_json->get_data();
		$font_size_settings  = $data['settings']['typography']['fontSizes'] ?? [];
		$existing_font_sizes = $font_size_settings['theme'] ?? $font_size_settings;
		$managed_slugs       = [];

		for ( $i = 1; $i <= self::USER_FONT_SIZE_LIMIT; $i ++ ) {
			$managed_slugs[] = self::get_slug( $i );
		}

		$filtered_font_sizes = array_values(
			array_filter(
				$existing_font_sizes,
				function ( $font_size ) use ( $managed_slugs ) {
					return empty( $font_size['slug'] ) || ! in_array( $font_size['slug'], $managed_slugs, true );
				}
			)
		);
		$user_font_sizes     = self::get_user_font_sizes();

		// ユーザー設定も古い管理対象slugもなければ、元のtheme.jsonを変更しない.
		if ( $existing_font_sizes === $filtered_font_sizes && empty( $user_font_sizes ) ) {
			return $theme_json;
		}

		return $theme_json->update_with(
			[
				'version'  => 3,
				'settings' => [
					'typography' => [
						'fontSizes' => array_merge( $user_font_sizes, $filtered_font_sizes ),
					],
				],
			]
		);
	}

	/**
	 * 有効なユーザー定義文字サイズを取得.
	 *
	 * @return array
	 */
	public static function get_user_font_sizes() {
		$font_sizes = [];

		for ( $i = 1; $i <= self::USER_FONT_SIZE_LIMIT; $i ++ ) {
			$label = sanitize_text_field( Option::get_option( self::get_option_name( $i, 'label' ), '' ) );
			$type  = Option::get_option( self::get_option_name( $i, 'type' ), 'static' );

			// ラベルがない設定枠は、未登録として扱う.
			if ( '' === $label ) {
				continue;
			}

			// 固定値ではWordPressの流体タイポグラフィを明示的に無効化する.
			if ( 'static' === $type ) {
				$size = self::normalize_static_size(
					Option::get_option( self::get_option_name( $i, 'static' ), '' )
				);

				// 固定値が未入力または不正な設定枠はプリセットにしない.
				if ( '' === $size ) {
					continue;
				}

				$font_sizes[] = [
					'name'  => $label,
					'slug'  => self::get_slug( $i ),
					'size'  => $size,
					'fluid' => false,
				];
				continue;
			}

			// 想定外の種類は保存値をCSSへ反映しない.
			if ( 'fluid' !== $type ) {
				continue;
			}

			$unit = Option::get_option( self::get_option_name( $i, 'unit' ), 'rem' );
			$min  = self::sanitize_fluid_number(
				Option::get_option( self::get_option_name( $i, 'min' ), '' )
			);
			$max  = self::sanitize_fluid_number(
				Option::get_option( self::get_option_name( $i, 'max' ), '' )
			);

			// 単位と数値の組み合わせが仕様外ならプリセットにしない.
			if ( ! self::is_valid_fluid_number( $min, $unit ) || ! self::is_valid_fluid_number( $max, $unit ) ) {
				continue;
			}

			// WordPressへ上下限が逆転した流体設定を渡さない.
			if ( (float) $min > (float) $max ) {
				continue;
			}

			$font_sizes[] = [
				'name'  => $label,
				'slug'  => self::get_slug( $i ),
				'size'  => $max . $unit,
				'fluid' => [
					'min' => $min . $unit,
					'max' => $max . $unit,
				],
			];
		}

		return $font_sizes;
	}

	/**
	 * カスタマイザー追加.
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 */
	public function customize_register( $wp_customize ) {
		$customizer = new Customize_Control( $wp_customize );
		$customizer->add_section_label(
			esc_html__( '文字サイズ定義', 'ystandard' ),
			[
				'id'          => 'ys_font_size_preset_section_label',
				'section'     => Block_Editor::SECTION_NAME,
				'description' => esc_html__( 'ブロックエディターで選択できる文字サイズ設定を追加できます。', 'ystandard' ),
			]
		);

		for ( $i = 1; $i <= self::USER_FONT_SIZE_LIMIT; $i ++ ) {
			// 2件目以降は前の設定との境界が分かるように余白を入れる.
			if ( 1 < $i ) {
				$customizer->add_spacer(
					[
						'id'      => 'ys_font_size_preset_' . $i . '_spacer',
						'section' => Block_Editor::SECTION_NAME,
						'size'    => 60,
					]
				);
			}

			$customizer->add_label(
				[
					'id'      => 'ys_font_size_preset_' . $i . '_label',
					'section' => Block_Editor::SECTION_NAME,
					'label'   => sprintf(
						/* translators: %d: Font size preset number. */
						esc_html__( '文字サイズ設定%d', 'ystandard' ),
						$i
					),
				]
			);
			$customizer->add_text(
				[
					'id'      => self::get_option_name( $i, 'label' ),
					'section' => Block_Editor::SECTION_NAME,
					'label'   => esc_html__( '設定名（ラベル）', 'ystandard' ),
				]
			);
			$customizer->add_toggle_group(
				[
					'id'      => self::get_option_name( $i, 'type' ),
					'section' => Block_Editor::SECTION_NAME,
					'label'   => esc_html__( '種類', 'ystandard' ),
					'default' => 'static',
					'choices' => [
						'static' => esc_html__( '固定値', 'ystandard' ),
						'fluid'  => esc_html__( '可変', 'ystandard' ),
					],
				]
			);
			$customizer->add_text(
				[
					'id'                => self::get_option_name( $i, 'static' ),
					'section'           => Block_Editor::SECTION_NAME,
					'label'             => esc_html__( '固定値', 'ystandard' ),
					'description'       => esc_html__( '単位付きで入力してください。数値のみを入力した場合は単位はpxになります。', 'ystandard' ),
					'sanitize_callback' => [ self::class, 'sanitize_static_size' ],
					'validate_callback' => [ self::class, 'validate_static_size' ],
					'active_callback'   => [ self::class, 'is_static_control_active' ],
				]
			);
			$number_args = [
				'section'           => Block_Editor::SECTION_NAME,
				'sanitize_callback' => [ self::class, 'sanitize_fluid_number' ],
				'validate_callback' => [ self::class, 'validate_fluid_number' ],
				'active_callback'   => [ self::class, 'is_fluid_control_active' ],
				'input_attrs'       => [
					'min'  => 0,
					'max'  => 999,
					'step' => self::get_initial_step( $i ),
				],
			];
			$customizer->add_toggle_group(
				[
					'id'              => self::get_option_name( $i, 'unit' ),
					'section'         => Block_Editor::SECTION_NAME,
					'label'           => esc_html__( '単位', 'ystandard' ),
					'default'         => 'rem',
					'choices'         => [
						'rem' => 'rem',
						'px'  => 'px',
					],
					'active_callback' => [ self::class, 'is_fluid_control_active' ],
				]
			);
			$customizer->add_number(
				array_merge(
					$number_args,
					[
						'id'    => self::get_option_name( $i, 'max' ),
						'label' => esc_html__( '最大（PC表示）', 'ystandard' ),
					]
				)
			);
			$customizer->add_number(
				array_merge(
					$number_args,
					[
						'id'    => self::get_option_name( $i, 'min' ),
						'label' => esc_html__( '最小（モバイル表示）', 'ystandard' ),
					]
				)
			);
		}
	}

	/**
	 * 固定値をサニタイズ.
	 *
	 * @param mixed $value 固定値.
	 *
	 * @return string
	 */
	public static function sanitize_static_size( $value ) {
		// 文字列へ安全に変換できない値は保存しない.
		if ( ! is_string( $value ) && ! is_numeric( $value ) ) {
			return '';
		}

		$value = trim( (string) $value );

		// 未入力の設定枠は保存を許可する.
		if ( '' === $value ) {
			return '';
		}

		// 数値だけの入力は、プリセット生成時にpxを補うためそのまま保存する.
		if ( is_numeric( $value ) && 0 <= (float) $value ) {
			return $value;
		}

		// 単一のCSS長さは、利用できる単位だけを保存する.
		if ( preg_match( '/\A(?:0|(?:\d+(?:\.\d+)?|\.\d+)(?:px|rem|em|%|vw|vh|vmin|vmax|ch|ex|cap|ic|lh|rlh))\z/i', $value ) ) {
			return $value;
		}

		// 固定値で受け付ける計算式はcalc()とclamp()に限定する.
		if ( ! preg_match( '/\A(?:calc|clamp)\s*\(/i', $value ) ) {
			return '';
		}

		return Layout::sanitize_css_value( $value );
	}

	/**
	 * 固定値へ必要な単位を追加.
	 *
	 * @param mixed $value 固定値.
	 *
	 * @return string
	 */
	public static function normalize_static_size( $value ) {
		$value = self::sanitize_static_size( $value );

		// 未入力または不正な固定値はプリセットにしない.
		if ( '' === $value ) {
			return '';
		}

		return is_numeric( $value ) ? $value . 'px' : $value;
	}

	/**
	 * 流体文字サイズの数値をサニタイズ.
	 *
	 * @param mixed $value 数値.
	 *
	 * @return string
	 */
	public static function sanitize_fluid_number( $value ) {
		// 文字列へ安全に変換できない値は保存しない.
		if ( ! is_string( $value ) && ! is_numeric( $value ) ) {
			return '';
		}

		$value = trim( (string) $value );

		// 未入力または範囲外の値は保存しない.
		if ( '' === $value || ! is_numeric( $value ) || 0 > (float) $value || 999 < (float) $value ) {
			return '';
		}

		return $value;
	}

	/**
	 * 固定値を検証.
	 *
	 * @param \WP_Error             $validity 検証結果.
	 * @param mixed                 $value 入力値.
	 * @param \WP_Customize_Setting $setting 設定.
	 *
	 * @return \WP_Error
	 */
	public static function validate_static_size( $validity, $value, $setting ) {
		// fluid設定では保持中の固定値を検証対象にしない.
		if ( 'static' !== self::get_related_value( $setting, 'type', 'static' ) ) {
			return $validity;
		}

		// 文字列へ変換できない入力はCSSへ保存しない.
		if ( ! is_string( $value ) && ! is_numeric( $value ) ) {
			$validity->add( 'invalid_font_size', esc_html__( '有効な文字サイズを入力してください。', 'ystandard' ) );

			return $validity;
		}

		// 未入力は未登録の設定枠として保存を許可する.
		if ( '' === trim( (string) $value ) ) {
			return $validity;
		}

		// CSSへ安全に出力できない値は保存を止める.
		if ( '' === self::sanitize_static_size( $value ) ) {
			$validity->add( 'invalid_font_size', esc_html__( '有効な文字サイズを入力してください。', 'ystandard' ) );
		}

		return $validity;
	}

	/**
	 * 流体文字サイズの数値を検証.
	 *
	 * @param \WP_Error             $validity 検証結果.
	 * @param mixed                 $value 入力値.
	 * @param \WP_Customize_Setting $setting 設定.
	 *
	 * @return \WP_Error
	 */
	public static function validate_fluid_number( $validity, $value, $setting ) {
		// 固定値設定では保持中のfluid値を検証対象にしない.
		if ( 'fluid' !== self::get_related_value( $setting, 'type', 'static' ) ) {
			return $validity;
		}

		// 文字列へ変換できない入力は数値として保存しない.
		if ( ! is_string( $value ) && ! is_numeric( $value ) ) {
			$validity->add( 'invalid_fluid_font_size', esc_html__( 'remは0.1刻み、pxは整数で、0〜999の範囲を入力してください。', 'ystandard' ) );

			return $validity;
		}

		// 未入力は未登録の設定枠として保存を許可する.
		if ( '' === trim( (string) $value ) ) {
			return $validity;
		}

		$unit = self::get_related_value( $setting, 'unit', 'rem' );
		// 単位ごとの範囲とstepに合わない値は保存を止める.
		if ( ! self::is_valid_fluid_number( $value, $unit ) ) {
			$validity->add( 'invalid_fluid_font_size', esc_html__( 'remは0.1刻み、pxは整数で、0〜999の範囲を入力してください。', 'ystandard' ) );
			return $validity;
		}

		$field         = self::get_field_from_option_name( $setting->id );
		$related_field = 'min' === $field ? 'max' : 'min';
		$related_value = self::get_related_value( $setting, $related_field, '' );

		// 両方の値が揃った時だけ上下限の関係を検証する.
		if ( self::is_valid_fluid_number( $related_value, $unit ) ) {
			$min = 'min' === $field ? $value : $related_value;
			$max = 'max' === $field ? $value : $related_value;
			// WordPressが正しいclamp()を生成できない逆転値は保存を止める.
			if ( (float) $min > (float) $max ) {
				$validity->add( 'invalid_fluid_font_size_range', esc_html__( '最小値は最大値以下にしてください。', 'ystandard' ) );
			}
		}

		return $validity;
	}

	/**
	 * 固定値コントロールを表示するか.
	 *
	 * @param \WP_Customize_Control $control コントロール.
	 *
	 * @return bool
	 */
	public static function is_static_control_active( $control ) {
		return 'static' === self::get_control_type_value( $control );
	}

	/**
	 * Fluidコントロールを表示するか.
	 *
	 * @param \WP_Customize_Control $control コントロール.
	 *
	 * @return bool
	 */
	public static function is_fluid_control_active( $control ) {
		return 'fluid' === self::get_control_type_value( $control );
	}

	/**
	 * Fluid値が単位の制約を満たすか.
	 *
	 * @param mixed  $value 数値.
	 * @param string $unit 単位.
	 *
	 * @return bool
	 */
	private static function is_valid_fluid_number( $value, $unit ) {
		$value = self::sanitize_fluid_number( $value );

		// 数値として不正な値はstepの検証へ進めない.
		if ( '' === $value ) {
			return false;
		}

		// pxは小数を許可しない.
		if ( 'px' === $unit ) {
			return floor( (float) $value ) === (float) $value;
		}

		// 想定外の単位はCSSへ出力しない.
		if ( 'rem' !== $unit ) {
			return false;
		}

		$scaled_value = (float) $value * 10;

		return abs( round( $scaled_value ) - $scaled_value ) < 0.000001;
	}

	/**
	 * 関連する設定値を取得.
	 *
	 * @param \WP_Customize_Setting $setting 設定.
	 * @param string                $field 項目名.
	 * @param mixed                 $default 初期値.
	 *
	 * @return mixed
	 */
	private static function get_related_value( $setting, $field, $default ) {
		$index       = self::get_index_from_option_name( $setting->id );
		$setting_id  = self::get_option_name( $index, $field );
		$post_values = $setting->manager->unsanitized_post_values();

		// 同じ保存操作に含まれる値を優先して、単位や種類の切り替えを正しく検証する.
		if ( array_key_exists( $setting_id, $post_values ) ) {
			return $post_values[ $setting_id ];
		}

		$related_setting = $setting->manager->get_setting( $setting_id );
		// カスタマイザーへ登録済みの関連設定があれば現在値を使用する.
		if ( $related_setting ) {
			return $related_setting->value();
		}

		return Option::get_option( $setting_id, $default );
	}

	/**
	 * コントロールが参照する種類を取得.
	 *
	 * @param \WP_Customize_Control $control コントロール.
	 *
	 * @return string
	 */
	private static function get_control_type_value( $control ) {
		$index   = self::get_index_from_option_name( $control->id );
		$setting = $control->manager->get_setting( self::get_option_name( $index, 'type' ) );

		// 種類の設定がまだ登録されていない場合は固定値として表示する.
		if ( ! $setting ) {
			return 'static';
		}

		return $setting->value();
	}

	/**
	 * 初期表示用のstepを取得.
	 *
	 * @param int $index 設定番号.
	 *
	 * @return float|int
	 */
	private static function get_initial_step( $index ) {
		$unit = Option::get_option( self::get_option_name( $index, 'unit' ), 'rem' );

		return 'px' === $unit ? 1 : 0.1;
	}

	/**
	 * Option名を取得.
	 *
	 * @param int    $index 設定番号.
	 * @param string $field 項目名.
	 *
	 * @return string
	 */
	public static function get_option_name( $index, $field ) {
		return self::OPTION_PREFIX . $index . '-' . $field;
	}

	/**
	 * Slugを取得.
	 *
	 * @param int $index 設定番号.
	 *
	 * @return string
	 */
	private static function get_slug( $index ) {
		return 'ystd-font-size-preset-' . $index;
	}

	/**
	 * Option名から設定番号を取得.
	 *
	 * @param string $option_name option名.
	 *
	 * @return int
	 */
	private static function get_index_from_option_name( $option_name ) {
		preg_match( '/^' . preg_quote( self::OPTION_PREFIX, '/' ) . '(\d+)-/', $option_name, $matches );

		return isset( $matches[1] ) ? (int) $matches[1] : 0;
	}

	/**
	 * Option名から項目名を取得.
	 *
	 * @param string $option_name option名.
	 *
	 * @return string
	 */
	private static function get_field_from_option_name( $option_name ) {
		preg_match( '/^' . preg_quote( self::OPTION_PREFIX, '/' ) . '\d+-(.+)$/', $option_name, $matches );

		return $matches[1] ?? '';
	}
}

new Block_Editor_Font_Size();
