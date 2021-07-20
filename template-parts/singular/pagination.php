<?php
/**
 * 詳細ページ用ページネーション
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

defined( 'ABSPATH' ) || die();

wp_link_pages(
	[
		'before'      => '<nav class="page-links pagination">',
		'after'       => '</nav>',
		'link_before' => '<span class="page-links__item pagination__item">',
		'link_after'  => '</span>',
		'pagelink'    => '<span class="page-links__text">%</span>',
		'separator'   => '',
	]
);
