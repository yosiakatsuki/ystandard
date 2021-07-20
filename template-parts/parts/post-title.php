<?php
/**
 * 投稿タイトル
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

defined( 'ABSPATH' ) || die();

do_action( 'ys_singular_before_title' );
the_title(
	'<h1 class="singular-header__title entry-title">',
	'</h1>'
);
do_action( 'ys_singular_after_title' );
