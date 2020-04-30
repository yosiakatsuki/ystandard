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
class Header_Media {

	/**
	 * Custom_Header constructor.
	 */
	public function __construct() {
		add_action( 'customize_register', [ $this, 'customize_register' ] );

		add_filter( 'header_video_settings', [ $this, 'header_video_settings' ] );
		add_filter( Enqueue_Utility::FILTER_INLINE_CSS, [ $this, 'header_media_style' ] );
		add_action( 'ys_after_site_header', [ $this, 'header_media' ], 2 );
	}

	/**
	 * カスタムヘッダーの出力
	 */
	public static function header_media_markup() {
		$shortcode = Option::get_option( 'ys_wp_header_media_shortcode', '' );
		if ( $shortcode ) {
			echo do_shortcode( $shortcode );
		} else {
			the_custom_header_markup();
		}
	}

	/**
	 * ヘッダーメディアタイプ取得
	 *
	 * @return string
	 */
	public static function get_header_media_type() {
		$type = 'image';
		if ( is_header_video_active() && has_header_video() ) {
			$type = 'video';
		}

		return apply_filters( 'ys_get_custom_header_type', $type );
	}


	/**
	 * ヘッダーメディアが有効か
	 *
	 * @return bool
	 */
	public static function is_active_header_media() {

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
	public function header_media() {
		if ( self::is_active_header_media() ) {
			Template::get_template_part( 'template-parts/header/custom-header' );
		}
	}

	/**
	 * ヘッダーメディア用CSS
	 *
	 * @param string $css CSS.
	 *
	 * @return string
	 */
	public function header_media_style( $css ) {
		if ( ! self::is_active_header_media() ) {
			return $css;
		}

		$css .= '
		.wp-custom-header {
		  position: relative; }
		  .wp-custom-header img {
		    display: block;
		    width: 100%;
		    height: auto;
		    margin: 0 auto; }
		.header-media.is-video .wp-custom-header {
		  width: 100%;
		  padding-top: 56.25%; }
		  .header-media.is-video .wp-custom-header video,
		  .header-media.is-video .wp-custom-header iframe,
		  .header-media.is-video .wp-custom-header img {
		    position: absolute;
		    top: 0;
		    left: 0;
		    width: 100%;
		    height: 100%; }
		.wp-custom-header-video-button {
		  display: block;
		  position: absolute;
		  bottom: 0.5em;
		  left: 0.5em;
		  padding: 0.5em;
		  background-color: rgba(0, 0, 0, 0.3);
		  border: 1px solid rgba(255, 255, 255, 0.3);
		  color: rgba(255, 255, 255, 0.3);
		  font-size: 0.8em;
		  line-height: 1; }';

		return $css;
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
			'pause'      => Icon::get_icon( 'pause' ),
			'play'       => Icon::get_icon( 'play' ),
			'pauseSpeak' => __( 'Video is paused.' ),
			'playSpeak'  => __( 'Video is playing.' ),
		];
		$settings['minWidth'] = 200;

		return $settings;
	}

	/**
	 * モバイル用デザイン設定追加
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 */
	public function customize_register( $wp_customize ) {
		$customizer = new Customize_Control( $wp_customize );

		$dscr = $wp_customize->get_section( 'header_image' )->description;
		$customizer->set_section_description(
			'header_image',
			$dscr . Admin::manual_link( 'https://wp-ystandard.com/custom-header/' )
		);
		// Refresh.
		$customizer->set_refresh( 'header_video' );
		$customizer->set_refresh( 'external_header_video' );
		$customizer->set_refresh( 'header_image_data' );
		/**
		 * ヘッダーメディアショートコード
		 */
		$customizer->add_text(
			[
				'id'          => 'ys_wp_header_media_shortcode',
				'default'     => '',
				'label'       => '[ys]ヘッダーメディア用ショートコード',
				'description' => 'ヘッダー画像をプラグイン等のショートコードで出力する場合、ショートコードを入力してください。',
				'section'     => 'header_image',
				'priority'    => 50,
			]
		);
	}
}

new Header_Media();
