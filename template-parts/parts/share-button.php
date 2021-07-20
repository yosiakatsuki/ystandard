<?php
/**
 * シェアボタン テンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

defined( 'ABSPATH' ) || die();

if ( empty( $share_button ) ) {
	return;
}
/**
 * デフォルト : circle
 */
ys_get_template_part(
	'temlate-parts/parts/share-button-circle',
	'',
	[ 'share_button' => $share_button ]
);
