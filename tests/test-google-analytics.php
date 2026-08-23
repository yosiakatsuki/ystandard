<?php
/**
 * Google Analyticsのテスト
 *
 * @package ystandard
 */

/**
 * Class GoogleAnalyticsTest
 */
class GoogleAnalyticsTest extends WP_UnitTestCase {

	/**
	 * テスト終了処理
	 */
	public function tear_down() {
		delete_option( 'ys_ga_tracking_id' );
		delete_option( 'ys_ga_tracking_type' );
		delete_option( 'ys_ga_exclude_logged_in_user' );
		remove_all_filters( 'ys_google_analytics_additional_config_info' );
		wp_set_current_user( 0 );
		parent::tear_down();
	}

	/**
	 * GA4測定IDでgtag.jsを出力することを確認
	 */
	public function test_ga4_measurement_id_outputs_gtag() {
		update_option( 'ys_ga_tracking_id', 'G-TEST123' );

		$output = $this->get_google_analytics_output();

		$this->assertStringContainsString( 'googletagmanager.com/gtag/js?id=G-TEST123', $output );
		$this->assertStringContainsString( 'gtag(\'config\', "G-TEST123")', $output );
		$this->assertStringNotContainsString( 'analytics.js', $output );
	}

	/**
	 * 測定IDが空の場合はタグを出力しないことを確認
	 */
	public function test_empty_measurement_id_outputs_nothing() {
		$this->assertSame( '', $this->get_google_analytics_output() );
	}

	/**
	 * 保存済みUA設定が残っていても旧タグを出力しないことを確認
	 */
	public function test_universal_analytics_settings_output_nothing() {
		update_option( 'ys_ga_tracking_id', 'UA-12345-1' );
		update_option( 'ys_ga_tracking_type', 'analytics' );

		$this->assertSame( '', $this->get_google_analytics_output() );
	}

	/**
	 * 追加設定フィルターの値をgtag.jsへ引き継ぐことを確認
	 */
	public function test_additional_config_filter_is_preserved() {
		update_option( 'ys_ga_tracking_id', 'G-TEST123' );
		add_filter(
			'ys_google_analytics_additional_config_info',
			function () {
				return [ 'send_page_view' => false ];
			}
		);

		$output = $this->get_google_analytics_output();

		$this->assertStringContainsString( '"send_page_view":false', $output );
	}

	/**
	 * 編集権限を持つログインユーザーを計測から除外できることを確認
	 */
	public function test_logged_in_editor_can_be_excluded() {
		update_option( 'ys_ga_tracking_id', 'G-TEST123' );
		update_option( 'ys_ga_exclude_logged_in_user', 1 );
		wp_set_current_user( self::factory()->user->create( [ 'role' => 'editor' ] ) );

		$this->assertSame( '', $this->get_google_analytics_output() );
	}

	/**
	 * Google Analyticsの出力を取得
	 *
	 * @return string
	 */
	private function get_google_analytics_output() {
		$google_analytics = ( new ReflectionClass( \ystandard\Google_Analytics::class ) )->newInstanceWithoutConstructor();
		ob_start();
		$google_analytics->google_analytics();

		return ob_get_clean();
	}
}
