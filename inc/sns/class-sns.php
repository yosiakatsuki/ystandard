<?php
/**
 * SNS関連
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

/**
 * Class SNS
 *
 * @package ystandard
 */
class SNS {

	/**
	 * Panel name.
	 */
	const PANEL_NAME = 'ys_sns';

	/**
	 * Enqueue : Twitter.
	 */
	const HANDLE_TWITTER = 'twitter-js';
	/**
	 * Enqueue : Facebook.
	 */
	const HANDLE_FACEBOOK = 'facebook-js';

	/**
	 * Facebook App Version.
	 */
	const FACEBOOK_API_VERSION = 'v6.0';

	/**
	 * Copyright constructor.
	 */
	public function __construct() {
		add_action( 'customize_register', [ $this, 'customize_register' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_sns_scripts' ] );
	}


	/**
	 * サイト内設定で使用するSNSのリスト
	 *
	 * @return array
	 */
	public static function get_sns_icons() {
		return apply_filters(
			'ys_get_sns_icons',
			[
				'twitter'   => [
					'class'      => 'twitter',
					'option_key' => 'twitter',
					'icon'       => 'fab fa-twitter',
					'color'      => 'twitter',
					'title'      => 'twitter',
					'label'      => 'Twitter',
				],
				'facebook'  => [
					'class'      => 'facebook',
					'option_key' => 'facebook',
					'icon'       => 'fab fa-facebook-f',
					'color'      => 'facebook',
					'title'      => 'facebook',
					'label'      => 'Facebook',
				],
				'instagram' => [
					'class'      => 'instagram',
					'option_key' => 'instagram',
					'icon'       => 'fab fa-instagram',
					'color'      => 'instagram',
					'title'      => 'instagram',
					'label'      => 'Instagram',
				],
				'tumblr'    => [
					'class'      => 'tumblr',
					'option_key' => 'tumblr',
					'icon'       => 'fab fa-tumblr',
					'color'      => 'tumblr',
					'title'      => 'tumblr',
					'label'      => 'Tumblr',
				],
				'youtube'   => [
					'class'      => 'youtube',
					'option_key' => 'youtube',
					'icon'       => 'fab fa-youtube',
					'color'      => 'youtube-play',
					'title'      => 'youtube',
					'label'      => 'YouTube',
				],
				'github'    => [
					'class'      => 'github',
					'option_key' => 'github',
					'icon'       => 'fab fa-github',
					'color'      => 'github',
					'title'      => 'github',
					'label'      => 'GitHub',
				],
				'pinterest' => [
					'class'      => 'pinterest',
					'option_key' => 'pinterest',
					'icon'       => 'fab fa-pinterest-p',
					'color'      => 'pinterest',
					'title'      => 'pinterest',
					'label'      => 'Pinterest',
				],
				'linkedin'  => [
					'class'      => 'linkedin',
					'option_key' => 'linkedin',
					'icon'       => 'fab fa-linkedin-in',
					'color'      => 'linkedin',
					'title'      => 'linkedin',
					'label'      => 'LinkedIn',
				],
				'amazon'    => [
					'class'      => 'amazon',
					'option_key' => 'amazon',
					'icon'       => 'fab fa-amazon',
					'color'      => 'amazon',
					'title'      => 'amazon',
					'label'      => 'Amazon',
				],
			]
		);
	}

	/**
	 * SNS用JavaScriptの読み込み
	 */
	public function enqueue_sns_scripts() {
		if ( AMP::is_amp() ) {
			return;
		}
		/**
		 * Twitter関連スクリプト読み込み
		 */
		if ( Option::get_option_by_bool( 'ys_load_script_twitter', false ) ) {
			wp_enqueue_script(
				self::HANDLE_TWITTER,
				'//platform.twitter.com/widgets.js',
				[],
				Utility::get_ystandard_version(),
				true
			);
			Enqueue_Utility::add_defer( self::HANDLE_TWITTER );
		}
		/**
		 * Facebook関連スクリプト読み込み
		 */
		if ( Option::get_option_by_bool( 'ys_load_script_facebook', false ) ) {
			wp_enqueue_script(
				self::HANDLE_FACEBOOK,
				'//connect.facebook.net/ja_JP/sdk.js#xfbml=1&version=' . self::FACEBOOK_API_VERSION,
				[],
				Utility::get_ystandard_version(),
				true
			);
			Enqueue_Utility::add_defer( self::HANDLE_FACEBOOK );
		}
		do_action( 'ys_enqueue_sns_script' );
	}

	/**
	 * カスタマイザー追加
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 */
	public function customize_register( $wp_customize ) {
		$customizer = new Customize_Control( $wp_customize );

		$customizer->add_panel(
			[
				'panel' => self::PANEL_NAME,
				'title' => '[ys]SNS',
			]
		);

		/**
		 * SNS用JavaScript読み込み
		 */
		$customizer->add_section(
			[
				'section'     => 'ys_load_script',
				'title'       => 'SNS用JavaScript読み込み(上級者向け)',
				'priority'    => 1000,
				'description' => 'SNS用のJavaScriptを読み込みます。',
			]
		);
		/**
		 * Twitter用JavaScriptを読み込む
		 */
		$customizer->add_checkbox(
			[
				'id'        => 'ys_load_script_twitter',
				'default'   => 0,
				'transport' => 'postMessage',
				'label'     => 'Twitter用JavaScriptを読み込む',
			]
		);
		/**
		 *  Facebook用JavaScriptを読み込む
		 */
		$customizer->add_checkbox(
			[
				'id'        => 'ys_load_script_facebook',
				'default'   => 0,
				'transport' => 'postMessage',
				'label'     => 'Facebook用JavaScriptを読み込む',
			]
		);
	}

}

new SNS();
