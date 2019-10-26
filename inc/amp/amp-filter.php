<?php
/**
 * AMPページに関わるフィルター処理
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 投稿内容をAMPに変換する
 *
 * @param string $content 投稿内容.
 *
 * @return string
 */
function ys_amp_convert_content( $content ) {
	if ( ! ys_is_ystd_amp() ) {
		return $content;
	}
	/**
	 * AMP関連の置換の前になにか置換したい場合
	 */
	$content = apply_filters( 'ys_amp_convert_before', $content );
	$content = ys_amp_convert_all( $content );

	return apply_filters( 'ys_convert_amp', $content );
}

add_filter( 'the_content', 'ys_amp_convert_content', 999 );

/**
 * AMPフォーマットへ変換
 *
 * @param string $content content.
 *
 * @return string
 */
function ys_amp_convert_all( $content ) {
	/**
	 * HTMLタグなどの置換
	 */
	$content = ys_amp_convert_html( $content );
	/**
	 * SNS埋め込みの置換
	 */
	$content = ys_amp_convert_sns( $content );
	/**
	 * 画像の置換
	 */
	$content = ys_amp_convert_image( $content );
	/**
	 * Iframeの置換
	 */
	$content = ys_amp_convert_iframe( $content );
	/**
	 * Scriptの削除
	 */
	$content = ys_amp_delete_script( $content );

	return $content;
}

/**
 * 投稿者画像のAMP変換セット
 */
add_filter( 'get_avatar', 'ys_amp_get_amp_image_tag', 999 );

/**
 * AMP連携:スクリプト削除
 */
function ys_amp_dequeue_script() {
	if ( ! ys_is_amp() ) {
		return;
	}
	$scripts = YS_Scripts::get_enqueue_script_files();
	foreach ( $scripts as $item ) {
		wp_dequeue_script( $item['handle'] );
	}
}

add_action( 'wp_enqueue_script', 'ys_amp_dequeue_script', PHP_INT_MAX );