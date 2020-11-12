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
	 * キャッシュキー
	 */
	const CACHE_KEY = 'icons';
	/**
	 * オブジェクトキャッシュキー
	 */
	const OBJECT_CACHE_KEY = 'ys_icons';

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
		$icon = self::get_icon_cache( $name );
		if ( false === $icon ) {
			$path = get_template_directory() . '/library/feather/' . $name . '.svg';
			if ( ! file_exists( $path ) ) {
				return '';
			}
			$icon = Utility::file_get_contents( $path );
			if ( ! empty( $icon ) ) {

			}
		}

		return self::wrap_icon_html( $icon );
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
		$icon  = self::get_sns_icon_cache( $name );
		$title = empty( $title ) ? $icon['title'] : $title;
		$path  = $icon['path'];
		$class = "icon--${name}";
		$icon  = "<svg class=\"${class}\" role=\"img\" viewBox=\"0 0 24 24\" xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" aria-hidden=\"true\" focusable=\"false\"><title>${title}</title><path d=\"${path}\"/></svg>";

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

		return "<span class=\"${html_class}\">${icon}</span>";
	}

	/**
	 * 全SNSアイコン取得
	 *
	 * @return array
	 */
	public static function get_all_sns_icons() {
		$all_icons = self::get_all_icons_cache();
		if ( isset( $all_icons['sns'] ) && ! empty( $all_icons['sns'] ) ) {
			return $all_icons['sns'];
		}

		$path  = get_template_directory() . '/library/simple-icons/brand-icons.json';
		$json  = Utility::file_get_contents( $path );
		$data  = json_decode( $json, true );
		$icons = [];
		foreach ( $data['data'] as $key => $value ) {
			$icons[ $key ] = $value;
		}
		if ( ! empty( $icons ) ) {
			self::add_sns_icon_cache( $icons );
		}

		return $icons;
	}

	/**
	 * アイコンキャッシュ用配列取得
	 *
	 * @return array
	 */
	public static function get_icon_cache_schema() {
		return [
			'icon' => [],
			'sns'  => [],
		];
	}

	/**
	 * アイコンの存在確認
	 *
	 * @param array $icons Icons.
	 *
	 * @return bool
	 */
	public static function is_empty_icons( $icons ) {
		if ( empty( $icons ) ) {
			return true;
		}
		if ( ! isset( $icons['icon'] ) && ! isset( $icons['sns'] ) ) {
			return true;
		}
		if ( ! isset( $icons['icon'] ) ) {
			$icons['icon'] = [];
		}
		if ( ! isset( $icons['sns'] ) ) {
			$icons['sns'] = [];
		}
		if ( empty( $icons['icon'] ) && empty( $icons['sns'] ) ) {
			return true;
		}

		return true;
	}

	/**
	 * キャッシュから全アイコンを取得
	 *
	 * @return array|bool
	 */
	public static function get_all_icons_cache() {
		$icons = wp_cache_get( self::OBJECT_CACHE_KEY );
		if ( false === $icons ) {
			$icons = Cache::get_cache( self::CACHE_KEY, [] );
			if ( false !== $icons ) {
				wp_cache_add( self::OBJECT_CACHE_KEY, $icons );
			}
		}

		return $icons;
	}

	/**
	 * キャッシュからアイコン取得
	 *
	 * @param string $name Name.
	 *
	 * @return string|bool
	 */
	public static function get_icon_cache( $name ) {
		$icons = self::get_all_icons_cache();
		if ( false === $icons ) {
			return false;
		}
		if ( ! isset( $icons['icon'] ) ) {
			return false;
		}
		if ( ! isset( $icons['icon'][ $name ] ) ) {
			return false;
		}

		return $icons['icon'][ $name ];
	}

	/**
	 * キャッシュからSNSアイコン取得
	 *
	 * @param string $name Name.
	 *
	 * @return string|bool
	 */
	public static function get_sns_icon_cache( $name ) {
		$icons = self::get_all_icons_cache();
		if ( isset( $icons['sns'][ $name ] ) ) {
			return $icons['sns'][ $name ];
		}

		$icons = self::get_all_sns_icons();

		return $icons[ $name ];
	}

	/**
	 * アイコンのキャッシュセット
	 *
	 * @param string $name icon name.
	 * @param string $icon icon.
	 */
	public static function add_icon_cache( $name, $icon ) {
		$icons_cache = self::get_all_icons_cache();
		if ( ! isset( $icons_cache['icon'] ) ) {
			$icons_cache['icon'] = [];
		}
		$icons_cache['icon'][ $name ] = $icon;
		Cache::set_cache( self::CACHE_KEY, $icons_cache, [], 30, true );
	}

	/**
	 * SNSアイコンのキャシュセット
	 *
	 * @param array $icons icons.
	 */
	public static function add_sns_icon_cache( $icons ) {
		$icons_cache        = self::get_all_icons_cache();
		$icons_cache['sns'] = $icons;
		Cache::set_cache( self::CACHE_KEY, $icons_cache, [], 30, true );
	}
}

$class_icon = new Icon();
$class_icon->register();
