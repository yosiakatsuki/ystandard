<?php
/**
 * AMP関連
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

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
	}

	/**
	 * AMP判定
	 *
	 * @return bool
	 */
	public static function is_amp() {
		$ys_amp = false;
		if ( Option::get_option_by_bool( 'ys_amp_enable_amp_plugin_integration', false ) ) {
			/**
			 * AMPプラグインでAMPページが作成されているか判断
			 */
			$is_amp_endpoint = function_exists( 'is_amp_endpoint' ) && is_amp_endpoint();
		}

		return apply_filters( 'ys_is_amp', $ys_amp );
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
				'section' => 'ys_amp_enable',
				'title'   => 'AMPプラグイン連携機能 設定',
			]
		);
		$customizer->add_checkbox(
			[
				'id'          => 'ys_amp_enable_amp_plugin_integration',
				'default'     => 0,
				'label'       => 'AMPプラグイン連携機能を有効化',
				'description' => '「AMP」プラグインの連携機能を有効化します。AMP用広告設定などが追加されます。',
			]
		);
	}

}


new AMP();
