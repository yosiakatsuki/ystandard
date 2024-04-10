<?php
/**
 * タクソノミー・ターム関連の関数
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */
defined( 'ABSPATH' ) || die();

/**
 * 記事一覧 ターム情報取得
 *
 * @param string $taxonomy タクソノミー.
 */
function ys_get_the_term_data( $taxonomy = false ) {
	return \ystandard\Taxonomy::get_the_term_data( $taxonomy );
}

/**
 * タクソノミー情報を取得
 *
 * @return array | bool
 */
function ys_get_the_taxonomies_data() {
	return \ystandard\Taxonomy::get_the_taxonomies_data();
}

/**
 * タクソノミー表示用アイコン取得
 *
 * @param string $taxonomy name.
 *
 * @return string
 */
function ys_get_taxonomy_icon( $taxonomy = false ) {
	return \ystandard\Utility::get_taxonomy_icon( $taxonomy );
}
