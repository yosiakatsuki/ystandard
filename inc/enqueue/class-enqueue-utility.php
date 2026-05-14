<?php
/**
 * CSSカスタムプロパティ用クラス
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

/**
 * Class Enqueue_Utility
 *
 * @package ystandard
 */
class Enqueue_Utility {

	/**
	 * CSSカスタムプロパティセット用フィルター名
	 */
	const FILTER_CSS_VARS = 'ys_css_vars';

	/**
	 * CSSカスタムプロパティ preset 上書き用 フィルター名
	 */
	const FILTER_CSS_VARS_PRESETS = 'ys_css_vars_presets';

	/**
	 * インラインCSSのフック名
	 */
	const FILTER_INLINE_CSS = 'ys_get_inline_css';

	/**
	 * ブロック用インラインCSSのフック名
	 */
	const FILTER_BLOCKS_INLINE_CSS = 'ys_get_blocks_inline_css';

	/**
	 * CSSカスタムプロパティ追加用配列の取得
	 *
	 * @param string $name    変数名.
	 * @param string $value   値.
	 * @param mixed  $default 初期値.
	 *
	 * @return array
	 */
	public static function get_css_var( $name, $value, $default = null ) {
		// 初期値と同じ場合無効.
		if ( ! is_null( $default ) && $value === $default ) {
			$value = '';
		}

		return [ $name => $value ];
	}

	/**
	 * Add defer.
	 *
	 * @param string $handle handle.
	 */
	public static function add_defer( $handle ) {
		self::add_loading_strategy( $handle, 'defer' );
		wp_script_add_data( $handle, 'defer', true );
	}

	/**
	 * Add async.
	 *
	 * @param string $handle handle.
	 */
	public static function add_async( $handle ) {
		self::add_loading_strategy( $handle, 'async' );
		wp_script_add_data( $handle, 'async', true );
	}

	/**
	 * Add script loading strategy.
	 *
	 * @param string $handle   handle.
	 * @param string $strategy strategy.
	 */
	private static function add_loading_strategy( $handle, $strategy ) {
		if ( ! self::can_add_loading_strategy( $handle ) ) {
			return;
		}
		if ( 'defer' === $strategy && wp_scripts()->get_data( $handle, 'async' ) ) {
			return;
		}
		wp_script_add_data( $handle, 'strategy', $strategy );
	}

	/**
	 * Check if script loading strategy can be added.
	 *
	 * @param string $handle handle.
	 *
	 * @return bool
	 */
	private static function can_add_loading_strategy( $handle ) {
		if ( version_compare( get_bloginfo( 'version' ), '6.3', '<' ) ) {
			return false;
		}

		$scripts = wp_scripts();
		if ( ! isset( $scripts->registered[ $handle ] ) ) {
			return false;
		}

		return ! empty( $scripts->registered[ $handle ]->src );
	}

	/**
	 * Add crossorigin.
	 *
	 * @param string $handle handle.
	 * @param string $value  value.
	 */
	public static function add_crossorigin( $handle, $value ) {
		wp_script_add_data( $handle, 'crossorigin', $value );
	}

	/**
	 * Add custom-element.
	 *
	 * @param string $handle handle.
	 * @param string $value  value.
	 */
	public static function add_custom_element( $handle, $value ) {
		wp_script_add_data( $handle, 'custom-element', $value );
	}
}
