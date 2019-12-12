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
	$type = 'image';
	if ( is_header_video_active() && has_header_video() ) {
		$type = 'video';
	}
	/**
	 * 詳細ページではフルサムネイル表示か確認
	 */
	if ( ys_is_full_width_thumbnail() && ! ys_is_active_custom_header() ) {
		$type = 'full-thumb';
	}

	return apply_filters( 'ys_get_custom_header_type', $type );
}

/**
 * カスタムヘッダーの出力
 */
function ys_the_custom_header_markup() {
	if ( ys_is_full_width_thumbnail() && ! ys_is_active_custom_header() ) {
		/**
		 * 個別ページの画像表示
		 */
		printf(
			'<div class="header__full-thumbnail">%s</div>',
			ys_amp_get_amp_image_tag( get_the_post_thumbnail() )
		);
	} else {
		/**
		 * ショートコード入力があればそちらを優先
		 */
		$media_shortcode = ys_get_option( 'ys_wp_header_media_shortcode' );
		if ( $media_shortcode ) {
			echo do_shortcode( $media_shortcode );
		} else {
			the_custom_header_markup();
		}
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
	$settings['l10n']     = array(
		'pause'      => '<i class="fas fa-pause"></i>',
		'play'       => '<i class="fas fa-play"></i>',
		'pauseSpeak' => __( 'Video is paused.' ),
		'playSpeak'  => __( 'Video is playing.' ),
	);
	$settings['minWidth'] = 200;

	return $settings;
}

add_filter( 'header_video_settings', 'ys_header_video_settings' );