<?php
/**
 * テンプレートで使用する関数
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/** *****************************************************************
 * HEAD関連
 * *****************************************************************/

/**
 * <head>タグにつける属性取得
 */
function ys_the_head_attr() {
	echo \ystandard\Head::get_head_attr();
}

/** *****************************************************************
 * ロゴ・グローバルナビゲーション
 * *****************************************************************/

/**
 * ヘッダーロゴ取得
 *
 * @return string;
 */
function ys_get_header_logo() {
	return \ystandard\Header::get_header_logo();
}

/**
 * サイトキャッチフレーズを取得
 */
function ys_the_blog_description() {
	echo \ystandard\Header::get_blog_description();
}


/**
 * グローバルナビゲーションクラス出力
 *
 * @param string $class class.
 */
function ys_global_nav_class( $class ) {
	echo \ystandard\Header::get_global_nav_class( $class );
}

/**
 * スライドメニュー内に検索フォームを表示するか
 *
 * @return bool
 */
function ys_is_active_header_search_form() {
	return \ystandard\Header::is_active_header_search_form();
}

/**
 * メニュー開閉ボタンの出力
 *
 * @param string $type 開閉タイプ.
 */
function ys_global_nav_toggle_button( $type = 'toggle' ) {
	echo \ystandard\Header::get_toggle_button( $type );
}

/** *****************************************************************
 * カスタムヘッダー
 * *****************************************************************/

/**
 * カスタムヘッダーが有効か
 *
 * @return bool
 */
function ys_is_active_header_media() {
	return \ystandard\Header_Media::is_active_header_media();
}

/**
 * カスタムヘッダータイプ
 *
 * @return string
 */
function ys_get_header_media_type() {
	return \ystandard\Header_Media::get_header_media_type();
}

/**
 * カスタムヘッダーの出力
 */
function ys_the_header_media_markup() {
	\ystandard\Header_Media::header_media_markup();
}


/** *****************************************************************
 * コンテンツ
 * *****************************************************************/

/**
 * Front-pageでロードするテンプレート
 */
function ys_get_front_page_template() {
	return \ystandard\Template::get_front_page_template();
}

/**
 * 投稿ヘッダー情報を隠すか
 */
function ys_is_active_post_header() {
	return \ystandard\Template::is_active_post_header();
}

/**
 * タイトル無しテンプレート判定
 */
function ys_is_no_title_template() {

	return \ystandard\Template::is_no_title_template();
}

/**
 * 投稿抜粋文を作成
 *
 * @param string  $sep     抜粋最後の文字.
 * @param integer $length  抜粋長さ.
 * @param integer $post_id 投稿ID.
 *
 * @return string
 */
function ys_get_custom_excerpt( $sep = ' …', $length = 0, $post_id = 0 ) {
	return \ystandard\Content::get_custom_excerpt( $sep, $length, $post_id );
}

/**
 * 投稿オプション(post-meta)取得
 *
 * @param string  $key     設定キー.
 * @param integer $post_id 投稿ID.
 *
 * @return string
 */
function ys_get_post_meta( $key, $post_id = 0 ) {
	return \ystandard\Content::get_post_meta( $key, $post_id );
}

/** *****************************************************************
 * アーカイブ
 * *****************************************************************/

/**
 * アーカイブ明細 CSSクラス作成
 *
 * @return array
 */
function ys_get_archive_item_class() {

	return \ystandard\Content::get_archive_item_class();
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
 * アーカイブ 概要
 */
function ys_the_archive_description() {
	\ystandard\Archive::the_archive_description();
}

/**
 * アーカイブ 画像縦横比
 */
function ys_the_archive_image_ratio() {
	echo esc_attr( \ystandard\Archive::get_archive_image_ratio() );
}

/** *****************************************************************
 * ページネーション
 * *****************************************************************/

/**
 * ページネーション
 */
function ys_get_pagination() {
	$pagination = new \ystandard\Pagination();

	return $pagination->get_pagination();
}


/** *****************************************************************
 * サイドバー
 * *****************************************************************/

/**
 * サイドバーを表示するか
 */
function ys_is_active_sidebar() {
	return \ystandard\Widget::is_active_sidebar();
}

/** *****************************************************************
 * 広告
 * *****************************************************************/

/**
 * インフィード広告の表示
 */
function ys_the_ad_infeed() {
	echo \ystandard\Advertisement::get_infeed();
}

/** *****************************************************************
 * フッター
 * *****************************************************************/

/**
 * フッターウィジェットが有効か
 */
function ys_is_active_footer_widgets() {
	return \ystandard\Footer::is_active_widget();
}

/**
 * サブフッターコンテンツ取得
 */
function ys_get_footer_sub_contents() {
	return \ystandard\Footer::get_footer_sub_contents();
}

/** *****************************************************************
 * コピーライト
 * *****************************************************************/

/**
 * フッターコピーライト表示取得
 *
 * @return string
 */
function ys_get_footer_site_info() {

	return \ystandard\Copyright::get_site_info();
}

/**
 * フッターコピーライト表示
 *
 * @return void
 */
function ys_the_footer_site_info() {
	echo \ystandard\Copyright::get_site_info();
}

/**
 * Copyright
 *
 * @return string
 */
function ys_get_copyright() {
	return \ystandard\Copyright::get_copyright();
}

/**
 * Copyrightのデフォルト文字列を作成
 *
 * @return string
 */
function ys_get_copyright_default() {
	return \ystandard\Copyright::get_default();
}


/**
 * Powered By
 *
 * @return string
 */
function ys_get_poweredby() {
	return \ystandard\Copyright::get_poweredby();
}

/** *****************************************************************
 * アイコン
 * *****************************************************************/

/**
 * アイコン取得
 *
 * @param string $name  name.
 * @param string $class class.
 *
 * @return string
 */
function ys_get_icon( $name, $class = '' ) {
	return \ystandard\Icon::get_icon( $name, $class );
}

/**
 * SNSアイコン取得
 *
 * @param string $name  name.
 * @param string $title title.
 *
 * @return string
 */
function ys_get_sns_icon( $name, $title = '' ) {
	return \ystandard\Icon::get_sns_icon( $name, $title );
}

/** *****************************************************************
 * テーマバージョン
 * *****************************************************************/

/**
 * テーマバージョン取得
 *
 * @param boolean $parent 親テーマ情報かどうか.
 *
 * @return string
 */
function ys_get_theme_version( $parent = false ) {
	return \ystandard\Utility::get_theme_version( $parent );
}

/**
 * 親テーマ(yStandard) バージョン取得
 *
 * @param boolean $parent 親テーマ情報かどうか.
 *
 * @return string
 */
function ys_get_ystandard_version( $parent = false ) {
	return \ystandard\Utility::get_ystandard_version();
}

/** *****************************************************************
 * 設定
 * *****************************************************************/

/**
 * 設定取得
 *
 * @param string $name    option key.
 * @param mixed  $default デフォルト値.
 * @param mixed  $type    取得する型.
 *
 * @return mixed
 */
function ys_get_option( $name, $default = false, $type = false ) {
	return \ystandard\Option::get_option( $name, $default, $type );
}

/**
 * 設定取得(bool)
 *
 * @param string $name    option key.
 * @param mixed  $default デフォルト値.
 *
 * @return mixed
 */
function ys_get_option_by_bool( $name, $default = false ) {
	return \ystandard\Option::get_option_by_bool( $name, $default );
}

/**
 * 設定取得(int)
 *
 * @param string $name    option key.
 * @param mixed  $default デフォルト値.
 *
 * @return mixed
 */
function ys_get_option_by_int( $name, $default = 0 ) {
	return \ystandard\Option::get_option_by_int( $name, $default );
}

/** *****************************************************************
 * Embed関連
 * *****************************************************************/
/**
 * Embed用コンテンツ取得.
 */
function ys_embed_content() {
	$embed = new \ystandard\Embed();
	echo $embed->get_embed_content();
}

/** *****************************************************************
 * AMP関連
 * *****************************************************************/
function ys_is_amp() {
	return \ystandard\AMP::is_amp();
}

/** *****************************************************************
 * その他
 * *****************************************************************/

/**
 * テンプレート読み込み拡張
 *
 * @param string $slug The slug name for the generic template.
 * @param string $name The name of the specialised template.
 * @param array  $args テンプレートに渡す変数.
 */
function ys_get_template_part( $slug, $name = null, $args = [] ) {
	\ystandard\Template::get_template_part( $slug, $name, $args );
}

/**
 * モバイル判定
 */
function ys_is_mobile() {
	return \ystandard\Template::is_mobile();
}
