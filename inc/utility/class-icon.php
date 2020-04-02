<?php
/**
 * アイコン関連
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

/**
 * Class Icon
 *
 * @package ystandard
 */
class Icon {

	/**
	 * ショートコードパラメーター
	 */
	const SHORTCODE_ATTR_SNS = [
		'name'  => '',
		'title' => '',
	];
	/**
	 * ショートコードパラメーター
	 */
	const SHORTCODE_ATTR_ICON = [
		'name'  => '',
		'class' => '',
	];

	/**
	 * キャッシュキー : sns
	 */
	const CACHE_KEY_SNS = 'ys_icons_sns';
	/**
	 * キャッシュキー : アイコン
	 */
	const CACHE_KEY_ICON_PREFIX = 'ys_icons_';

	/**
	 * フックやショートコードの登録
	 */
	public function register() {
		if ( ! shortcode_exists( 'ys_sns_icon' ) ) {
			add_shortcode( 'ys_sns_icon', [ $this, 'do_shortcode_sns' ] );
		}
		if ( ! shortcode_exists( 'ys_icon' ) ) {
			add_shortcode( 'ys_icon', [ $this, 'do_shortcode_icon' ] );
		}
	}

	/**
	 * ショートコード実行
	 *
	 * @param array $atts    Attributes.
	 * @param null  $content Content.
	 *
	 * @return string
	 */
	public function do_shortcode_icon( $atts, $content = null ) {
		$atts = shortcode_atts( self::SHORTCODE_ATTR_ICON, $atts );
		if ( empty( $atts['name'] ) ) {
			return '';
		}

		return self::get_icon( $atts['name'], $atts['class'] );
	}

	/**
	 * SNSアイコン取得
	 *
	 * @param string $name  name.
	 * @param string $class class.
	 *
	 * @return string
	 */
	public static function get_icon( $name, $class = '' ) {
		$cache_key = self::CACHE_KEY_ICON_PREFIX . $name;
		$icon      = wp_cache_get( $cache_key );
		if ( false === $icon ) {
			$path = get_template_directory() . '/library/feather/' . $name . '.svg';
			if ( ! file_exists( $path ) ) {
				return '';
			}
			$icon = Utility::file_get_contents( $path );
			if ( ! empty( $icon ) ) {
				wp_cache_add( $cache_key, $icon );
			}
		}

		$html_class = [
			'ys-icon',
			$class,
		];
		$html_class = trim( implode( ' ', $html_class ) );

		return "<span class=\"${$html_class}\">${icon}</span>";
	}

	/**
	 * ショートコード実行
	 *
	 * @param array $atts    Attributes.
	 * @param null  $content Content.
	 *
	 * @return string
	 */
	public function do_shortcode_sns( $atts, $content = null ) {
		$atts = shortcode_atts( self::SHORTCODE_ATTR_SNS, $atts );
		if ( empty( $atts['name'] ) ) {
			return '';
		}

		return self::get_sns_icon( $atts['name'], $atts['title'] );
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
		$icons = self::get_all_sns_icons();
		if ( empty( $icons ) || ! isset( $icons[ $name ] ) ) {
			return '';
		}
		$title = empty( $title ) ? $icons[ $name ]['title'] : $title;
		$path  = $icons[ $name ]['path'];
		$class = "sns-icon icon--${name}";

		return "<svg class=\"${class}\" role=\"img\" viewBox=\"0 0 24 24\" xmlns=\"http://www.w3.org/2000/svg\" aria-hidden=\"true\" focusable=\"false\"><title>${title}</title><path d=\"${path}\"/></svg>";
	}

	/**
	 * 全SNSアイコン取得
	 *
	 * @return array
	 */
	public static function get_all_sns_icons() {
		$icons = wp_cache_get( self::CACHE_KEY_SNS );
		if ( false === $icons ) {
			$path  = get_template_directory() . '/library/simple-icons/brand-icons.json';
			$json  = Utility::file_get_contents( $path );
			$data  = json_decode( $json, true );
			$icons = [];
			foreach ( $data['data'] as $key => $value ) {
				$icons[ $key ] = $value;
			}
			if ( ! empty( $icons ) ) {
				wp_cache_add( self::CACHE_KEY_SNS, $icons );
			}
		}

		return $icons;
	}

}

$class_icon = new Icon();
$class_icon->register();
