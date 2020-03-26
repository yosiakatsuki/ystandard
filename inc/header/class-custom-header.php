<?php
/**
 * カスタムヘッダー・ヘッダーメディア
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

/**
 * Class Custom_Header
 *
 * @package ystandard
 */
class Custom_Header {

	/**
	 * Custom_Header constructor.
	 */
	public function __construct() {
		add_filter( 'header_video_settings', [ $this, 'header_video_settings' ] );
	}

	/**
	 * ビデオの停止ボタンを削除
	 *
	 * @param array $settings ビデオ設定.
	 *
	 * @return array;
	 */
	public function header_video_settings( $settings ) {
		$settings['l10n']     = [
			'pause'      => '<i class="fas fa-pause"></i>',
			'play'       => '<i class="fas fa-play"></i>',
			'pauseSpeak' => __( 'Video is paused.' ),
			'playSpeak'  => __( 'Video is playing.' ),
		];
		$settings['minWidth'] = 200;

		return $settings;
	}


	/**
	 * カスタムヘッダーが有効か
	 *
	 * @return bool
	 */
	public static function is_active_custom_header() {

		if ( Template::is_top_page() ) {
			if ( self::has_custom_image() ) {
				return true;
			}
			if ( ys_get_option( 'ys_wp_header_media_shortcode', '' ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * ヘッダー画像が有効か
	 *
	 * @return bool
	 */
	public static function has_custom_image() {
		if ( get_custom_header_markup() ) {
			return true;
		}

		return false;
	}
}

new Custom_Header();
