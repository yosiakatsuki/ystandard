<?php
/**
 * AMP関連
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

/**
 * Class AMP
 *
 * @package ystandard
 */
class AMP {

	/**
	 * AMP constructor.
	 */
	public function __construct() {
		add_action( 'customize_register', [ $this, 'customize_register' ] );
		add_filter( 'ys_get_the_canonical_url', [ $this, 'remove_amp' ] );
		add_filter( 'ys_google_analytics_additional_config_info', [ $this, 'add_ga_tracking_option' ] );
	}

	/**
	 * AMP判定
	 *
	 * @return bool
	 */
	public static function is_amp() {
		$is_amp = false;
		if ( Option::get_option_by_bool( 'ys_amp_enable_amp_plugin_integration', false ) ) {
			/**
			 * AMPプラグインでAMPページが作成されているか判断
			 */
			$is_amp = function_exists( 'is_amp_endpoint' ) && is_amp_endpoint();
		}

		return apply_filters( 'ys_is_amp', $is_amp );
	}

	/**
	 * Canonical URL からampを削除
	 *
	 * @param string $url URL.
	 *
	 * @return string
	 */
	public function remove_amp( $url ) {
		return remove_query_arg( 'amp', $url );
	}

	/**
	 * Google Analyticsのパラメーター追加.
	 *
	 * @param array $options Options.
	 *
	 * @return array
	 */
	public function add_ga_tracking_option( $options ) {

		if ( Option::get_option_by_bool( 'ys_amp_enable_amp_plugin_integration', false ) ) {
			$options['useAmpClientId'] = true;
		}

		return $options;
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
				'panel' => 'ys_amp',
				'title' => '[ys]AMP',
			]
		);
		$customizer->add_section(
			[
				'section'     => 'ys_amp_enable',
				'title'       => 'AMPプラグイン連携機能 設定',
				'description' => Admin::manual_link( 'manual/ystadnard-amp-page' ),
			]
		);
		$description = '「AMP」プラグインの連携機能を有効化します。AMP用広告設定などが追加されます。';
		if ( ! function_exists( 'is_amp_endpoint' ) ) {
			$description .= '<br><strong>「AMP」プラグインが有効化されていません。AMPプラグイン連携機能を使う場合はプラグインをインストール・有効化してください</strong>';
		}
		$customizer->add_checkbox(
			[
				'id'          => 'ys_amp_enable_amp_plugin_integration',
				'default'     => 0,
				'label'       => 'AMPプラグイン連携機能を有効化',
				'description' => $description,
			]
		);
	}

}


new AMP();
