<?php
/**
 * 詳細ページ用ページネーション
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

wp_link_pages(
	array(
		'before'      => '<nav class="page-links pagination flex flex--j-center">',
		'after'       => '</nav>',
		'link_before' => '<span class="page-links__item pagination__item flex flex--c-c">',
		'link_after'  => '</span>',
		'pagelink'    => '<span class="page-links__text">%</span>',
		'separator'   => '',
	)
);