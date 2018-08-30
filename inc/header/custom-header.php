<?php
/**
 * ヘッダー画像
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * ヘッダー画像が有効か判定する
 *
 * @return bool
 */
function ys_has_header_image() {
	$result = false;
	if ( get_custom_header_markup() ) {
		$result = true;
	}

	return apply_filters( 'ys_has_header_image', $result );
}

/**
 * カスタムヘッダータイプ
 *
 * @return string
 */
function ys_get_custom_header_type() {
	$type  = 'image';
	$video = get_header_video_settings();
	if ( $video['videoUrl'] ) {
		$type = 'video';
	}

	return apply_filters( 'ys_get_custom_header_type', $type );
}

/**
 * カスタムヘッダーの出力
 */
function ys_the_custom_header_markup() {
	$media_shortcode = ys_get_option( 'ys_wp_header_media_shortcode' );
	if ( $media_shortcode ) {
		echo do_shortcode( $media_shortcode );
	} else {
		the_custom_header_markup();
	}
}

/**
 * ビデオの停止ボタンを削除
 *
 * @param array $settings ビデオ設定.
 *
 * @return array;
 */
function ys_header_video_settings( $settings ) {
	$settings['l10n'] = array(
		'pause'      => '<i class="fa fa-pause" aria-hidden="true"></i>',
		'play'       => '<i class="fa fa-play" aria-hidden="true"></i>',
		'pauseSpeak' => __( 'Video is paused.' ),
		'playSpeak'  => __( 'Video is playing.' ),
	);

	return $settings;
}

add_filter( 'header_video_settings', 'ys_header_video_settings' );