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
	 * 初期フッターが幅広グループと余白プリセット・サイトロゴで構成されることを確認
	 */
	public function test_footer_template_part_replaces_widget_areas() {
		$this->assertTrue( current_theme_supports( 'block-template-parts' ) );
		$this->assertFalse( wp_is_block_theme() );
		$template_part = get_block_template( get_stylesheet() . '//footer', 'wp_template_part' );

		$this->assertNotNull( $template_part );
		$this->assertSame( 'footer', $template_part->area );
		$blocks = parse_blocks( $template_part->content );
		$group  = $blocks[0];
		$this->assertSame( 'core/group', $group['blockName'] );
		$this->assertSame( 'wide', $group['attrs']['align'] );
		$this->assertSame(
			[
				'top'    => 'var:preset|spacing|ys-static-40',
				'bottom' => 'var:preset|spacing|ys-static-40',
			],
			$group['attrs']['style']['spacing']['padding']
		);
		$this->assertCount( 1, $group['innerBlocks'] );
		$inner_group = $group['innerBlocks'][0];
		$this->assertSame( 'core/group', $inner_group['blockName'] );
		$this->assertSame( 'wide', $inner_group['attrs']['align'] );
		$this->assertCount( 1, $inner_group['innerBlocks'] );
		$this->assertSame( 'core/site-logo', $inner_group['innerBlocks'][0]['blockName'] );
		$this->assertTrue( ys_is_active_footer_template_part() );

		foreach ( [ 'footer-left', 'footer-center', 'footer-right' ] as $sidebar ) {
			$this->assertArrayNotHasKey( $sidebar, $GLOBALS['wp_registered_sidebars'] );
		}
	}

	/**
	 * 初期設定で保存済みパーツを表示し、旧ラッパーや未設定のメニューを出力しないことを確認
	 */
	public function test_footer_renders_saved_template_part_without_menu() {
		$this->create_footer_template_part();

		$this->assertTrue( ys_is_active_footer_template_part() );
		$output = $this->render_footer();

		$this->assertStringContainsString( '<p>Footer block content</p>', $output );
		$this->assertStringContainsString( 'class="site-footer-contents block-content"', $output );
		$this->assertStringContainsString( 'class="copyright-wrapper"', $output );
		$this->assertStringNotContainsString( 'class="footer-main"', $output );
		$this->assertStringNotContainsString( 'class="footer-container"', $output );
		$this->assertStringNotContainsString( 'class="footer-widget"', $output );
		$this->assertStringNotContainsString( 'class="sub-footer"', $output );
		$this->assertStringNotContainsString( 'class="footer-nav"', $output );
	}

	/**
	 * 空のパーツがあってもフッターメニューを独立したコンテナで表示することを確認
	 */
	public function test_footer_renders_navigation_without_empty_template_part() {
		$this->create_footer_template_part( '' );
		$this->create_footer_menu();

		$this->assertFalse( ys_is_active_footer_template_part() );
		$output = $this->render_footer();

		$this->assertStringContainsString( 'class="footer-nav"', $output );
		$this->assertStringContainsString( 'class="footer-nav__container"', $output );
		$this->assertStringContainsString( 'Footer menu link', $output );
		$this->assertStringNotContainsString( 'class="footer-main"', $output );
	}

	/**
	 * パーツだけを非表示にしてもメニューとコピーライトは残り、再表示できることを確認
	 */
	public function test_footer_display_setting_only_controls_template_part() {
		$this->create_footer_template_part();
		$this->create_footer_menu();
		update_option( 'ys_footer_template_part_display', 'hide' );

		$this->assertFalse( ys_is_active_footer_template_part() );
		$output = $this->render_footer();

		$this->assertStringNotContainsString( 'Footer block content', $output );
		$this->assertStringContainsString( 'Footer menu link', $output );
		$this->assertStringContainsString( 'class="copyright-wrapper"', $output );

		update_option( 'ys_footer_template_part_display', 'show' );
		$this->assertTrue( ys_is_active_footer_template_part() );
		$this->assertStringContainsString( 'Footer block content', $this->render_footer() );
	}

	/**
	 * フッター内の呼び出し順を保ち、廃止したフックを実行しないことを確認
	 */
	public function test_footer_preserves_part_order_and_hooks() {
		$order     = [];
		$callbacks = [];
		foreach ( [ 'ys_site_footer_prepend', 'ys_site_footer_append', 'ys_before_footer_main' ] as $hook ) {
			$callbacks[ $hook ] = static function () use ( &$order, $hook ) {
				$order[] = $hook;
			};
			add_action( $hook, $callbacks[ $hook ] );
		}
		$template_callback = static function ( $slug ) use ( &$order ) {
			$order[] = $slug;
		};
		add_action( 'get_template_part', $template_callback );

		$this->render_footer();

		foreach ( $callbacks as $hook => $callback ) {
			remove_action( $hook, $callback );
		}
		remove_action( 'get_template_part', $template_callback );

		$this->assertSame(
			[
				'ys_site_footer_prepend',
				'template-parts/footer/footer-content',
				'template-parts/footer/footer-nav',
				'template-parts/copyright/copyright',
				'ys_site_footer_append',
			],
			array_slice( $order, 0, 5 )
		);
		$this->assertNotContains( 'ys_before_footer_main', $order );
	}

	/**
	 * 横並び選択で初期値・保存値を扱い、不要なフッター設定を登録しないことを確認
	 */
	public function test_footer_settings_use_toggle_group_and_remove_legacy_settings() {
		$wp_customize = new WP_Customize_Manager();
		// 管理画面では遅延読み込みされる専用ラベルをテストでも利用する.
		if ( ! class_exists( \ystandard\Section_Label_Control::class ) ) {
			require get_template_directory() . '/inc/customizer/class-section-label-control.php';
		}
		// 既存のReact製横並びコントロールを使うことを確認するために読み込む.
		if ( ! class_exists( \ystandard\Toggle_Group_Control::class ) ) {
			require get_template_directory() . '/inc/customizer/class-toggle-group-control.php';
		}
		$footer = ( new ReflectionClass( \ystandard\Footer::class ) )->newInstanceWithoutConstructor();
		$footer->customize_register( $wp_customize );

		$setting = $wp_customize->get_setting( 'ys_footer_template_part_display' );
		$control = $wp_customize->get_control( 'ys_footer_template_part_display' );
		$this->assertSame( 'show', $setting->default );
		$this->assertSame( 'option', $setting->type );
		$this->assertInstanceOf( \ystandard\Toggle_Group_Control::class, $control );
		$this->assertSame( [ 'show', 'hide' ], array_keys( $control->choices ) );
		$this->assertSame( 'hide', $setting->sanitize( 'hide' ) );
		$this->assertSame( 'show', $setting->sanitize( 'invalid' ) );

		foreach ( [
			'ys_color_footer_font',
			'ys_color_footer_text_gray',
			'ys_color_footer_bg',
			'ys_footer_main_padding_top',
			'ys_footer_main_padding_bottom',
			'ys_footer_main_content_gap',
			'ys_footer_widget_column_gap',
			'ys_color_sub_footer_bg',
			'ys_color_sub_footer_text',
			'ys_footer_sub_content',
			'ys_sub_footer_padding',
		] as $setting_id ) {
			$this->assertNull( $wp_customize->get_setting( $setting_id ), $setting_id );
			$this->assertNull( $wp_customize->get_control( $setting_id ), $setting_id );
		}
		foreach ( [ 'ys_footer_nav_font_size', 'ys_footer_nav_gap_x', 'ys_footer_nav_gap_y', 'ys_back_to_top_active' ] as $setting_id ) {
			$this->assertInstanceOf( WP_Customize_Setting::class, $wp_customize->get_setting( $setting_id ) );
		}
	}

	/**
	 * 残したナビゲーション設定が実際のスタイルと同じCSS変数へ反映されることを確認
	 */
	public function test_footer_navigation_settings_match_css_properties() {
		update_option( 'ys_footer_nav_font_size', 14 );
		update_option( 'ys_footer_nav_gap_x', 30 );
		update_option( 'ys_footer_nav_gap_y', 20 );
		$footer = ( new ReflectionClass( \ystandard\Footer::class ) )->newInstanceWithoutConstructor();

		$this->assertSame(
			[
				'footer--nav--font-size'   => '14px',
				'footer--nav--gap--column' => '30px',
				'footer--nav--gap--row'    => '20px',
			],
			$footer->add_css_var_footer_nav( [] )
		);
	}

	/**
	 * 保存済みフッターパーツを作成
	 *
	 * @param string $content パーツのブロックマークアップ.
	 */
	private function create_footer_template_part( $content = '<!-- wp:paragraph --><p>Footer block content</p><!-- /wp:paragraph -->' ) {
		$part_id = self::factory()->post->create(
			[
				'post_type'    => 'wp_template_part',
				'post_status'  => 'publish',
				'post_name'    => 'footer',
				'post_content' => $content,
			]
		);
		wp_set_object_terms( $part_id, get_stylesheet(), 'wp_theme' );
		wp_set_object_terms( $part_id, 'footer', 'wp_template_part_area' );
	}

	/**
	 * フッターに表示するメニューを作成
	 */
	private function create_footer_menu() {
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
}
