<?php
/**
 * CSS,JavaScript読み込み関連のためのクラス
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * CSS,JavaScript読み込み関連のためのクラス
 */
class YS_Scripts {
	/**
	 * インラインCSS読み込み時のダミーCSS
	 */
	const CSS_HANDLE_DUMMY = 'ystandard';
	/**
	 * メインCSS
	 */
	const CSS_HANDLE_MAIN = 'ystandard-style';
	/**
	 * インラインのハンドルにするダミーJS
	 */
	const SCRIPT_HANDLE_DUMMY = 'ystandard-s';
	/**
	 * メインJS
	 */
	const SCRIPT_HANDLE_MAIN = 'ystandard-script';
	/**
	 * 読み込むCSS
	 *
	 * @var array
	 */
	private $enqueue_styles = array();
	/**
	 * 読み込むJS
	 *
	 * @var array
	 */
	private $enqueue_scripts = array();
	/**
	 * 読み込むインラインJS
	 *
	 * @var array
	 */
	private $enqueue_inline_scripts = array();
	/**
	 * インラインCSS
	 *
	 * @var array
	 */
	private $inline_styles = array();
	/**
	 * Onload-script
	 *
	 * @var array
	 */
	private $onload_script = array();
	/**
	 * Lazyload-script
	 *
	 * @var array
	 */
	private $lazyload_script = array();

	/**
	 * インラインCSS
	 *
	 * @var string
	 */
	private $inline_css = '';

	/**
	 * Construct
	 */
	public function __construct() {
	}

	/**
	 * スクリプトのエンキュー
	 */
	public function enqueue_script() {
		/**
		 * ダミーjsの指定
		 */
		wp_enqueue_script(
			self::SCRIPT_HANDLE_DUMMY,
			get_template_directory_uri() . '/js/ystandard-s.js',
			array(),
			'',
			true
		);
		/**
		 * ダミー削除用フック登録
		 */
		add_filter( 'script_loader_tag', array( $this, 'delete_ystandard_script' ), 999, 2 );
		/**
		 * JS enqueue前アクション
		 */
		do_action( 'ys_enqueue_scripts' );
		/**
		 * インラインスクリプトをセット
		 */
		add_filter( 'wp_enqueue_scripts', array( $this, 'add_ystandard_inline_script' ), 999 );
	}

	/**
	 * インラインスクリプトのセット
	 */
	public function add_ystandard_inline_script() {
		/**
		 * パラメーターを渡す
		 */
		wp_localize_script(
			self::SCRIPT_HANDLE_DUMMY,
			'ys_onload_script',
			$this->onload_script
		);
		wp_localize_script(
			self::SCRIPT_HANDLE_DUMMY,
			'ys_Lazyload_script',
			$this->lazyload_script
		);
		/**
		 * インラインJSのセット
		 */
		wp_add_inline_script(
			self::SCRIPT_HANDLE_DUMMY,
			str_replace( array( "\r\n", "\r", "\n", "\t" ), '', $this->get_inline_js() )
		);
	}

	/**
	 * CSSのエンキュー
	 */
	public function pre_enqueue_styles() {
		/**
		 * CSS enqueue前アクション
		 */
		do_action( 'ys_enqueue_styles' );
		/**
		 * インラインCSS出力用にダミーでenqueueする.
		 */
		wp_enqueue_style(
			self::CSS_HANDLE_DUMMY,
			get_template_directory_uri() . '/css/ystandard.css'
		);
		/**
		 * ダミー削除用フック登録
		 */
		add_filter( 'style_loader_tag', array( $this, 'delete_ystandard_css' ), 999, 2 );
		/**
		 * 通常読み込み
		 */
		$this->enqueue_items();
		/**
		 * インラインCSSなどの読み込み
		 */
		add_filter( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ), 999 );
	}

	/**
	 * リスト化されたアイテムをwp_enqueue_styleする
	 */
	private function enqueue_items() {
		foreach ( $this->enqueue_styles as $handle => $item ) {
			wp_enqueue_style(
				$handle,
				$item['src'],
				$item['deps'],
				$item['ver'],
				$item['media']
			);
		}
		/**
		 * 変数のクリア
		 */
		$this->enqueue_styles = array();
	}

	/**
	 * インラインCSSなどの読み込み指定
	 */
	public function enqueue_styles() {
		/**
		 * 通常読み込み
		 */
		$this->enqueue_items();
		/**
		 * インラインCSSの登録
		 */
		wp_add_inline_style(
			$this->get_inline_css_handle(),
			$this->get_inline_style( ys_is_amp() )
		);
	}

	/**
	 * インラインCSSをくっつけるハンドル名を取得
	 *
	 * @return string
	 */
	private function get_inline_css_handle() {
		if ( ys_is_optimize_load_css() ) {
			return self::CSS_HANDLE_DUMMY;
		}

		return self::CSS_HANDLE_MAIN;
	}

	/**
	 * 読み込みするCSSを登録する
	 *
	 * @param string $handle Handle.
	 * @param string $src    CSSのURL.
	 * @param bool   $inline インライン読み込みするか.
	 * @param array  $deps   deps.
	 * @param bool   $ver    クエリストリング.
	 * @param string $media  media.
	 * @param bool   $minify Minifyするか.
	 */
	public function set_enqueue_style( $handle, $src, $inline = true, $deps = array(), $ver = false, $media = 'all', $minify = true ) {
		if ( false === $ver ) {
			$ver = '';
		}
		if ( $inline && $this->is_site_url( $src ) ) {
			/**
			 * インラインCSSとして登録
			 */
			$path = $this->get_inline_css_path( $src );
			$this->set_inline_style( $path, $minify );
		} else {
			/**
			 * 通常読み込みCSSとして登録
			 */
			$this->enqueue_styles[ $handle ] = array(
				'src'    => $src,
				'inline' => $inline,
				'deps'   => $deps,
				'ver'    => $ver,
				'media'  => $media,
				'minify' => $minify,
			);
		}
	}

	/**
	 * インラインCSS読み込み用パスを作成
	 *
	 * @param string $src CSSのURL.
	 *
	 * @return string
	 */
	private function get_inline_css_path( $src ) {
		return str_replace( site_url( '/' ), ABSPATH, $src );
	}

	/**
	 * ダミーで追加したCSSを削除
	 *
	 * @param string $html   linkタグ.
	 * @param string $handle キー.
	 *
	 * @return string
	 */
	public function delete_ystandard_css( $html, $handle ) {
		if ( self::CSS_HANDLE_DUMMY === $handle ) {
			$html = '';
		}

		return $html;
	}

	/**
	 * ダミーで追加したJSを削除
	 *
	 * @param string $html   scriptタグ.
	 * @param string $handle キー.
	 *
	 * @return string
	 */
	public function delete_ystandard_script( $html, $handle ) {
		if ( self::SCRIPT_HANDLE_DUMMY === $handle ) {
			$html = preg_replace( '/<script.+src=["\'].+?["\']><\/script>/is', '', $html );
		}

		return $html;
	}

	/**
	 * インラインCSSのセット
	 *
	 * @param string  $style  インラインCSS or パス.
	 * @param boolean $minify minifyするかどうか.
	 *
	 * @return void
	 */
	public function set_inline_style( $style, $minify = true ) {
		$this->inline_styles[] = array(
			'style'  => $style,
			'minify' => $minify,
		);
	}

	/**
	 * Onload-script,lazyload-script,lazyload-cssの配列取得
	 *
	 * @param string  $id  id.
	 * @param string  $url url.
	 * @param boolean $ver varsion.
	 *
	 * @return array
	 */
	private function get_load_script_array( $id, $url, $ver = false ) {
		$ver = false === $ver ? '' : $ver;

		return array(
			'id'  => esc_attr( $id ),
			'url' => esc_url_raw( $url ),
			'ver' => esc_attr( $ver ),
		);
	}

	/**
	 * Onload-scriptのセット
	 *
	 * @param string  $id  id.
	 * @param string  $src url.
	 * @param boolean $ver varsion.
	 */
	public function set_onload_script( $id, $src, $ver = false ) {
		$this->onload_script[] = $this->get_load_script_array( $id, $src, $ver );
	}

	/**
	 * Lazyload-scriptのセット
	 *
	 * @param string  $id  id.
	 * @param string  $src url.
	 * @param boolean $ver varsion.
	 */
	public function set_lazyload_script( $id, $src, $ver = false ) {
		$this->lazyload_script[] = $this->get_load_script_array( $id, $src, $ver );
	}

	/**
	 * インラインCSSの取得
	 *
	 * @param boolean $amp is amp.
	 *
	 * @return string
	 */
	public function get_inline_style( $amp ) {
		$style = '';
		$list  = apply_filters( 'ys_enqueue_inline_styles', $this->inline_styles );
		foreach ( $list as $item ) {
			$inline_style = $item['style'];
			/**
			 * パス文字の場合、中身を取得
			 */
			if ( $this->is_site_path( $inline_style ) ) {
				if ( file_exists( $inline_style ) ) {
					$inline_style = ys_file_get_contents( $inline_style );
				} else {
					$inline_style = '';
				}
			}
			/**
			 * AMPの場合、「@charset "UTF-8";」を削除
			 */
			if ( $amp ) {
				$inline_style = str_replace( '@charset "UTF-8";', '', $inline_style );
			}
			/**
			 * Minifyする
			 */
			if ( true === $item['minify'] ) {
				$style .= $this->minify( $inline_style );
			} else {
				$style .= $inline_style;
			}
		}

		/**
		 * インラインCSSをセット
		 */
		$this->inline_css = $style;

		return $style;
	}

	/**
	 * サイト内のCSS URLかどうかの判断
	 *
	 * @param string $src url.
	 *
	 * @return bool
	 */
	private function is_site_url( $src ) {
		if ( false !== strrpos( $src, home_url() ) ) {
			return true;
		}

		return false;
	}

	/**
	 * サイト内のCSSパスかどうかの判断
	 *
	 * @param string $style style or url.
	 *
	 * @return bool
	 */
	private function is_site_path( $style ) {
		if ( false !== strrpos( $style, ABSPATH ) ) {
			return true;
		}

		return false;
	}

	/**
	 * CSSの圧縮
	 *
	 * @param string $style inline css styles.
	 *
	 * @return string
	 */
	public function minify( $style ) {
		/**
		 * コメント削除
		 */
		$style = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $style );
		/**
		 * コロンの後の空白を削除する
		 */
		$style = str_replace( ': ', ':', $style );
		/**
		 * タブ、スペース、改行などを削除する
		 */
		$style = str_replace( array( "\r\n", "\r", "\n", "\t", '  ', '    ' ), '', $style );

		return apply_filters( 'ys_enqueue_minify', $style );
	}

	/**
	 * Gutenbergフォントサイズ指定CSS
	 *
	 * @param int $default デフォルトフォントサイズ.
	 *
	 * @return string
	 */
	public function get_editor_font_size_css( $default = 16 ) {
		$default = apply_filters( 'ys_default_editor_font_size', $default );
		$size    = ys_get_editor_font_sizes();
		$css     = '';
		foreach ( $size as $value ) {
			$css .= sprintf(
				'.has-%s-font-size{font-size:%sem;}',
				$value['slug'],
				( $value['size'] / $default )
			);
		}

		return $css;
	}

	/**
	 * Gutenberg色設定
	 *
	 * @return string
	 */
	public function get_editor_color_palette() {
		$color = ys_get_editor_color_palette();
		$css   = '';
		foreach ( $color as $value ) {
			$css .= sprintf(
				'.has-%s-background-color{background-color:%s;border-color:%s;}',
				$value['slug'],
				$value['color'],
				$value['color']
			);
			$css .= sprintf(
				'.has-%s-color,.has-%s-color:hover{color:%s;}',
				$value['slug'],
				$value['slug'],
				$value['color']
			);
			$css .= sprintf(
				'.is-style-outline .wp-block-button__link.has-%s-color{border-color:%s;color:%s;}',
				$value['slug'],
				$value['color'],
				$value['color']
			);
			$css .= sprintf(
				'.is-style-outline .wp-block-button__link:hover.has-%s-color{background-color:%s;border-color:%s;}',
				$value['slug'],
				$value['color'],
				$value['color']
			);
		}

		return $css;
	}

	/**
	 * インラインJS作成
	 *
	 * @return string
	 */
	private function get_inline_js() {
		return "
		(function (d) {
		window.ysSetTimeoutId = null;
		var ysLoadScript = function (id, src) {
			if (!d.getElementById(id)) {
				var js = d.createElement('script');
				js.id = id;
				js.src = src;
				js.defer = true;
				d.body.appendChild(js);
			}
		};
		var ysGetSrc = function (url, ver) {
			if (ver) {
				url += '?' + ver;
			}
			return url;
		};
		window.addEventListener('DOMContentLoaded', function () {
			setTimeout(function () {
				for (var i = 0; i < ys_onload_script.length; i++) {
					var item = ys_onload_script[i];
					ysLoadScript(item.id, ysGetSrc(item.url, item.ver));
				}
			}, 100);
		});
		window.addEventListener('scroll', function () {
			if (window.ysSetTimeoutId) {
				return false;
			}
			window.ysSetTimeoutId = setTimeout(function () {
				if (0 < ys_Lazyload_script.length) {
					var item = ys_Lazyload_script[0];
					ysLoadScript(item.id, ysGetSrc(item.url, item.ver));
					ys_Lazyload_script.shift();
					ysSetTimeoutId = null;
				}
			}, 200);
		});
		})(document);";
	}
}