<?php
/**
 * シェアボタン テンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

defined( 'ABSPATH' ) || die();

/**
 * @var array $args {
 * @type string $share_button シェアボタン用データ.
 * }
 */
if ( empty( $args ) || ! isset( $args['share_button'] ) ) {
	return;
}
$share_button = $args['share_button'];
// シェアボタンデータチェック.
if ( empty( $share_button ) ) {
	return;
}
/**
 * デフォルト : circle
 */
ys_get_template_part(
	'temlate-parts/sns-share-button/share-button-circle',
	'',
	[ 'share_button' => $share_button ]
);
