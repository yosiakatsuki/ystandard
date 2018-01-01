<?php
/**
 * スタイルシート関連を操作するためのクラス
 */
class YS_Styles {
	/**
	 * インラインCSS文字列
	 */
	private $inline_styles;

	/**
	 * construct
	 */
	public function __construct() {
		$inline_styles = array();
	}

	/**
	 * インラインCSSのセット
	 */
	public function set_inline_style( $style, $minify = true ) {
		$this->inline_styles[] = array(
																'style' => $style,
																'minify' => $minify
															);
	}

	/**
	 * インラインCSSの取得
	 */
	public function get_inline_style( $amp ) {
		$style = '';
		$inline_style_list = apply_filters( 'ys_styles_before_get_inline_style', $this->inline_styles );
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
	 * CSSへパス文字かどうかの判断
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

		return apply_filters( 'ys_styles_minify_css', $style );
	}
}