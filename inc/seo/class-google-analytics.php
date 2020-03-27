<?php
/**
 * Google Analytics
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

/**
 * Class Google_Analytics
 *
 * @package ystandard
 */
class Google_Analytics {

	/**
	 * Google_Analytics constructor.
	 */
	public function __construct() {
		add_action( 'customize_register', [ $this, 'customize_register' ] );
		add_action( 'wp_head', [ $this, 'google_analytics' ] );
	}

	/**
	 * Google Analytics
	 */
	public function google_analytics() {

		if ( ! $this->is_enable_google_analytics() ) {
			return;
		}
		ob_start();
		Template::get_template_part(
			'template-parts/parts/ga',
			Option::get_option( 'ys_ga_tracking_type', 'gtag' ),
			[
				'ys_tracking_id' => trim( Option::get_option( 'ys_ga_tracking_id', '' ) ),
			]
		);
		echo ob_get_clean();
	}

	/**
	 * Google Analyticsのタグを出力するか
	 *
	 * @return bool
	 */
	public static function is_enable_google_analytics() {
		if ( AMP::is_amp() ) {
			return false;
		}
		/**
		 * 設定チェック
		 */
		if ( ! trim( Option::get_option( 'ys_ga_tracking_id', '' ) ) ) {
			return false;
		}
		/**
		 * ログイン中にGA出力しない場合
		 */
		if ( Option::get_option_by_bool( 'ys_ga_exclude_logged_in_user', false ) ) {
			if ( is_user_logged_in() ) {
				/**
				 * 編集権限を持っている場合のみ出力しない
				 */
				if ( current_user_can( 'edit_posts' ) ) {
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * カスタマイザー追加
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 */
	public function customize_register( $wp_customize ) {
		$customizer = new Customize_Control( $wp_customize );

		$customizer->add_section(
			[
				'section'  => 'ys_google_analytics',
				'title'    => 'Google Analytics',
				'priority' => 10,
				'panel'    => SEO::PANEL_NAME,
			]
		);

		/**
		 * Google Analytics トラッキングID
		 */
		$customizer->add_text(
			[
				'id'          => 'ys_ga_tracking_id',
				'default'     => '',
				'transport'   => 'postMessage',
				'label'       => 'Google Analytics トラッキングID',
				'input_attrs' => [
					'placeholder' => 'UA-00000000-0',
				],
			]
		);
		/**
		 * トラッキングコードタイプ
		 */
		$customizer->add_radio(
			[
				'id'          => 'ys_ga_tracking_type',
				'default'     => 'gtag',
				'transport'   => 'postMessage',
				'label'       => 'トラッキングコードタイプ',
				'description' => 'Google Analytics トラッキングコードタイプを選択出来ます。<br>※デフォルトはグローバル サイトタグ(gtag.js)です。',
				'choices'     => [
					'gtag'      => 'グローバル サイトタグ(gtag.js)',
					'analytics' => 'ユニバーサルアナリティクス(analytics.js)',
				],
			]
		);
		/**
		 * ログイン中はアクセス数をカウントしない
		 */
		$customizer->add_checkbox(
			[
				'id'          => 'ys_ga_exclude_logged_in_user',
				'default'     => 0,
				'transport'   => 'postMessage',
				'label'       => '管理画面ログイン中はアクセス数カウントを無効にする（「購読者」ユーザーを除く）',
				'description' => 'チェックを付けた場合、ログインユーザーのアクセスではGoogle Analyticsのトラッキングコードを出力しません',
			]
		);
	}
}

new Google_Analytics();
