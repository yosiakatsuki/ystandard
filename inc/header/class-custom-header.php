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
		add_action( 'ys_after_site_header', [ $this, 'custom_header' ], 2 );
	}

	/**
	 * カスタムヘッダーの出力
	 */
	public static function custom_header_markup() {
		$shortcode = Option::get_option( 'ys_wp_header_media_shortcode', '' );
		if ( $shortcode ) {
			echo do_shortcode( $shortcode );
		} else {
			the_custom_header_markup();
		}
	}

	/**
	 * カスタムヘッダータイプ取得
	 *
	 * @return string
	 */
	public static function get_custom_header_type() {
		$type = 'image';
		if ( is_header_video_active() && has_header_video() ) {
			$type = 'video';
		}

		return apply_filters( 'ys_get_custom_header_type', $type );
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
			if ( Option::get_option( 'ys_wp_header_media_shortcode', '' ) ) {
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

	/**
	 * カスタムヘッダー表示
	 */
	public function custom_header() {
		if ( self::is_active_custom_header() ) {
			Template::get_template_part( 'template-parts/header/custom-header' );
		}
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
}

new Custom_Header();
