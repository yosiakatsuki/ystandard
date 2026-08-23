<?php
/**
 * Google Analytics
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

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

		// 無効な測定IDや除外対象では解析タグを出力しない.
		if ( ! $this->is_enable_google_analytics() ) {
			return;
		}
		$ys_tracking_option = apply_filters( 'ys_google_analytics_additional_config_info', [] );
		// gtag.jsの設定オブジェクトとして安全に扱えない値は破棄する.
		if ( ! is_array( $ys_tracking_option ) ) {
			$ys_tracking_option = [];
		}
		ob_start();
		get_template_part(
			'template-parts/google-analytics/gtag',
			null,
			[
				'ys_tracking_id'     => trim( Option::get_option( 'ys_ga_tracking_id', '' ) ),
				'ys_tracking_option' => $ys_tracking_option,
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
		// ウィジェットプレビューの通信をアクセスとして計測しない.
		if ( Widget::is_legacy_widget_preview() ) {
			return false;
		}
		$tracking_id = trim( Option::get_option( 'ys_ga_tracking_id', '' ) );
		// Universal Analyticsや不正なIDで無効なタグを読み込まない.
		if ( ! preg_match( '/^G-[A-Z0-9]+$/', $tracking_id ) ) {
			return false;
		}
		/**
		 * ログイン中にGA出力しない場合
		 */
		if ( Option::get_option_by_bool( 'ys_ga_exclude_logged_in_user', false ) ) {
			// 購読者のアクセスは除外対象に含めないため権限を段階的に確認する.
			if ( is_user_logged_in() ) {
				// サイトを編集できるユーザーの確認アクセスは計測しない.
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
				'section'     => 'ys_google_analytics',
				'title'       => 'Google Analytics',
				'priority'    => 10,
				'description' => Admin::manual_link( 'manual/google-analytics' ),
				'panel'       => SEO::PANEL_NAME,
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
				'label'       => __( 'Google Analytics 測定ID', 'ystandard' ),
				'description' => __( '「G-」から始まるGA4の測定IDを入力してください。', 'ystandard' ),
				'input_attrs' => [
					'placeholder' => 'G-0000000000',
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
