<?php
/**
 * CSS,JavaScript読み込み関連のためのクラス
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * CSS,JavaScript読み込み関連のためのクラス
 */
class YS_Enqueue {
	/**
	 * インラインCSS
	 *
	 * @var array
	 */
	private $inline_styles;
	/**
	 * Non-critical-css
	 *
	 * @var array
	 */
	private $non_critical_css;
	/**
	 * Onload-script
	 *
	 * @var array
	 */
	private $onload_script;
	/**
	 * Lazyload-script
	 *
	 * @var array
	 */
	private $lazyload_script;
	/**
	 * Lazyload-css_lazyload
	 *
	 * @var array
	 */
	private $lazyload_css;

	/**
	 * Construct
	 */
	public function __construct() {
		$inline_styles    = array();
		$non_critical_css = array();
		$onload_script    = array();
		$lazyload_script  = array();
		$lazyload_css     = array();
	}

	/**
	 * インラインCSSのセット
	 *
	 * @param string  $style インラインCSS.
	 * @param boolean $minify minifyするかどうか.
	 * @return void
	 */
	public function set_inline_style( $style, $minify = true ) {
		$this->inline_styles[] = array(
			'style'  => $style,
			'minify' => $minify,
		);
	}
	/**
	 * Non-critical-css,lazyload-cssの配列セット
	 *
	 * @param array   $arr css list.
	 * @param string  $id  id.
	 * @param string  $url url.
	 * @param boolean $ver varsion.
	 */
	private function set_load_css_array( &$arr, $id, $url, $ver = false ) {
		$ver        = false == $ver ? '' : $ver;
		$arr[ $id ] = array(
			'id'  => esc_attr( $id ),
			'url' => esc_url_raw( $url ),
			'ver' => esc_attr( $ver ),
		);
	}
	/**
	 * Non-critical CSSのセット
	 *
	 * @param string  $id  id.
	 * @param string  $src url.
	 * @param boolean $ver varsion.
	 */
	public function set_non_critical_css( $id, $src, $ver = false ) {
		$this->set_load_css_array( $this->non_critical_css, $id, $src, $ver );
	}
	/**
	 * Lazyload-cssのセット
	 *
	 * @param string  $id  id.
	 * @param string  $src url.
	 * @param boolean $ver varsion.
	 */
	public function set_lazyload_css( $id, $src, $ver = false ) {
		$this->set_load_css_array( $this->lazyload_css, $id, $src, $ver );
	}
	/**
	 * Onload-script,lazyload-script,lazyload-cssの配列セット
	 *
	 * @param array   $arr css list.
	 * @param string  $id  id.
	 * @param string  $url url.
	 * @param boolean $ver varsion.
	 */
	private function set_load_script_array( &$arr, $id, $url, $ver = false ) {
		$ver        = false == $ver ? '' : $ver;
		$arr[ $id ] = array(
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
		$this->set_load_script_array( $this->onload_script, $id, $src, $ver );
	}
	/**
	 * Lazyload-scriptのセット
	 *
	 * @param string  $id  id.
	 * @param string  $src url.
	 * @param boolean $ver varsion.
	 */
	public function set_lazyload_script( $id, $src, $ver = false ) {
		$this->set_load_script_array( $this->lazyload_script, $id, $src, $ver );
	}
	/**
	 * インラインCSSの取得
	 *
	 * @param boolean $amp is amp.
	 */
	public function get_inline_style( $amp ) {
		$style             = '';
		$inline_style_list = apply_filters( 'ys_enqueue_inline_styles', $this->inline_styles );
		foreach ( $inline_style_list as $inline_style_item ) {
			$inline_style = $inline_style_item['style'];
			/**
			 * パス文字の場合、中身を取得
			 */
			if ( $this->is_css_path( $inline_style ) ) {
				if ( file_exists( $inline_style ) ) {
					$inline_style = file_get_contents( $inline_style );
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
			if ( true === $inline_style_item['minify'] ) {
				$style .= $this->minify_css( $inline_style );
			} else {
				$style .= $inline_style;
			}
		}
		return $style;
	}

	/**
	 * Non-critical CSS一覧の取得
	 */
	public function get_non_critical_css_list() {
		$list  = array();
		$items = apply_filters( 'ys_enqueue_non_critical_css', $this->non_critical_css );
		if ( is_array( $items ) ) {
			foreach ( $items as $id => $item ) {
				$src = $item['url'];
				if ( '' !== $item['ver'] ) {
					$src = add_query_arg( 'ver', $item['ver'], $src );
				}
				$list[] = $src;
			}
		}
		return $list;
	}

	/**
	 * Non-critical-css読み込み用script作成
	 */
	public function get_non_critical_css() {
		$list = $this->get_non_critical_css_list();
		$list = array_reverse( $list );
		return $this->get_non_critical_css_script( $this->ys_json_encode( $list ) );
	}

	/**
	 * Onload-script読み込み用data属性文字列作成
	 */
	public function get_onload_script_attr() {
		$scripts = apply_filters( 'ys_enqueue_onload_scripts', $this->onload_script );
		$scripts = $this->create_load_array( $scripts );
		return 'data-ys-onload-script=\'' . $this->ys_json_encode( $scripts ) . '\'';
	}
	/**
	 * Lazyload-script読み込み用data属性文字列作成
	 */
	public function get_lazyload_script_attr() {
		$scripts = apply_filters( 'ys_enqueue_lazyload_scripts', $this->lazyload_script );
		$scripts = $this->create_load_array( $scripts );
		return 'data-ys-lazy-script=\'' . $this->ys_json_encode( $scripts ) . '\'';
	}
	/**
	 * Lazyload-css読み込み用data属性文字列作成
	 */
	public function get_lazyload_css_attr() {
		$css = apply_filters( 'ys_enqueue_lazyload_css', $this->lazyload_css );
		$css = $this->create_load_array( $css );
		return 'data-ys-lazy-css=\'' . $this->ys_json_encode( $css ) . '\'';
	}
	/**
	 * テーマ内のCSSパスかどうかの判断
	 *
	 * @param string $style style or url.
	 */
	private function is_css_path( $style ) {
		if ( false !== strrpos( $style, get_stylesheet_directory() ) ) {
			return true;
		}
		if ( false !== strrpos( $style, get_template_directory() ) ) {
			return true;
		}
		return false;
	}
	/**
	 * 読み込み用配列を作成する
	 *
	 * @param array $obj 読み込み対象のものをまとめた配列.
	 */
	private function create_load_array( $obj ) {
		if ( empty( $obj ) ) {
			return null;
		}
		$array = array();
		foreach ( $obj as $key => $value ) {
			$url = $value['url'];
			if ( '' !== $value['ver'] ) {
				$url = add_query_arg( $value['ver'], '', $url );
			}
			$array[] = array(
				'id'  => $value['id'],
				'url' => $url,
			);
		}
		return $array;
	}
	/**
	 * Non-critical-css出力用JavaScript取得
	 *
	 * @param string $css inline css styles.
	 */
	private function get_non_critical_css_script( $css ) {
		if ( empty( $css ) ) {
			return '';
		}
		$script = <<<EOD
<script>
	var cb = function() {
		var list = {$css}
				,l
				,h = document.getElementsByTagName('head')[0]
				,s = document.getElementById('ystandard-inline-style');
		for (var i = 0; i < list.length; i++){
			l = document.createElement('link');
			l.rel = 'stylesheet';
			l.href = list[i];
			if(s) {
				s.parentNode.insertBefore(l,s.nextSibling);
			} else {
				h.appendChild(l);
			}
		}
	};
	var raf = requestAnimationFrame || mozRequestAnimationFrame || webkitRequestAnimationFrame || msRequestAnimationFrame;
	if (raf) raf(cb);
	else window.addEventListener('load', cb);
</script>
EOD;
		/**
		 * 改行削除
		 */
		$script = str_replace( array( "\r\n", "\r", "\n", "\t" ), '', $script );
		return $script;
	}

	/**
	 * CSSの圧縮
	 *
	 * @param string $style inline css styles.
	 */
	public function minify_css( $style ) {
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

		return apply_filters( 'ys_enqueue_minify_css', $style );
	}
	/**
	 * JSで扱える形式のjsonを作る
	 *
	 * @param array $array JavaScript情報をまとめた配列.
	 */
	public function ys_json_encode( $array ) {
		return json_encode( $array, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES );
	}
}