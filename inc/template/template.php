<?php
/**
 * テンプレートのロード等関連の関数
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * Require
 */
require_once dirname( __FILE__ ) . '/class-template-function.php';


/**
 * アーカイブ明細クラス作成
 *
 * @return array
 */
function ys_get_archive_item_class() {

	return ystandard\Content::get_archive_item_class();
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
	return \ystandard\Post::get_post_meta( $key, $post_id );
}


/**
 * インフィード広告の表示
 */
function ys_the_ad_infeed() {
	echo \ystandard\Advertisement::get_infeed();
}

/**
 * テンプレート読み込み拡張
 *
 * @param string $slug The slug name for the generic template.
 * @param string $name The name of the specialised template.
 * @param array  $args テンプレートに渡す変数.
 */
function ys_get_template_part( $slug, $name = null, $args = [] ) {
	\ystandard\Template_Function::get_template_part( $slug, $name, $args );
}
