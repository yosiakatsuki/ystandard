<?php
/**
 * アーカイブ関連の関数
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */
defined( 'ABSPATH' ) || die();


/**
 * アーカイブ明細 CSSクラス作成
 *
 * @param string|string[] $class Class.
 *
 * @return array
 */
function ys_get_archive_item_class( $class = '' ) {

	return \ystandard\Archive::get_archive_item_class( $class );
}

/**
 * アーカイブ明細クラス出力
 */
function ys_the_archive_item_class() {
	echo implode( ' ', ys_get_archive_item_class() );
}

/**
 * アーカイブテンプレートタイプ取得
 */
function ys_get_archive_type() {
	return \ystandard\Archive::get_archive_type();
}

/**
 * アーカイブ 日付・カテゴリー情報
 */
function ys_the_archive_meta() {
	\ystandard\Archive::the_archive_meta();
}

/**
 * アーカイブ 日付情報
 *
 * @param string $icon アイコンを表示するか.
 */
function ys_the_archive_date( $icon = true ) {
	echo \ystandard\Archive::get_archive_detail_date( $icon );
}

/**
 * アーカイブ カテゴリー情報
 *
 * @param string $icon アイコンを表示するか.
 */
function ys_the_archive_category( $icon = true ) {
	echo \ystandard\Archive::get_archive_detail_category( $icon );
}

/**
 * アーカイブ 概要
 */
function ys_the_archive_description() {
	\ystandard\Archive::the_archive_description();
}

/**
 * アーカイブ リンク
 */
function ys_the_archive_read_more() {
	echo \ystandard\Archive::get_archive_detail_read_more();
}

/**
 * アーカイブ 画像縦横比
 */
function ys_the_archive_image_ratio() {
	echo esc_attr( \ystandard\Archive::get_archive_image_ratio() );
}

/**
 * アーカイブデフォルト画像
 *
 * @param string $class Class.
 * @param string $icon_class Icon Class.
 * @param string $thumbnail_size Thumbnail size.
 */
function ys_the_archive_default_image( $class = 'archive__no-img', $icon_class = 'archive__image', $thumbnail_size = 'full' ) {
	echo \ystandard\Archive::get_archive_default_image( $class, $icon_class, $thumbnail_size );
}
