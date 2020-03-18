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
				'handle'  => 'font-awesome',
				'src'     => ys_get_font_awesome_css_url(),
				'deps'    => array(),
				'ver'     => ystandard\Utility::get_font_awesome_version(),
				'media'   => 'all',
				'enqueue' => ( 'css' === ys_get_option( 'ys_enqueue_icon_font_type', 'js' ) && ! ys_is_amp() ),
				'type'    => 'enqueue', // enqueue or inline.
				'inline'  => false, // true, false, handle.
			),
			array(
				'handle'  => self::CSS_HANDLE_MAIN,
				'src'     => YS_Scripts::get_enqueue_css_file_uri(),
				'deps'    => array(),
				'ver'     => ys_get_theme_version( true ),
				'media'   => 'all',
				'enqueue' => true,
				'type'    => 'enqueue', // enqueue or inline.
				'inline'  => true, // true, false, handle.
			),
			array(
				'handle'  => 'ys-customizer',
				'src'     => $inline_css->get_inline_css(),
				'deps'    => array(),
				'ver'     => ys_get_theme_version( true ),
				'media'   => 'all',
				'enqueue' => true,
				'type'    => 'inline', // enqueue or inline.
				'inline'  => self::CSS_HANDLE_MAIN, // true, false, handle.
			),
			array(
				'handle'  => 'ys-editor-font-size',
				'src'     => YS_Inline_Css::get_editor_font_size_css(),
				'deps'    => array(),
				'ver'     => ys_get_theme_version( true ),
				'media'   => 'all',
				'enqueue' => true,
				'type'    => 'inline', // enqueue or inline.
				'inline'  => self::CSS_HANDLE_MAIN, // true, false, handle.
			),
			array(
				'handle'  => 'ys-editor-color-palette',
				'src'     => YS_Inline_Css::get_editor_color_palette(),
				'deps'    => array(),
				'ver'     => ys_get_theme_version( true ),
				'media'   => 'all',
				'enqueue' => true,
				'type'    => 'inline', // enqueue or inline.
				'inline'  => self::CSS_HANDLE_MAIN, // true, false, handle.
			),
			array(
				'handle'  => 'ys-custom-css',
				'src'     => wp_get_custom_css(),
				'deps'    => array(),
				'ver'     => ys_get_theme_version( true ),
				'media'   => 'all',
				'enqueue' => ( ! is_customize_preview() ),
				'type'    => 'inline', // enqueue or inline.
				'inline'  => self::CSS_HANDLE_MAIN, // true, false, handle.
			),
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
			array(
				'handle'  => 'ys-amp-fontawesome',
				'src'     => ys_get_font_awesome_cdn_css_url(),
				'deps'    => array(),
				'ver'     => ystandard\Utility::get_font_awesome_version(),
				'media'   => 'all',
				'enqueue' => ys_is_amp(),
				'type'    => 'enqueue', // enqueue or inline.
				'inline'  => false, // true, false, handle.
			),
		);

		$styles = apply_filters( 'ys_pre_enqueue_style_css', $styles );

		$styles[] = array(
			'handle'  => 'style-css',
			'src'     => get_stylesheet_uri(),
			'deps'    => array(),
			'ver'     => ys_get_theme_version( true ),
			'media'   => 'all',
			'enqueue' => true,
			'type'    => 'enqueue', // enqueue or inline.
			'inline'  => true, // true, false, handle.
		);

		return apply_filters( 'ys_get_enqueue_css_files', $styles );
	}

	/**
	 * ロードするJSのリスト
	 */
	public static function get_enqueue_script_files() {
		$scripts = array(
			array(
				'handle'    => self::SCRIPT_HANDLE_MAIN,
				'src'       => get_template_directory_uri() . '/js/ystandard.js',
				'deps'      => array(),
				'ver'       => ys_get_theme_version( true ),
				'in_footer' => true,
				'enqueue'   => true,
			),
			array(
				'handle'    => 'font-awesome',
				'src'       => ys_get_font_awesome_svg_light_url(),
				'deps'      => array(),
				'ver'       => ys_get_theme_version( true ),
				'in_footer' => true,
				'enqueue'   => ( 'light' === ys_get_option( 'ys_enqueue_icon_font_type', 'js' ) ),
			),
			array(
				'handle'    => 'font-awesome',
				'src'       => ys_get_font_awesome_svg_url(),
				'deps'      => array(),
				'ver'       => ystandard\Utility::get_font_awesome_version(),
				'in_footer' => true,
				'enqueue'   => ( 'js' === ys_get_option( 'ys_enqueue_icon_font_type', 'js' ) ),
			),
			array(
				'handle'    => 'font-awesome',
				'src'       => ys_get_option( 'ys_enqueue_icon_font_kit_url', '' ),
				'deps'      => array(),
				'ver'       => ys_get_theme_version( true ),
				'in_footer' => true,
				'enqueue'   => ( 'kit' === ys_get_option( 'ys_enqueue_icon_font_type', 'js' ) && ! empty( ys_get_option( 'ys_enqueue_icon_font_kit_url', '' ) ) ),
			),
		);

		return apply_filters( 'ys_get_enqueue_script_files', $scripts );
	}
}
