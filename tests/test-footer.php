<?php
/**
 * Class Footer_Test
 *
 * @package ystandard
 */

/**
 * Class Footer_Test
 */
class Footer_Test extends WP_UnitTestCase {

	/**
	 * クラシックテーマで空のフッターパーツを編集でき、旧ウィジェットエリアを登録しないことを確認
	 */
	public function test_footer_template_part_replaces_widget_areas() {
		$this->assertTrue( current_theme_supports( 'block-template-parts' ) );
		$this->assertFalse( wp_is_block_theme() );
		$template_part = get_block_template( get_stylesheet() . '//footer', 'wp_template_part' );

		$this->assertNotNull( $template_part );
		$this->assertSame( 'footer', $template_part->area );
		$this->assertSame( '', trim( $template_part->content ) );
		$this->assertFalse( ys_is_active_footer_main_contents() );

		foreach ( [ 'footer-left', 'footer-center', 'footer-right' ] as $sidebar ) {
			$this->assertArrayNotHasKey( $sidebar, $GLOBALS['wp_registered_sidebars'] );
		}
	}

	/**
	 * メニューがなくても保存したフッターパーツを表示し、既存のコピーライトを維持することを確認
	 */
	public function test_footer_renders_saved_template_part_without_menu() {
		$part_id = self::factory()->post->create(
			[
				'post_type'    => 'wp_template_part',
				'post_status'  => 'publish',
				'post_name'    => 'footer',
				'post_content' => '<!-- wp:paragraph --><p>Footer block content</p><!-- /wp:paragraph -->',
			]
		);
		wp_set_object_terms( $part_id, get_stylesheet(), 'wp_theme' );
		wp_set_object_terms( $part_id, 'footer', 'wp_template_part_area' );

		$this->assertTrue( ys_is_active_footer_template_part() );
		$this->assertTrue( ys_is_active_footer_main_contents() );
		$output = $this->render_footer();

		$this->assertStringContainsString( 'class="footer-main"', $output );
		$this->assertStringContainsString( '<p>Footer block content</p>', $output );
		$this->assertStringContainsString( 'class="copyright-wrapper"', $output );
		$this->assertStringNotContainsString( 'class="footer-widget"', $output );
		$this->assertStringNotContainsString( 'class="footer-nav"', $output );
	}

	/**
	 * 空のパーツを出力せず、フッターメニューだけでも表示できることを確認
	 */
	public function test_footer_renders_navigation_without_empty_template_part() {
		$menu_id = wp_create_nav_menu( 'Footer test menu' );
		wp_update_nav_menu_item(
			$menu_id,
			0,
			[
				'menu-item-title'  => 'Footer menu link',
				'menu-item-url'    => home_url( '/footer-link/' ),
				'menu-item-status' => 'publish',
			]
		);
		set_theme_mod( 'nav_menu_locations', [ 'footer' => $menu_id ] );

		$this->assertTrue( ys_is_active_footer_main_contents() );
		$output = $this->render_footer();

		$this->assertStringContainsString( 'class="footer-main"', $output );
		$this->assertStringContainsString( 'class="footer-nav"', $output );
		$this->assertStringContainsString( 'Footer menu link', $output );
		$this->assertStringNotContainsString( 'wp-block-template-part', $output );
	}

	/**
	 * 通常のページ表示と同じ初期化を行ってフッターを描画
	 *
	 * @return string
	 */
	private function render_footer() {
		// wp_head()から呼ばれる初期化で、非推奨の互換フックを解除する.
		wp_enqueue_block_template_skip_link();
		ob_start();
		load_template( get_theme_file_path( 'footer.php' ), false );

		return ob_get_clean();
	}

	/**
	 * サブフッター 初期値テスト
	 */
	function test_sub_footer_css_var() {
		$footer   = new \ystandard\Footer();
		$expected = [];
		$this->assertSame( $expected, $footer->add_css_var_footer_sub( [] ) );
	}

	/**
	 * サブフッター 背景設定テスト
	 */
	function test_sub_footer_css_var_background() {
		update_option( 'ys_color_sub_footer_bg', '#dddddd' );
		$footer   = new \ystandard\Footer();
		$expected = [
			'sub-footer--background' => '#dddddd',
		];
		$this->assertSame( $expected, $footer->add_css_var_footer_sub( [] ) );
	}

	/**
	 * サブフッター 文字色設定テスト
	 */
	function test_sub_footer_css_var_text_color() {
		update_option( 'ys_color_sub_footer_text', '#000000' );
		$footer   = new \ystandard\Footer();
		$expected = [
			'sub-footer--text-color' => '#000000',
		];
		$this->assertSame( $expected, $footer->add_css_var_footer_sub( [] ) );
	}

	/**
	 * サブフッター 文字色・背景色設定テスト
	 */
	function test_sub_footer_css_var_text_and_background_color() {
		update_option( 'ys_color_sub_footer_bg', '#dddddd' );
		update_option( 'ys_color_sub_footer_text', '#000000' );
		$footer   = new \ystandard\Footer();
		$expected = [
			'sub-footer--background' => '#dddddd',
			'sub-footer--text-color' => '#000000',
		];
		$this->assertSame( $expected, $footer->add_css_var_footer_sub( [] ) );
	}

	/**
	 * サブフッター 余白設定テスト
	 */
	function test_sub_footer_css_var_padding_empty() {
		update_option( 'ys_sub_footer_padding', '' );
		$footer   = new \ystandard\Footer();
		$expected = [];
		$this->assertSame( $expected, $footer->add_css_var_footer_sub( [] ) );
	}

	/**
	 * サブフッター 余白設定テスト
	 */
	function test_sub_footer_css_var_padding_zero() {
		update_option( 'ys_sub_footer_padding', '0' );
		$footer   = new \ystandard\Footer();
		$expected = [
			'sub-footer--padding' => 0,
		];
		$this->assertSame( $expected, $footer->add_css_var_footer_sub( [] ) );
	}

	/**
	 * サブフッター 余白設定テスト
	 */
	function test_sub_footer_css_var_padding_30() {
		update_option( 'ys_sub_footer_padding', '30' );
		$footer   = new \ystandard\Footer();
		$expected = [
			'sub-footer--padding' => '30px',
		];
		$this->assertSame( $expected, $footer->add_css_var_footer_sub( [] ) );
	}

}
