<?php
/**
 * グロナビ関連の関数
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */
defined( 'ABSPATH' ) || die();


/**
 * グローバルナビゲーションクラス出力
 *
 * @param string $class class.
 */
function ys_global_nav_class( $class ) {
	echo \ystandard\Global_Nav::get_global_nav_class( $class );
}

/**
 * グローバルナビゲーションを表示するか
 *
 * @return boolean
 */
function ys_has_global_nav() {
	return \ystandard\Global_Nav::has_global_nav();
}

/**
 * グローバルナビゲーションワーカー
 *
 * @return \Walker_Nav_Menu
 */
function ys_global_nav_walker() {
	return \ystandard\Global_Nav::global_nav_walker();
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
 * @param array $args {
 * 開閉ボタンタイプ・クラス.
 *
 * @type string $type ボタンタイプ.
 * @type string $id ID.
 * @type string $class クラス.
 * }
 */
function ys_global_nav_toggle_button( $args = [] ) {
	echo \ystandard\Drawer_Menu::get_toggle_button( $args );
}

/**
 * モバイルフッターナビゲーションを出力
 *
 * @return void
 */
function ys_the_mobile_footer_menu() {
	echo \ystandard\Footer_Mobile_Nav::the_mobile_footer_menu();
}

/**
 * モバイルフッターナビゲーションを表示するか.
 *
 * @return bool
 */
function ys_show_footer_mobile_nav() {
	return \ystandard\Footer_Mobile_Nav::show_footer_mobile_nav();
}

/**
 * モバイルフッターCSSクラス出力
 *
 * @return void
 */
function ys_the_mobile_footer_classes() {
	echo \ystandard\Footer_Mobile_Nav::the_mobile_footer_classes();
}


