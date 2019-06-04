<?php
/**
 * 前の記事・次の記事テンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

if ( ! is_single() ) {
	return;
}

/**
 * 前の記事・次の記事
 */
ys_do_shortcode(
	'ys_post_paging',
	array(
		'class' => 'post-paging singular-footer__block',
		'title' => '',
	)
);