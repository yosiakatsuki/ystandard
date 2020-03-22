<?php
/**
 * CSS,JavaScript読み込み関連 設定など
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * Class YS_Scripts_Config
 */
class YS_Scripts_Config {
	/**
	 * インラインCSS読み込み時のダミーCSS
	 */
	const CSS_HANDLE_DUMMY = 'ystandard';
	/**
	 * メインCSS
	 */
	const CSS_HANDLE_MAIN = 'ystandard-style';
	/**
	 * メインJS
	 */
	const SCRIPT_HANDLE_MAIN = 'ystandard-script';

	/**
	 * EnqueueするCSSのリスト
	 */
	public static function get_enqueue_css_files() {
		$inline_css = new YS_Inline_Css();
		/**
		 * CSSのリスト
		 */
		$styles = array(
			array(
				'handle'  => 'adminbar-css',
				'src'     => get_template_directory_uri() . '/css/ystandard-adminbar.css',
				'deps'    => array(),
				'ver'     => ys_get_theme_version( true ),
				'media'   => 'all',
				'enqueue' => is_admin_bar_showing(),
				'type'    => 'enqueue', // enqueue or inline.
				'inline'  => false, // true, false, handle.
			),

		);

		$styles = apply_filters( 'ys_pre_enqueue_style_css', $styles );



		return apply_filters( 'ys_get_enqueue_css_files', $styles );
	}

}
