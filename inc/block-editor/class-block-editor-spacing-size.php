<?php
/**
 * ブロックエディターの余白サイズ定義.
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

/**
 * Class Block_Editor_Spacing_Size
 *
 * @package ystandard
 */
class Block_Editor_Spacing_Size {

	/**
	 * ユーザー定義余白サイズの上限.
	 */
	const USER_SPACING_SIZE_LIMIT = 6;

	/**
	 * 設定名の接頭辞.
	 */
	const OPTION_PREFIX = 'ys-block-editor-spacing-preset-';

	/**
	 * Block_Editor_Spacing_Size constructor.
	 */
	public function __construct() {
		add_filter( 'wp_theme_json_data_user', [ $this, 'add_user_spacing_sizes_to_theme_json' ] );
		add_action( 'customize_register', [ $this, 'customize_register' ] );
	}

	/**
	 * ユーザー定義余白サイズをtheme.jsonのユーザー設定へ追加.
	 *
	 * @param \WP_Theme_JSON_Data $theme_json Theme.jsonデータ.
	 *
	 * @return \WP_Theme_JSON_Data
	 */
	public function add_user_spacing_sizes_to_theme_json( $theme_json ) {
		$data                   = $theme_json->get_data();
		$existing_spacing_sizes = $data['settings']['spacing']['spacingSizes']['custom'] ?? [];
		$managed_slugs          = [];

		for ( $i = 1; $i <= self::USER_SPACING_SIZE_LIMIT; $i ++ ) {
			$managed_slugs[] = self::get_slug( $i );
		}

		$filtered_spacing_sizes = array_values(
			array_filter(
				$existing_spacing_sizes,
				function ( $spacing_size ) use ( $managed_slugs ) {
					// 既存データが想定外の形式でも、この機能の管理対象でなければ保持する.
					if ( ! is_array( $spacing_size ) ) {
						return true;
					}

					return empty( $spacing_size['slug'] ) || ! in_array( $spacing_size['slug'], $managed_slugs, true );
				}
			)
		);
		$user_spacing_sizes     = self::get_user_spacing_sizes();

		// ユーザー設定も古い管理対象slugもなければ、元のtheme.jsonを変更しない.
		if ( $existing_spacing_sizes === $filtered_spacing_sizes && empty( $user_spacing_sizes ) ) {
			return $theme_json;
		}

		return $theme_json->update_with(
			[
				'version'  => 3,
				'settings' => [
					'spacing' => [
						'spacingSizes' => array_merge( $user_spacing_sizes, $filtered_spacing_sizes ),
					],
				],
			]
		);
	}

	/**
	 * 有効なユーザー定義余白サイズを取得.
	 *
	 * @return array
	 */
	public static function get_user_spacing_sizes() {
		$spacing_sizes = [];

		for ( $i = 1; $i <= self::USER_SPACING_SIZE_LIMIT; $i ++ ) {
			$label = sanitize_text_field( Option::get_option( self::get_option_name( $i, 'label' ), '' ) );
			$size  = self::normalize_spacing_size(
				Option::get_option( self::get_option_name( $i, 'value' ), '' )
			);

			// ラベルまたは値が不足している設定枠は、未登録として扱う.
			if ( '' === $label || '' === $size ) {
				continue;
			}

			$spacing_sizes[] = [
				'name' => $label,
				'slug' => self::get_slug( $i ),
				'size' => $size,
			];
		}

		return $spacing_sizes;
	}

	/**
	 * カスタマイザー追加.
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 */
	public function customize_register( $wp_customize ) {
		$customizer = new Customize_Control( $wp_customize );
		$customizer->add_section_label(
			esc_html__( '余白定義', 'ystandard' ),
			[
				'id'          => 'ys_spacing_preset_section_label',
				'section'     => Block_Editor::SECTION_NAME,
				'description' => esc_html__( 'ブロックエディターで選択できる余白設定を追加できます。', 'ystandard' ),
			]
		);

		for ( $i = 1; $i <= self::USER_SPACING_SIZE_LIMIT; $i ++ ) {
			// 2件目以降は前の設定との境界が分かるように余白を入れる.
			if ( 1 < $i ) {
				$customizer->add_spacer(
					[
						'id'      => 'ys_spacing_preset_' . $i . '_spacer',
						'section' => Block_Editor::SECTION_NAME,
						'size'    => 60,
					]
				);
			}

			$customizer->add_label(
				[
					'id'      => 'ys_spacing_preset_' . $i . '_label',
					'section' => Block_Editor::SECTION_NAME,
					'label'   => sprintf(
						/* translators: %d: Spacing preset number. */
						esc_html__( '余白設定%d', 'ystandard' ),
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
			$customizer->add_text(
				[
					'id'                => self::get_option_name( $i, 'value' ),
					'section'           => Block_Editor::SECTION_NAME,
					'label'             => esc_html__( '値', 'ystandard' ),
					'description'       => esc_html__( '単位付きで入力してください。数値のみを入力した場合は単位はpxになります。', 'ystandard' ),
					'sanitize_callback' => [ self::class, 'sanitize_spacing_size' ],
					'validate_callback' => [ self::class, 'validate_spacing_size' ],
				]
			);
		}
	}

	/**
	 * 余白サイズをサニタイズ.
	 *
	 * @param mixed $value 余白サイズ.
	 *
	 * @return string
	 */
	public static function sanitize_spacing_size( $value ) {
		// 文字列へ安全に変換できない値は保存しない.
		if ( ! is_string( $value ) && ! is_numeric( $value ) ) {
			return '';
		}

		$value = trim( (string) $value );

		// 未入力の設定枠は保存を許可する.
		if ( '' === $value ) {
			return '';
		}

		// 0以上の数値は、プリセット生成時にpxを補うためそのまま保存する.
		if ( preg_match( '/\A(?:\d+(?:\.\d+)?|\.\d+)\z/', $value ) ) {
			return $value;
		}

		// 単一のCSS長さは、0以上で利用できる単位だけを保存する.
		if ( preg_match( '/\A(?:0|(?:\d+(?:\.\d+)?|\.\d+)(?:px|rem|em|%|vw|vh|vmin|vmax|ch|ex|cap|ic|lh|rlh))\z/i', $value ) ) {
			return $value;
		}

		// 複合値は余白サイズを返せるCSS計算関数に限定する.
		if ( ! preg_match( '/\A(?:calc|clamp|min|max)\s*\(/i', $value ) ) {
			return '';
		}

		return Layout::sanitize_css_value( $value );
	}

	/**
	 * 余白サイズへ必要な単位を追加.
	 *
	 * @param mixed $value 余白サイズ.
	 *
	 * @return string
	 */
	public static function normalize_spacing_size( $value ) {
		$value = self::sanitize_spacing_size( $value );

		// 未入力または不正な値はプリセットにしない.
		if ( '' === $value ) {
			return '';
		}

		return preg_match( '/\A(?:\d+(?:\.\d+)?|\.\d+)\z/', $value ) ? $value . 'px' : $value;
	}

	/**
	 * 余白サイズを検証.
	 *
	 * @param \WP_Error             $validity 検証結果.
	 * @param mixed                 $value 入力値.
	 * @param \WP_Customize_Setting $setting 設定.
	 *
	 * @return \WP_Error
	 */
	public static function validate_spacing_size( $validity, $value, $setting ) {
		unset( $setting );

		// 文字列へ変換できない入力はCSSへ保存しない.
		if ( ! is_string( $value ) && ! is_numeric( $value ) ) {
			$validity->add( 'invalid_spacing_size', esc_html__( '有効な余白サイズを入力してください。', 'ystandard' ) );

			return $validity;
		}

		// 未入力は未登録の設定枠として保存を許可する.
		if ( '' === trim( (string) $value ) ) {
			return $validity;
		}

		// CSSへ安全に出力できない値は保存を止める.
		if ( '' === self::sanitize_spacing_size( $value ) ) {
			$validity->add( 'invalid_spacing_size', esc_html__( '有効な余白サイズを入力してください。', 'ystandard' ) );
		}

		return $validity;
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
		return 'ystd-spacing-preset-' . $index;
	}
}

new Block_Editor_Spacing_Size();
