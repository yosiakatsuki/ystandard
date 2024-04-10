<?php
/**
 * ページネーション関連の読み込み
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */
defined( 'ABSPATH' ) || die();

/**
 * ページネーション
 */
function ys_get_pagination() {
	$pagination = new \ystandard\Pagination();

	return $pagination->get_pagination();
}
