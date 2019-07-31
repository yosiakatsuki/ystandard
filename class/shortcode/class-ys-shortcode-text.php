<?php
/**
 * テキスト ショートコード クラス
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * Class YS_Shortcode_Text
 */
class YS_Shortcode_Text extends YS_Shortcode_Base {

	/**
	 * YS_Shortcode_Text constructor.
	 *
	 * @param array $args ユーザー指定パラメーター.
	 * @param array $attr 追加パラメーター.
	 */
	public function __construct( $args = array(), $attr = array() ) {
		parent::__construct( $args, $attr );
	}

	/**
	 * HTML取得
	 *
	 * @param string $content コンテンツ.
	 *
	 * @return string
	 */
	public function get_html( $content ) {
		return parent::get_html( $content );
	}
}