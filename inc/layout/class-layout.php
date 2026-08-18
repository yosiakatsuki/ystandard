<?php
/**
 * レイアウト設定.
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

use ystandard\utils\CSS;

defined( 'ABSPATH' ) || die();

/**
 * Class Layout
 *
 * @package ystandard
 */
class Layout {
	/**
	 * コンテンツ領域設定ラベル名.
	 */
	private const CONTENT_LABEL_NAME = 'ys_layout_content_section_label';

	/**
	 * コンテンツ幅設定名.
	 */
	private const CONTENT_WIDTH_OPTION_NAME = 'ys_content_width';

	/**
	 * コンテナ幅設定名.
	 */
	private const CONTAINER_WIDTH_OPTION_NAME = 'ys_container_width';

	/**
	 * セクション名.
	 */
	const SECTION_NAME = 'ys_layout';

	/**
	 * Layout constructor.
	 */
	public function __construct() {
		add_action( 'customize_register', [ $this, 'customize_register' ], 9 );
		add_filter( 'wp_theme_json_data_user', [ self::class, 'add_custom_layout_to_theme_json' ] );
	}

	/**
	 * カスタマイザー追加.
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 */
	public function customize_register( $wp_customize ) {
		$customizer = new Customize_Control( $wp_customize );

		$customizer->add_section(
			[
				'section'     => self::SECTION_NAME,
				'title'       => '[ys]' . __( 'レイアウト', 'ystandard' ),
				'description' => __( 'サイト全体のレイアウトに関する設定', 'ystandard' ),
			]
		);

		$customizer->add_section_label(
			__( 'コンテンツ領域', 'ystandard' ),
			[
				'id'          => self::CONTENT_LABEL_NAME,
				'section'     => self::SECTION_NAME,
				'description' => __( 'サイト全体のコンテンツ領域に関する設定', 'ystandard' ),
			]
		);

		$description = __( '単位付きで入力してください。数値のみを入力した場合は単位はpxになります。', 'ystandard' );
		$customizer->add_text(
			[
				'id'                => self::CONTENT_WIDTH_OPTION_NAME,
				'section'           => self::SECTION_NAME,
				'label'             => __( 'コンテンツ幅', 'ystandard' ),
				'description'       => $description,
				'placeholder'       => self::get_global_layout_value( 'contentSize' ),
				'sanitize_callback' => [ self::class, 'sanitize_css_value' ],
			]
		);

		$customizer->add_text(
			[
				'id'                => self::CONTAINER_WIDTH_OPTION_NAME,
				'section'           => self::SECTION_NAME,
				'label'             => __( 'コンテナ幅', 'ystandard' ),
				'description'       => $description,
				'placeholder'       => self::get_global_layout_value( 'wideSize' ),
				'sanitize_callback' => [ self::class, 'sanitize_css_value' ],
			]
		);
	}

	/**
	 * カスタマイザーの幅設定をGlobal Stylesへ追加.
	 *
	 * @param \WP_Theme_JSON_Data $theme_json Theme JSONデータ.
	 * @return \WP_Theme_JSON_Data
	 */
	public static function add_custom_layout_to_theme_json( $theme_json ) {
		$content_width   = self::normalize_css_value( Option::get_option( self::CONTENT_WIDTH_OPTION_NAME, '' ) );
		$container_width = self::normalize_css_value( Option::get_option( self::CONTAINER_WIDTH_OPTION_NAME, '' ) );
		$layout          = [];

		// コンテンツ幅が設定されている場合だけtheme.jsonの値を上書きする.
		if ( '' !== $content_width ) {
			$layout['contentSize'] = $content_width;
		}

		// コンテナ幅が設定されている場合だけtheme.jsonの値を上書きする.
		if ( '' !== $container_width ) {
			$layout['wideSize'] = $container_width;
		}

		// 未設定時はテーマやユーザーが持つ既存のGlobal Stylesを変更しない.
		if ( empty( $layout ) ) {
			return $theme_json;
		}

		return $theme_json->update_with(
			[
				'version'  => 3,
				'settings' => [
					'layout' => $layout,
				],
			]
		);
	}

	/**
	 * レイアウト用CSS値をサニタイズ.
	 *
	 * @param mixed $value CSS値.
	 * @return string
	 */
	public static function sanitize_css_value( $value ): string {
		// 文字列へ安全に変換できない値は保存しない.
		if ( ! is_string( $value ) && ! is_numeric( $value ) ) {
			return '';
		}

		$value = trim( (string) $value );

		// 未入力時はtheme.jsonまたはSCSSの初期値を使用する.
		if ( '' === $value ) {
			return '';
		}

		// CSS宣言の追加やコメントによる入力値の改変を防ぐ.
		if ( false !== strpos( $value, '/*' ) || false !== strpos( $value, '*/' ) ) {
			return '';
		}

		// 長さ・割合・計算式に不要な文字を含む値はCSSへ出力しない.
		if ( ! preg_match( '/\A[0-9a-zA-Z.%+\-*\/(),\s_]+\z/', $value ) ) {
			return '';
		}

		// 閉じ括弧が先行する不正な計算式は使用しない.
		if ( ! self::has_valid_parentheses( $value ) ) {
			return '';
		}

		preg_match_all( '/([a-zA-Z][a-zA-Z0-9-]*)\s*\(/', $value, $matches );
		$allowed_functions = [ 'calc', 'clamp', 'max', 'min', 'var' ];
		foreach ( $matches[1] as $function_name ) {
			// CSS長さの計算に不要な関数は、外部参照などを避けるため許可しない.
			if ( ! in_array( strtolower( $function_name ), $allowed_functions, true ) ) {
				return '';
			}
		}

		return $value;
	}

	/**
	 * CSS値を検証し、必要に応じて単位を追加.
	 *
	 * @param mixed $value CSS値.
	 * @return string
	 */
	public static function normalize_css_value( $value ): string {
		$value = self::sanitize_css_value( $value );

		// 無効値や未設定値はCSSへ出力しない.
		if ( '' === $value ) {
			return '';
		}

		return CSS::check_and_add_unit( $value );
	}

	/**
	 * 現在のGlobal Stylesからレイアウト値を取得.
	 *
	 * @param string $setting_name 設定名.
	 * @return string
	 */
	private static function get_global_layout_value( string $setting_name ): string {
		// Global Styles APIが利用できないWordPressではプレースホルダーを表示しない.
		if ( ! function_exists( 'wp_get_global_settings' ) ) {
			return '';
		}

		$value = wp_get_global_settings( [ 'layout', $setting_name ] );

		return self::normalize_css_value( $value );
	}

	/**
	 * CSS値の括弧が正しく閉じているか.
	 *
	 * @param string $value CSS値.
	 * @return bool
	 */
	private static function has_valid_parentheses( string $value ): bool {
		$depth = 0;
		for ( $i = 0, $length = strlen( $value ); $i < $length; $i ++ ) {
			// 関数の開始を記録して、入れ子の計算式も検証する.
			if ( '(' === $value[ $i ] ) {
				++$depth;
			}

			// 関数の終了を記録し、開始より多い閉じ括弧を検出する.
			if ( ')' === $value[ $i ] ) {
				--$depth;
			}

			// 閉じ括弧が先行した値はCSSとして成立しない.
			if ( 0 > $depth ) {
				return false;
			}
		}

		return 0 === $depth;
	}
}

new Layout();
