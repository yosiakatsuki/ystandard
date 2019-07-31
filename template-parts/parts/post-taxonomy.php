<?php
/**
 * タクソノミー表示
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

if ( ! ys_get_option( 'ys_show_post_category' ) ) {
	return;
}

/**
 * タクソノミー表示
 */
ys_do_shortcode(
	'ys_post_tax',
	array(
		'class' => 'singular-footer__block',
		'title' => '',
	)
);