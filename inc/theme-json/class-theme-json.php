<?php
/**
 * Theme Json関連
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

/**
 * Class Theme_Json.
 */
class Theme_Json {

	/**
	 * キャッシュ.
	 *
	 * @var array
	 */
	protected static $theme_json_file_cache = [];

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'ys_get_ystandard_theme_json_data', [ __CLASS__, 'get_ystandard_theme_json_data' ] );
	}

	/**
	 * テーマ本体のtheme.jsonの内容を取得する.
	 *
	 * @return array
	 */
	public static function get_ystandard_theme_json_data() {

		$file_path = get_template_directory() . '/theme.json';
		// ファイルチェック.
		if ( file_exists( $file_path ) ) {
			// ファイルのキャッシュを確認.
			if ( array_key_exists( $file_path, static::$theme_json_file_cache ) ) {
				return static::$theme_json_file_cache[ $file_path ];
			}
			$decoded_file = wp_json_file_decode( $file_path, [ 'associative' => true ] );
			// デコードできたらキャッシュに保存&返却.
			if ( is_array( $decoded_file ) ) {
				static::$theme_json_file_cache[ $file_path ] = $decoded_file;

				return static::$theme_json_file_cache[ $file_path ];
			}
		}

		return [];
	}
}

new Theme_Json();
