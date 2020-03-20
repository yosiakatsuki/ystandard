<?php
/**
 * CSSカスタムプロパティ用クラス
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

/**
 * Class Css_Vars
 *
 * @package ystandard
 */
class Css_Vars {

	const FILTER_NAME = 'ys_css_vars';

	/**
	 * CSSカスタムプロパティ追加用配列の取得
	 *
	 * @param string $name  変数名.
	 * @param string $value 値.
	 *
	 * @return array
	 */
	public static function get_css_var( $name, $value ) {
		return [
			'name'  => $name,
			'value' => $value,
		];
	}
}
