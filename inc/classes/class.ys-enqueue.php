<?php
/**
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */
/**
 * css,javascript読み込み関連のためのクラス
 */
class YS_Enqueue {
	/**
	 * インラインCSS
	 */
	private $inline_styles;
	/**
	 * non-critical-css
	 */
	private $non_critical_css;
	/**
	 * onload-script
	 */
	private $onload_script;
	/**
	 * lazyload-script
	 */
	private $lazyload_script;
	/**
	 * lazyload-css_lazyload
	 */
	private $lazyload_css;

	/**
	 * construct
	 */
	public function __construct() {
		$inline_styles = array();
		$non_critical_css = array();
		$onload_script = array();
		$lazyload_script = array();
		$lazyload_css = array();
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
	 * non-critical-css,lazyload-cssの配列セット
	 */
	private function set_load_css_array( &$arr, $id, $url, $ver = false ) {
		$ver = false == $ver ? '' : $ver;
		$arr[ $id ] = array(
										'id' => esc_attr( $id ),
										'url' => esc_url_raw( $url ),
										'ver' => esc_attr( $ver )
									);
	}
	/**
	 * non-critical CSSのセット
	 */
	public function set_non_critical_css( $id, $src, $ver = false ) {
		$this->set_load_css_array( $this->non_critical_css, $id, $src, $ver );
	}
	/**
	 * lazyload-cssのセット
	 */
	public function set_lazyload_css( $id, $src, $ver = false ) {
		$this->set_load_css_array( $this->lazyload_css, $id, $src, $ver );
	}
	/**
	 * onload-script,lazyload-script,lazyload-cssの配列セット
	 */
	private function set_load_script_array( &$arr, $id, $url, $ver = false ) {
		$ver = false == $ver ? '' : $ver;
		$arr[ $id ] = array(
										'id' => esc_attr( $id ),
										'url' => esc_url_raw( $url ),
										'ver' => esc_attr( $ver )
									);
	}
	/**
	 * onload-scriptのセット
	 */
	public function set_onload_script( $id, $src, $ver = false ) {
		$this->set_load_script_array( $this->onload_script, $id, $src, $ver );
	}
	/**
	 * lazyload-scriptのセット
	 */
	public function set_lazyload_script( $id, $src, $ver = false ) {
		$this->set_load_script_array( $this->lazyload_script, $id, $src, $ver );
	}

	/**
	 * インラインCSSの取得
	 */
	public function get_inline_style( $amp ) {
		$style = '';
		$inline_style_list = apply_filters( 'ys_enqueue_inline_styles', $this->inline_styles );
		foreach( $inline_style_list as $inline_style_item ){
			$inline_style = $inline_style_item['style'];
			/**
			 * パス文字の場合、中身を取得
			 */
			if( $this->is_css_path( $inline_style ) ) {
				if( file_exists( $inline_style ) ) {
					$inline_style = file_get_contents( $inline_style );
				} else {
					$inline_style = '';
				}
			}
			/**
			 * AMPの場合、「@charset "UTF-8";」を削除
			 * @var [type]
			 */
			if( $amp ) {
				$inline_style = str_replace( '@charset "UTF-8";', '', $inline_style );
			}
			/**
			 * minifyする
			 */
			if( true === $inline_style_item['minify'] ) {
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
					$src = add_query_arg( $item['ver'], '', $src );
				}
				$list[] = $src;
			}
		}
		return $list;
	}

	/**
	 * non-critical-css読み込み用script作成
	 */
	public function get_non_critical_css() {
		$list = $this->get_non_critical_css_list();
		$list = array_reverse( $list );
		return $this->get_non_critical_css_script( $this->ys_json_encode( $list ) );
	}

	/**
	 * onload-script読み込み用data属性文字列作成
	 */
	public function get_onload_script_attr() {
		$scripts = apply_filters( 'ys_enqueue_onload_scripts', $this->onload_script );
		$scripts = $this->create_load_array( $scripts );		
		return 'data-ys-onload-script=' . $this->ys_json_encode( $scripts );
	}
	/**
	 * lazyload-script読み込み用data属性文字列作成
	 */
	public function get_lazyload_script_attr() {
		$scripts = apply_filters( 'ys_enqueue_lazyload_scripts', $this->lazyload_script );
		$scripts = $this->create_load_array( $scripts );
		return 'data-ys-lazy-script=' . $this->ys_json_encode( $scripts );
	}
	/**
	 * lazyload-css読み込み用data属性文字列作成
	 */
	public function get_lazyload_css_attr() {
		$css = apply_filters( 'ys_enqueue_lazyload_css', $this->lazyload_css );
		$css = $this->create_load_array( $css );
		return 'data-ys-lazy-css=' . $this->ys_json_encode( $css );
	}

	/**
	 * テーマ内のCSSパスかどうかの判断
	 */
	private function is_css_path( $style ) {
		if( false !== strrpos( $style, get_stylesheet_directory() ) ) {
			return true;
		}
		if( false !== strrpos( $style, get_template_directory() ) ) {
			return true;
		}
		return false;
	}
	/**
	 * 読み込み用配列を作成する
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
	 * non-critical-css出力用javascript取得
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
		// $script = str_replace( array( "\r\n", "\r", "\n", "\t" ), '', $script );
		return $script;
	}

	/**
	 * cssの圧縮
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
	 * jsで扱える形式のjsonを作る
	 */
	public function ys_json_encode( $array ) {
		return json_encode( $array, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES );
	}
}