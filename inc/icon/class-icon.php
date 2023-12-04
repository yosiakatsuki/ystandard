<?php
/**
 * アイコン
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */

namespace ystandard;

use ystandard\utils\File;

defined( 'ABSPATH' ) || die();

/**
 * Class Icon
 *
 * @package ystandard
 */
class Icon {

	/**
	 * SNSアイコン ショートコードパラメーター
	 */
	const SHORTCODE_ATTR_SNS = [
		'name'  => '',
		'title' => '',
	];
	/**
	 * アイコン ショートコードパラメーター
	 */
	const SHORTCODE_ATTR_ICON = [
		'name'  => '',
		'class' => '',
	];


	/**
	 * Constructor.
	 */
	public function __construct() {
		// ショートコードの登録.
		$this->register_shortcodes();
	}


	/**
	 * アイコン取得
	 *
	 * @param string $name name.
	 * @param string $class class.
	 *
	 * @return string
	 */
	public static function get_icon( $name, $class = '' ) {
		$icon = Icon_Cache::get_icon_cache( $name );

		// キャッシュがあればそれを返す.
		if ( false !== $icon ) {
			return self::wrap_icon_html( $icon );
		}

		// アイコンのSVGファイルパスを取得.
		$path = get_theme_file_path( "library/feather/{$name}.svg" );
		$path = apply_filters( "ys_get_icon_path__{$name}", $path, $name );

		if ( ! file_exists( $path ) ) {
			return '';
		}
		// SVGの中身を取得.
		$icon = File::file_get_contents( $path );

		// キャッシュ登録.
		if ( ! empty( $icon ) ) {
			Icon_Cache::add_icon_cache( $name, $icon );
		}

		return self::wrap_icon_html( $icon );
	}

	/**
	 * SNSアイコン取得
	 *
	 * @param string $name  name.
	 * @param string $title title.
	 *
	 * @return string
	 */
	public static function get_sns_icon( $name, $title = '' ) {
		// キャッシュから取得.
		$icon  = Icon_Cache::get_sns_icon_cache( $name );
		// 各属性の取得・作成.
		$title = empty( $title ) ? $icon['title'] : $title;
		$path  = $icon['path'];
		$class = "icon--{$name}";
		// SVG作成.
		$icon  = "<svg class=\"{$class}\" role=\"img\" viewBox=\"0 0 24 24\" xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" aria-hidden=\"true\" focusable=\"false\"><title>{$title}</title><path d=\"{$path}\"/></svg>";

		return self::wrap_icon_html( $icon, 'sns-icon' );
	}


	/**
	 * アイコン表示用HTML取得
	 *
	 * @param string $icon  Icon.
	 * @param string $class Class.
	 *
	 * @return string
	 */
	public static function wrap_icon_html( $icon, $class = '' ) {

		if ( is_array( $class ) ) {
			$class = trim( implode( ' ', $class ) );
		}
		$html_class = [
			'ys-icon',
			$class,
		];
		$html_class = trim( implode( ' ', $html_class ) );

		return "<span class=\"{$html_class}\">{$icon}</span>";
	}


	/**
	 * アイコン ショートコード実行
	 *
	 * @param array $attributes Attributes.
	 *
	 * @return string
	 */
	public function do_shortcode_icon( $attributes ) {
		$attributes = shortcode_atts( self::SHORTCODE_ATTR_ICON, $attributes );

		if ( empty( $attributes['name'] ) ) {
			return '';
		}

		return self::get_icon( $attributes['name'], $attributes['class'] );
	}

	/**
	 * SNSアイコン ショートコード実行
	 *
	 * @param array $atts Attributes.
	 *
	 * @return string
	 */
	public function do_shortcode_sns( $attributes ) {
		$attributes = shortcode_atts( self::SHORTCODE_ATTR_SNS, $attributes );
		if ( empty( $attributes['name'] ) ) {
			return '';
		}

		return self::get_sns_icon( $attributes['name'], $attributes['title'] );
	}

	/**
	 * ショートコードの登録
	 * @return void
	 */
	public function register_shortcodes() {
		if ( ! shortcode_exists( 'ys_sns_icon' ) ) {
			add_shortcode( 'ys_sns_icon', [ $this, 'do_shortcode_sns' ] );
		}
		if ( ! shortcode_exists( 'ys_icon' ) ) {
			add_shortcode( 'ys_icon', [ $this, 'do_shortcode_icon' ] );
		}
	}
}

new Icon();
