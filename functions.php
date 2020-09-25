<?php
/**
 * テーマ内で使用する関数
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 関数群を読み込み
 */
require_once __DIR__ . '/inc/class-ys-loader.php';


add_filter(
	'ys_get_archive_default_image',
	function ( $image ) {
		return wp_get_attachment_image(
			6196,
			'full',
			false,
			[
				'class' => 'archive__image',
				'alt'   => get_the_title(),
			]
		);
	}
);
