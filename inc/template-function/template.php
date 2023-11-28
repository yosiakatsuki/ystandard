<?php
/**
 * テンプレート関連
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

defined( 'ABSPATH' ) || die();

/**
 * Front-pageでロードするテンプレート
 */
function ys_get_front_page_template() {
	return page\Template::get_front_page_template();
}
