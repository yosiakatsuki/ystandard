<?php
/**
 * CSS読み込みクラス
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

/**
 * Class Enqueue_Styles
 *
 * @package ystandard
 */
class Enqueue_Styles {

	/**
	 * Main CSS.
	 */
	const HANDLE_MAIN = 'ystandard';

	/**
	 * Blocks CSS.
	 */
	const HANDLE_BLOCKS = 'ys-blocks';

	/**
	 * ブレークポイント
	 *
	 * @var array
	 */
	const BREAKPOINTS = [
		'sm' => 600,
		'md' => 769,
		'lg' => 1025,
	];

	/**
	 * Enqueue_Styles constructor.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_css' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'dequeue_css' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_style_css' ], 100 );
		// CSSインライン読み込み.
		if ( Option::get_option_by_bool( 'ys_option_optimize_load_css', false ) ) {
			add_filter( 'style_loader_tag', [ $this, 'style_loader_inline' ], PHP_INT_MAX, 4 );
		}
		add_filter( 'wp_get_custom_css', [ $this, '_wp_get_custom_css' ] );
	}

	/**
	 * CSS enqueue
	 */
	public function enqueue_css() {
		/**
		 * メインCSS
		 */
		wp_enqueue_style(
			self::HANDLE_MAIN,
			get_template_directory_uri() . '/css/ystandard.css',
			[],
			Utility::get_ystandard_version()
		);

		wp_add_inline_style(
			self::HANDLE_MAIN,
			$this->get_inline_css()
		);
		do_action( 'ys_enqueue_css' );

		// 位置調整.
		wp_dequeue_style( 'wp-block-library' );
		wp_enqueue_style( 'wp-block-library' );
		wp_enqueue_style(
			self::HANDLE_BLOCKS,
			get_template_directory_uri() . '/css/blocks.css',
			[],
			Utility::get_ystandard_version()
		);
		wp_add_inline_style(
			self::HANDLE_BLOCKS,
			$this->get_blocks_inline_css()
		);

		/**
		 * Style css
		 */
		wp_enqueue_style(
			'style-css',
			get_stylesheet_uri(),
			[],
			Utility::get_theme_version( true )
		);

		$this->style_add_data();
	}

	/**
	 * Add Data
	 */
	private function style_add_data() {
		wp_style_add_data( self::HANDLE_MAIN, 'inline', true );
		wp_style_add_data( 'wp-block-library', 'inline', 'none' );
		do_action( 'ys_style_add_data' );
	}

	/**
	 * インラインCSSを取得
	 *
	 * @return string
	 */
	private function get_inline_css() {
		$inline   = self::minify( apply_filters( Enqueue_Utility::FILTER_INLINE_CSS, '' ) );
		$css_vars = self::get_css_vars_selector();

		return $inline . $css_vars;
	}

	/**
	 * インラインCSSを取得
	 *
	 * @return string
	 */
	private function get_blocks_inline_css() {

		return self::minify( apply_filters( Enqueue_Utility::FILTER_BLOCKS_INLINE_CSS, '' ) );
	}

	/**
	 * CSSカスタムプロパティを作成する
	 *
	 * @return string
	 */
	public static function get_css_vars_selector() {
		/**
		 * CSSカスタムプロパティに指定する値
		 * name,value
		 */
		$vars = apply_filters( Enqueue_Utility::FILTER_CSS_VARS, [] );
		if ( empty( $vars ) ) {
			return '';
		}
		$result = '';
		foreach ( $vars as $item ) {
			if ( isset( $item['name'] ) && isset( $item['value'] ) ) {
				$result .= "--{$item['name']}: {$item['value']};";
			}
		}
		if ( ! $result ) {
			return '';
		}

		return ":root{ ${result} }";
	}

	/**
	 * Style.cssの位置調整
	 */
	public function enqueue_style_css() {
		wp_dequeue_style( 'style-css' );
		wp_enqueue_style(
			'style-css',
			get_stylesheet_uri(),
			[],
			Utility::get_theme_version( true )
		);
	}

	/**
	 * CSS dequeue
	 */
	public function dequeue_css() {
		wp_dequeue_style( 'wp-block-library-theme' );
	}

	/**
	 * 追加CSSのminify
	 *
	 * @param string $css CSS.
	 *
	 * @return string
	 */
	public function _wp_get_custom_css( $css ) {
		return self::minify( $css );
	}

	/**
	 * CSSの圧縮
	 *
	 * @param string $style inline css styles.
	 *
	 * @return string
	 */
	public static function minify( $style ) {
		// コメント削除.
		$style = preg_replace( '#/\*[^!][^*]*\*+([^/][^*]*\*+)*/#', '', $style );
		// コロンの後の空白を削除する.
		$style = str_replace( ': ', ':', $style );
		// タブ、スペース、改行などを削除する.
		$style = str_replace( [ "\r\n", "\r", "\n", "\t", '  ', '    ' ], '', $style );

		return $style;
	}

	/**
	 * メディアクエリを追加
	 *
	 * @param string $css Styles.
	 * @param string $min Breakpoint.
	 * @param string $max Breakpoint.
	 *
	 * @return string
	 */
	public static function add_media_query( $css, $min = '', $max = '' ) {

		if ( ! array_key_exists( $min, self::BREAKPOINTS ) && ! array_key_exists( $max, self::BREAKPOINTS ) ) {
			return $css;
		}
		if ( array_key_exists( $min, self::BREAKPOINTS ) ) {
			$breakpoint = self::BREAKPOINTS[ $min ];
			$min        = "(min-width: ${breakpoint}px)";
		}
		if ( array_key_exists( $max, self::BREAKPOINTS ) ) {
			$breakpoint = self::BREAKPOINTS[ $max ] - 1;
			$max        = "(max-width: ${breakpoint}px)";
		}
		$breakpoint = $min . $max;
		if ( '' !== $min && '' !== $max ) {
			$breakpoint = $min . ' AND ' . $max;
		}

		if ( empty( $breakpoint ) ) {
			return $css;
		}

		return sprintf(
			'@media %s {%s}',
			$breakpoint,
			$css
		);
	}

	/**
	 * CSSのインライン出力化
	 *
	 * @param string $html   Html.
	 * @param string $handle Handle.
	 * @param string $href   Href.
	 * @param string $media  Media.
	 *
	 * @return string
	 */
	public function style_loader_inline( $html, $handle, $href, $media ) {
		if ( false === strpos( $html, 'ystandard' ) ) {
			if ( true !== wp_styles()->get_data( $handle, 'inline' ) ) {
				return $html;
			}
		}
		if ( 'none' === wp_styles()->get_data( $handle, 'inline' ) ) {
			return $html;
		}
		if ( is_admin() ) {
			return $html;
		}
		/**
		 * URLとメディア指定を取得
		 */
		$pattern = '/<link.+href=[\'"](.+?)[\'"\?\#].+media=[\'"](.+?)[\'"].*\/>/i';
		$matches = null;
		if ( 1 !== preg_match( $pattern, $html, $matches ) ) {
			return $html;
		}
		/**
		 * サイトURLのチェック
		 */
		$url = $matches[1];

		if ( false === strrpos( $url, home_url() ) ) {
			return $html;
		}
		$path = str_replace( site_url( '/' ), ABSPATH, $url );
		if ( file_exists( $path ) ) {
			$style = Utility::file_get_contents( $path );
		}
		if ( false === $style ) {
			return $html;
		}
		if ( false !== strpos( $style, '../' ) || false !== strpos( $style, './' ) ) {
			return $html;
		}
		/**
		 * インラインCSSのminify
		 */
		$style = $this->minify( str_replace( '@charset "UTF-8";', '', $style ) );
		/**
		 * 中身がなければ何も出さない
		 */
		if ( empty( $style ) ) {
			return '';
		}
		/**
		 * インラインCSS出力
		 */
		$tag = '<style>%s</style>';
		if ( isset( $matches[2] ) && 'all' !== $matches[2] ) {
			$tag = '<style>@media ' . $matches[2] . ' {%s}</style>';
		}

		return sprintf( $tag, $style ) . PHP_EOL;
	}

}

new Enqueue_Styles();
