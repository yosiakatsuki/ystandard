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
	 * 読み込むCSS
	 *
	 * @var array
	 */
	private $enqueue_styles = array();
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
	 * 初期化処理
	 */
	public function init() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'dequeue_wp_block_css' ) );
		/**
		 * JavaScriptにdeferをセットするフック
		 */
		add_filter( 'script_loader_tag', array( $this, 'script_add_defer' ), PHP_INT_MAX, 3 );
		/**
		 * CSSインライン読み込み
		 */
		if ( ! is_admin() && ys_get_option_by_bool( 'ys_option_optimize_load_css', false ) ) {
			add_filter( 'style_loader_tag', array( $this, 'load_inline_css' ), PHP_INT_MAX, 4 );
		}
		/**
		 * Font Awesome Kitに属性追加
		 */
		if ( 'kit' === ys_get_option( 'ys_enqueue_icon_font_type', 'js' ) && ! empty( ys_get_option( 'ys_enqueue_icon_font_kit_url', '' ) ) ) {
			add_filter( 'script_loader_tag', array( $this, 'set_font_awesome_kit_attributes' ), 10, 2 );
		}
		/**
		 * 追加CSSの出力削除
		 */
		if ( ! is_customize_preview() ) {
			remove_action( 'wp_head', 'wp_custom_css_cb', 101 );
		}
		/**
		 * 管理画面外のみjQuery操作
		 */
		if ( ! is_admin() && ! ys_is_login_page() && ! is_customize_preview() ) {
			/**
			 * [jQuery]削除
			 */
			if ( ys_is_disable_jquery() ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'disable_jquery' ) );
			}
			/**
			 * [jQuery]のフッター読み込み
			 */
			if ( ys_is_deregister_jquery() ) {
				add_action( 'init', array( $this, 'jquery_in_footer' ) );
			}
		}

	}

	/**
	 * CSSのEnqueue
	 */
	public function enqueue_styles() {
		/**
		 * 後方互換 : CSS登録
		 */
		$this->enqueue_items();
		/**
		 * 後方互換 : インラインCSSの登録
		 */
		$this->get_inline_style( ys_is_amp() );
		/**
		 * CSSのエンキュー処理
		 */
		$css = YS_Scripts_Config::get_enqueue_css_files();
		foreach ( $css as $item ) {
			if ( $item['enqueue'] ) {
				if ( 'enqueue' === $item['type'] ) {
					/**
					 * CSSのエンキュー
					 */
					wp_enqueue_style(
						$item['handle'],
						$item['src'],
						$item['deps'],
						$item['ver'],
						$item['media']
					);
				} else {
					/**
					 * インラインCSSの追加
					 */
					wp_add_inline_style(
						$item['inline'],
						$this->minify( $item['src'] )
					);
				}
			}
		}
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
	public function load_inline_css( $html, $handle, $href, $media ) {
		$style = '';
		if ( ! $this->is_enable_inline_css( $handle ) ) {
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
		if ( ! $this->is_site_url( $url ) ) {
			return $html;
		}
		$path = $this->get_inline_css_path( $url );
		if ( file_exists( $path ) ) {
			$style = ystandard\Utility::file_get_contents( $path );
		}
		if ( false === $style ) {
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

	/**
	 * インラインCSS読み込みOKか判断する
	 *
	 * @param string $handle Handle.
	 *
	 * @return bool
	 */
	private function is_enable_inline_css( $handle ) {
		if ( is_admin() ) {
			return false;
		}
		/**
		 * NGリストチェック
		 */
		$disable_inline_css = apply_filters(
			'ys_disable_inline_css',
			array()
		);
		if ( isset( $disable_inline_css[ $handle ] ) ) {
			return false;
		}
		/**
		 * [ystandard]が含まれていればインライン化する
		 */
		if ( false !== strpos( $handle, 'ystandard' ) ) {
			return true;
		}
		/**
		 * OKリストチェック
		 */
		$enable_inline_css = apply_filters(
			'ys_enable_inline_css',
			array()
		);
		if ( isset( $enable_inline_css[ $handle ] ) ) {
			return true;
		}
		/**
		 * エンキューするファイルのチェック
		 */
		$styles = YS_Scripts_Config::get_enqueue_css_files();
		$key    = array_search(
			$handle,
			array_column( $styles, 'handle' ),
			true
		);

		if ( false === $key ) {
			return false;
		}
		if ( false === $styles[ $key ]['inline'] ) {
			return false;
		}

		return true;
	}

	/**
	 * スクリプトのエンキュー
	 */
	public function enqueue_scripts() {

		if ( ys_is_amp() ) {
			return;
		}
		/**
		 * JavaScriptのenqueue
		 */
		$files = YS_Scripts_Config::get_enqueue_script_files();
		foreach ( $files as $file ) {
			if ( $file['enqueue'] ) {
				wp_enqueue_script(
					$file['handle'],
					$file['src'],
					$file['deps'],
					$file['ver'],
					$file['in_footer']
				);
			}
		}
		/**
		 * Twitter関連スクリプト読み込み
		 */
		if ( ys_get_option_by_bool( 'ys_load_script_twitter', false ) ) {
			$this->set_onload_script(
				'twitter-wjs',
				ystandard\Utility::get_twitter_widgets_js()
			);
		}
		/**
		 * Facebook関連スクリプト読み込み
		 */
		if ( ys_get_option_by_bool( 'ys_load_script_facebook', false ) ) {
			ys_enqueue_onload_script(
				'facebook-jssdk',
				ystandard\Utility::get_facebook_sdk_js()
			);
		}
		/**
		 * インラインスクリプトをセット
		 */
		wp_add_inline_script(
			'font-awesome',
			'FontAwesomeConfig = { searchPseudoElements: true };',
			'before'
		);
		wp_localize_script(
			YS_Scripts_Config::SCRIPT_HANDLE_MAIN,
			'ystdConfig',
			array(
				'onload'   => $this->onload_script,
				'lazyload' => $this->lazyload_script,
			)
		);
		/**
		 * インラインJSのセット
		 */
		wp_add_inline_script(
			YS_Scripts_Config::SCRIPT_HANDLE_MAIN,
			str_replace( array( "\r\n", "\r", "\n", "\t" ), '', $this->get_inline_js() )
		);
	}

	/**
	 * スクリプトにdefer属性をセット
	 *
	 * @param string $tag    tag.
	 * @param string $handle handle.
	 * @param string $src    src.
	 *
	 * @return string
	 */
	public function script_add_defer( $tag, $handle, $src ) {
		if ( is_admin() ) {
			return $tag;
		}
		$defer_scripts = $this->get_defer_scripts();
		if ( ! is_array( $defer_scripts ) || empty( $defer_scripts ) ) {
			return $tag;
		}
		if ( isset( $defer_scripts[ $handle ] ) ) {
			if ( $defer_scripts[ $handle ] ) {
				return $this->add_defer( $tag );
			} else {
				return $tag;
			}
		}
		if ( ys_get_option_by_bool( 'ys_option_optimize_load_js', false ) ) {
			return $this->add_defer( $tag );
		}

		return $tag;
	}

	/**
	 * Scriptにdeferを追加
	 *
	 * @param string $tag タグ.
	 *
	 * @return string
	 */
	private function add_defer( $tag ) {
		$tag = str_replace( 'src=\'', 'defer src=\'', $tag );
		$tag = str_replace( 'src="', 'defer src="', $tag );

		return $tag;
	}

	/**
	 * ブロックエディター用WP標準CSSの削除
	 */
	public function dequeue_wp_block_css() {
		// 5.4対応 ... wp_dequeue_style( 'wp-block-library' ).
		wp_dequeue_style( 'wp-block-library-theme' );
	}

	/**
	 * リスト化されたアイテムをwp_enqueue_styleする
	 */
	private function enqueue_items() {
		/**
		 * ロードするCSSファイルのリストに追加する
		 */
		add_filter(
			'ys_get_enqueue_css_files',
			function ( $styles ) {
				foreach ( $this->enqueue_styles as $handle => $item ) {
					$styles[] = array(
						'handle'  => $handle,
						'src'     => $item['src'],
						'deps'    => $item['deps'],
						'ver'     => $item['ver'],
						'media'   => $item['media'],
						'enqueue' => true,
						'type'    => 'enqueue', // enqueue or inline.
						'inline'  => false, // true, false, handle.
					);
				}

				return $styles;
			}
		);

		/**
		 * 変数のクリア
		 */
		$this->enqueue_styles = array();
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
		/**
		 * ロードするCSSファイルのリストに追加する
		 */
		add_filter(
			'ys_get_enqueue_css_files',
			function ( $styles ) {
				$style = '';
				$list  = apply_filters( 'ys_enqueue_inline_styles', $this->inline_styles );
				foreach ( $list as $item ) {
					$inline_style = $item['style'];
					/**
					 * パス文字の場合、中身を取得
					 */
					if ( $this->is_site_path( $inline_style ) ) {
						if ( file_exists( $inline_style ) ) {
							$inline_style = ystandard\Utility::file_get_contents( $inline_style );
						} else {
							$inline_style = '';
						}
					}
					/**
					 * AMPの場合、「@charset "UTF-8";」を削除
					 */
					if ( ys_is_amp() ) {
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
					$styles[] = array(
						'handle'  => substr( md5( $style ), 0, 10 ),
						'src'     => $style,
						'deps'    => array(),
						'ver'     => '',
						'media'   => 'all',
						'enqueue' => true,
						'type'    => 'inline', // enqueue or inline.
						'inline'  => YS_Scripts_Config::CSS_HANDLE_MAIN, // true, false, handle.
					);
				}

				return $styles;
			}
		);

		return '';
	}

	/**
	 * AMP用 CSSの取得
	 *
	 * @return string
	 */
	public function get_amp_style() {
		$style  = '';
		$styles = YS_Scripts_Config::get_enqueue_css_files();
		foreach ( $styles as $item ) {
			if ( $item['enqueue'] ) {
				$temp = '';
				/**
				 * URLのものは中身を読み込む
				 */
				if ( 'enqueue' === $item['type'] ) {
					if ( $this->is_site_url( $item['src'] ) ) {
						$path = $this->get_inline_css_path( $item['src'] );
						if ( file_exists( $path ) ) {
							$temp = ystandard\Utility::file_get_contents( $path );
						}
					}
				} else {
					$temp = $item['src'];
				}
				/**
				 * インラインCSSセット
				 */
				$style .= $this->minify( str_replace( '@charset "UTF-8";', '', $temp ) );
			}
		}

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
		$style = preg_replace( '#/\*[^!][^*]*\*+([^/][^*]*\*+)*/#', '', $style );
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
	 * スクリプトにdeferをつける・つけないリスト
	 *
	 * @return array
	 */
	public function get_defer_scripts() {
		return apply_filters(
			'ys_defer_scripts',
			array(
				'jquery'                              => false,
				'jquery-core'                         => false,
				'jquery-migrate'                      => false,
				'wp-custom-header'                    => false,
				'wp-a11y'                             => false,
				'recaptcha'                           => false,
				YS_Scripts_Config::SCRIPT_HANDLE_MAIN => true,
				'font-awesome'                        => true,
			)
		);
	}

	/**
	 * Font Awesome Kitを使っている場合に属性を追加する
	 *
	 * @param string $tag    Tag.
	 * @param string $handle Handle.
	 *
	 * @return string|string[]|null
	 */
	public function set_font_awesome_kit_attributes( $tag, $handle ) {
		if ( 'font-awesome' !== $handle ) {
			return $tag;
		}
		$extra_tag_attributes = 'crossorigin="anonymous"';
		$modified_script_tag  = preg_replace(
			'/<script\s*(.*?src=.*?)>/',
			'<script \1' . " $extra_tag_attributes >",
			$tag,
			1
		);

		return $modified_script_tag;
	}

	/**
	 * [jQuery]の削除
	 */
	public function disable_jquery() {
		wp_deregister_script( 'jquery' );
		wp_deregister_script( 'jquery-core' );
		wp_dequeue_script( 'jquery' );
		wp_dequeue_script( 'jquery-core' );
	}

	/**
	 * [jQuery]をフッターに移動
	 */
	public function jquery_in_footer() {
		global $wp_scripts;
		$ver = ys_get_theme_version();
		$src = '';
		/**
		 * 必要があればwp_scriptsを初期化
		 */
		wp_scripts();
		if ( null !== $wp_scripts ) {
			$jquery = $wp_scripts->registered['jquery-core'];
			$ver    = $jquery->ver;
			$src    = $jquery->src;
		}
		/**
		 * CDN経由の場合
		 */
		if ( ys_is_load_cdn_jquery() ) {
			$src = ys_get_option( 'ys_load_cdn_jquery_url', '' );
			$ver = null;
		}
		if ( '' === $src ) {
			return;
		}
		/**
		 * [jQuery削除]
		 */
		wp_deregister_script( 'jquery' );
		wp_deregister_script( 'jquery-core' );
		/**
		 * フッターで読み込むか
		 */
		$in_footer = ys_is_load_jquery_in_footer();
		/**
		 * [jQueryをフッターに移動]
		 */
		wp_register_script(
			'jquery-core',
			$src,
			array(),
			$ver,
			$in_footer
		);
		wp_register_script(
			'jquery',
			false,
			array( 'jquery-core' ),
			$ver,
			$in_footer
		);
	}

	/**
	 * 読み込むCSSファイルのURLを取得する
	 *
	 * @return string
	 */
	public static function get_enqueue_css_file_uri() {
		return get_template_directory_uri() . '/css/' . self::get_enqueue_css_file_name();
	}

	/**
	 * 読み込むCSSファイルのパスを取得する
	 *
	 * @return string
	 */
	public static function get_enqueue_css_file_path() {
		return get_template_directory() . '/css/' . self::get_enqueue_css_file_name();
	}

	/**
	 * 読み込むCSSファイルの名前を取得する
	 *
	 * @return string
	 */
	public static function get_enqueue_css_file_name() {
		$file = 'ystandard.css';
		/**
		 * AMP以外は通常CSS
		 */
		if ( ! ys_is_amp() ) {
			$file = 'ystandard.css';
		}

		return $file;
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
				if(ystdConfig.onload) {
					for (var i = 0; i < ystdConfig.onload.length; i++) {
						var item = ystdConfig.onload[i];
						ysLoadScript(item.id, ysGetSrc(item.url, item.ver));
					}
				}
			}, 100);
		});
		window.addEventListener('scroll', function () {
			if (window.ysSetTimeoutId) {
				return false;
			}
			window.ysSetTimeoutId = setTimeout(function () {
				if (ystdConfig.lazyload && 0 < ystdConfig.lazyload.length) {
					var item = ystdConfig.lazyload[0];
					ysLoadScript(item.id, ysGetSrc(item.url, item.ver));
					ystdConfig.lazyload.shift();
					ysSetTimeoutId = null;
				}
			}, 200);
		});
		})(document);";
	}
}
