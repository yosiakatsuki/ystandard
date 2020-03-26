<?php
/**
 * 画像関連の処理
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */


/**
 * パブリッシャー用画像取得
 */
function ys_get_publisher_image() {
	/**
	 * パブリッシャー画像の取得
	 */
	$image = ys_get_option( 'ys_option_structured_data_publisher_image', '' );
	if ( $image ) {
		if ( $image ) {
			return $image;
		}
	}
	/**
	 * ロゴ設定の取得
	 */
	$image = ys_get_custom_logo_image_object();
	if ( $image ) {
		return $image;
	}

	return get_template_directory_uri() . '/assets/images/publisher-logo/default-publisher-logo.png';
}
