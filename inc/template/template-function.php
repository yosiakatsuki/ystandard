<?php
/**
 * テンプレートで使用する関数
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * <head>タグにつける属性取得
 */
function ys_the_head_attr() {
	echo \ystandard\Head::get_head_attr();
}

/**
 * ヘッダーロゴ取得
 */
function ys_get_header_logo() {
	if ( has_custom_logo() && ! ys_get_option_by_bool( 'ys_logo_hidden', false ) ) {
		$logo = get_custom_logo();
	} else {
		$logo = sprintf(
			'<a href="%s" class="custom-logo-link" rel="home">%s</a>',
			esc_url( home_url( '/' ) ),
			get_bloginfo( 'name' )
		);
	}

	return apply_filters( 'ys_get_header_logo', $logo );
}

/**
 * サイトキャッチフレーズを取得
 */
function ys_the_blog_description() {
	if ( ys_get_option_by_bool( 'ys_wp_hidden_blogdescription', false ) ) {
		return;
	}
	echo sprintf(
		'<p class="site-description header__dscr text-sub">%s</p>',
		apply_filters( 'ys_the_blog_description', get_bloginfo( 'description', 'display' ) )
	);
}

/**
 * カスタムヘッダーが有効か
 *
 * @return bool
 */
function ys_is_active_custom_header() {
	return \ystandard\Custom_Header::is_active_custom_header();
}

/**
 * カスタムヘッダータイプ
 *
 * @return string
 */
function ys_get_custom_header_type() {
	return \ystandard\Custom_Header::get_custom_header_type();
}

/**
 * カスタムヘッダーの出力
 */
function ys_the_custom_header_markup() {
	\ystandard\Custom_Header::custom_header_markup();
}

/**
 * ページネーション
 */
function ys_get_pagination() {
	$pagination = new \ystandard\Pagination();

	return $pagination->get_pagination();
}

/**
 * アーカイブ明細クラス作成
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
	$classes = ys_get_archive_item_class();
	echo implode( ' ', $classes );
}

/**
 * アーカイブテンプレートタイプ取得
 */
function ys_get_archive_template_type() {
	return ys_get_option( 'ys_archive_type', 'list' );
}

/**
 * Front-pageでロードするテンプレート
 */
function ys_get_front_page_template() {
	$type = get_option( 'show_on_front' );
	if ( 'page' === $type ) {
		$template      = 'page';
		$page_template = get_page_template_slug();

		if ( $page_template ) {
			$template = str_replace( '.php', '', $page_template );
		}
	} else {
		$template = 'home';
	}

	return $template;
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
	return ystandard\Content::get_post_meta( $key, $post_id );
}


/**
 * インフィード広告の表示
 */
function ys_the_ad_infeed() {
	echo ystandard\Advertisement::get_infeed();
}

/**
 * テンプレート読み込み拡張
 *
 * @param string $slug The slug name for the generic template.
 * @param string $name The name of the specialised template.
 * @param array  $args テンプレートに渡す変数.
 */
function ys_get_template_part( $slug, $name = null, $args = [] ) {
	ystandard\Template::get_template_part( $slug, $name, $args );
}


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

/**
 * フッターコピーライト表示取得
 *
 * @return string
 */
function ys_get_footer_site_info() {

	return ystandard\Copyright::get_site_info();
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
	return ystandard\Copyright::get_copyright();
}

/**
 * Copyrightのデフォルト文字列を作成
 *
 * @return string
 */
function ys_get_copyright_default() {
	return ystandard\Copyright::get_default();
}


/**
 * Powered By
 *
 * @return string
 */
function ys_get_poweredby() {
	return ystandard\Copyright::get_poweredby();
}


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
	return ystandard\Option::get_option( $name, $default, $type );
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
	return ystandard\Option::get_option_by_bool( $name, $default );
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
	return ystandard\Option::get_option_by_int( $name, $default );
}
