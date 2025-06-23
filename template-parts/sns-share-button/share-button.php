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
 * 渡されるパラメーター情報.
 *
 * @var array $args {
 *     @type array $share_button {
 *         'official' => array {
 *               'url-encode' => URLエンコードしたURL.
 *               'title-encode' => URLエンコードしたタイトル.
 *               'url' => シェアするURL.
 *               'title' => シェアするタイトル.
 *               'twitter-via' => Twitterのvia属性.
 *               'twitter-related' => Twitterのvia属性.
 *         }
 *         'sns' => array {
 *             'x' => X (旧Twitter) シェアURL.
 *             'facebook' => Facebook シェアURL.
 *             'bluesky' => Bluesky シェアURL.
 *             'hatenabookmark' => はてなブックマーク シェアURL.
 *             'line' => LINE シェアURL.
 *         }
 *         'text' => array {
 *           'before' => シェアボタン前のテキスト.
 *           'after' => シェアボタン後のテキスト.
 *         }
 *     }
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
get_template_part(
	'temlate-parts/sns-share-button/share-button-circle',
	'',
	[ 'share_button' => $share_button ]
);
