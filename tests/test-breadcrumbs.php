<?php
/**
 * Class Breadcrumbs Test
 *
 * @package ystandard
 */

use ystandard\Breadcrumbs;
use ystandard\Breadcrumbs_Data;
use ystandard\Breadcrumbs_Option;
use ystandard\Enqueue_Styles;

require_once ABSPATH . WPINC . '/class-wp-customize-manager.php';

/**
 * Class Breadcrumbs Test
 */
class BreadcrumbsTest extends WP_UnitTestCase {

	/**
	 * Test: is_show_breadcrumbs
	 */
	function test_is_show_breadcrumbs_header() {
		$post_id = $this->factory->post->create();
		$this->go_to( get_permalink( $post_id ) );

		update_option( 'ys_breadcrumbs_position', 'header' );

		$this->assertTrue( Breadcrumbs_Data::is_show_breadcrumbs() );
	}

	/**
	 * Test: is_show_breadcrumbs
	 */
	function test_is_show_breadcrumbs_footer() {
		$post_id = $this->factory->post->create();
		$this->go_to( get_permalink( $post_id ) );

		update_option( 'ys_breadcrumbs_position', 'footer' );

		$this->assertTrue( Breadcrumbs_Data::is_show_breadcrumbs() );
	}
	/**
	 * Test: is_show_breadcrumbs
	 */
	function test_is_show_breadcrumbs_front_page() {
		$post_id = $this->factory->post->create(
			[
				'post_type' => 'page',
			]
		);
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $post_id );
		$this->go_to( home_url( '/' ) );

		update_option( 'ys_breadcrumbs_position', 'footer' );

		$this->assertFalse( Breadcrumbs_Data::is_show_breadcrumbs() );
	}

	/**
	 * フッターでは投稿ヘッダー無しテンプレートにもパンくずを表示する.
	 */
	public function test_is_show_breadcrumbs_blank_template_in_footer() {
		$this->go_to_blank_template_page();
		update_option( 'ys_breadcrumbs_position', 'footer' );
		update_option( 'ys_show_breadcrumb_blank_template', 0 );

		$this->assertTrue( Breadcrumbs_Data::is_show_breadcrumbs() );
	}

	/**
	 * ヘッダーで表示する場合のみ投稿ヘッダー無しテンプレート用設定を使う.
	 */
	public function test_is_show_breadcrumbs_blank_template_in_header() {
		$this->go_to_blank_template_page();
		update_option( 'ys_breadcrumbs_position', 'header' );
		update_option( 'ys_show_breadcrumb_blank_template', 0 );
		$this->assertFalse( Breadcrumbs_Data::is_show_breadcrumbs() );

		update_option( 'ys_show_breadcrumb_blank_template', 1 );
		$this->assertTrue( Breadcrumbs_Data::is_show_breadcrumbs() );

		update_option( 'ys_breadcrumbs_position', 'none' );
		$this->assertFalse( Breadcrumbs_Data::is_show_breadcrumbs() );
	}

	/**
	 * Test: is_show_breadcrumbs
	 */
	function test_is_show_breadcrumbs_pre() {
		$this->go_to_blank_template_page();
		update_option( 'ys_breadcrumbs_position', 'header' );
		update_option( 'ys_show_breadcrumb_blank_template', 0 );

		$this->assertFalse( Breadcrumbs_Data::is_show_breadcrumbs() );

		add_filter( 'ys_pre_is_show_breadcrumbs', '__return_true' );
		$this->assertTrue( Breadcrumbs_Data::is_show_breadcrumbs() );
	}

	/**
	 * カスタマイザープレビューの表示位置に応じて見出しとチェックボックスが切り替わる.
	 */
	public function test_customizer_shows_blank_template_controls_only_for_header() {
		wp_set_current_user( self::factory()->user->create( [ 'role' => 'administrator' ] ) );
		$wp_customize = new WP_Customize_Manager();
		// テーマ読み込み時にカスタマイザーのクラスが未定義だった場合は読み込み直す.
		if ( ! class_exists( \ystandard\Section_Label_Control::class ) ) {
			require get_template_directory() . '/inc/customizer/class-section-label-control.php';
		}
		$option = ( new ReflectionClass( Breadcrumbs_Option::class ) )->newInstanceWithoutConstructor();
		$option->customize_register( $wp_customize );

		$label_id = 'ys_' . md5( __( '投稿ヘッダー無しテンプレート用設定', 'ystandard' ) . 'ys_breadcrumbs' );
		$label    = $wp_customize->get_control( $label_id );
		$checkbox = $wp_customize->get_control( 'ys_show_breadcrumb_blank_template' );
		$position = $wp_customize->get_setting( 'ys_breadcrumbs_position' );
		$position->preview();

		foreach ( [ 'footer' => false, 'header' => true, 'none' => false ] as $value => $expected ) {
			$wp_customize->set_post_value( $position->id, $value );
			$this->assertSame( $expected, $label->active() );
			$this->assertSame( $expected, $checkbox->active() );
		}

		$font_size = $wp_customize->get_control( 'ys_breadcrumbs_font_size' );
		$this->assertSame( 'text', $font_size->type );
		$this->assertSame( '14px', $font_size->input_attrs['placeholder'] );

		$padding = $wp_customize->get_control( 'ys_breadcrumbs_padding' );
		$this->assertSame( 'text', $padding->type );
		$this->assertSame( '0.5em', $padding->input_attrs['placeholder'] );
		$this->assertSame( '', $padding->setting->default );
	}

	/**
	 * デザイン設定がパンくずのCSSから参照される変数に反映される.
	 */
	public function test_breadcrumb_design_options_output_css_properties() {
		update_option( 'ys_breadcrumbs_font_size', '14' );
		update_option( 'ys_breadcrumbs_padding', '0.75em' );
		update_option( 'ys_color_breadcrumbs_background', '#eee' );
		update_option( 'ys_color_breadcrumbs_text', '#123456' );

		$css = Enqueue_Styles::get_css_custom_properties();
		$this->assertStringContainsString( '--ystd--breadcrumbs--font-size: 14px;', $css );
		$this->assertStringContainsString( '--ystd--breadcrumbs--padding: 0.75em;', $css );
		$this->assertStringContainsString( '--ystd--breadcrumbs--background: #eee;', $css );
		$this->assertStringContainsString( '--ystd--breadcrumbs--text-color: #123456;', $css );

		update_option( 'ys_breadcrumbs_font_size', '0.875rem' );
		update_option( 'ys_breadcrumbs_padding', '0' );
		$css = Enqueue_Styles::get_css_custom_properties();
		$this->assertStringContainsString( '--ystd--breadcrumbs--font-size: 0.875rem;', $css );
		$this->assertStringContainsString( '--ystd--breadcrumbs--padding: 0px;', $css );

		update_option( 'ys_breadcrumbs_font_size', '' );
		update_option( 'ys_breadcrumbs_padding', '' );
		update_option( 'ys_color_breadcrumbs_background', '' );
		$css = Enqueue_Styles::get_css_custom_properties();
		$this->assertStringNotContainsString( '--ystd--breadcrumbs--font-size:', $css );
		$this->assertStringNotContainsString( '--ystd--breadcrumbs--padding:', $css );
		$this->assertStringNotContainsString( '--ystd--breadcrumbs--background:', $css );
	}

	/**
	 * 閲覧権限の有無にかかわらず、公開されている祖先ページだけを出力する.
	 */
	public function test_non_public_page_ancestors_are_omitted() {
		$parent_id = self::factory()->post->create(
			[
				'post_type'  => 'page',
				'post_title' => 'Public ancestor',
			]
		);
		foreach ( [ [ 'post_status' => 'draft' ], [ 'post_password' => 'test-password' ], [ 'post_status' => 'private' ] ] as $visibility ) {
			$parent_id = self::factory()->post->create(
				array_merge(
					[
						'post_type'   => 'page',
						'post_title'  => 'Hidden ancestor',
						'post_parent' => $parent_id,
					],
					$visibility
				)
			);
		}
		$page_id = self::factory()->post->create(
			[
				'post_type'   => 'page',
				'post_title'  => 'Public child',
				'post_parent' => $parent_id,
			]
		);
		$this->go_to( get_permalink( $page_id ) );

		$expected = [ 'Home', 'Public ancestor', 'Public child' ];
		$this->assertSame( $expected, array_column( Breadcrumbs_Data::get_breadcrumbs(), 'name' ) );

		wp_set_current_user( self::factory()->user->create( [ 'role' => 'administrator' ] ) );
		$this->assertSame( $expected, array_column( Breadcrumbs_Data::get_breadcrumbs(), 'name' ) );

		$renderer = ( new ReflectionClass( Breadcrumbs::class ) )->newInstanceWithoutConstructor();
		ob_start();
		$renderer->show_breadcrumbs();
		$html = ob_get_clean();
		$this->assertStringContainsString( '<nav id="breadcrumbs" class="breadcrumbs" aria-label="パンくずリスト">', $html );
		$this->assertStringContainsString( 'Public ancestor', $html );
		$this->assertStringNotContainsString( 'Hidden ancestor', $html );

		ob_start();
		$renderer->structured_data();
		$json_ld = ob_get_clean();
		$this->assertStringContainsString( 'Public ancestor', $json_ld );
		$this->assertStringNotContainsString( 'Hidden ancestor', $json_ld );
	}

	/**
	 * 「投稿ページ」が非公開の間は投稿のパンくずに追加しない.
	 */
	public function test_private_posts_page_is_omitted() {
		$front_page_id = self::factory()->post->create( [ 'post_type' => 'page' ] );
		$posts_page_id = self::factory()->post->create(
			[
				'post_type'   => 'page',
				'post_status' => 'private',
				'post_title'  => 'Posts list',
			]
		);
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $front_page_id );
		update_option( 'page_for_posts', $posts_page_id );

		$post_id = self::factory()->post->create();
		$this->go_to( get_permalink( $post_id ) );
		$this->assertNotContains( get_the_title( $posts_page_id ), array_column( Breadcrumbs_Data::get_breadcrumbs(), 'name' ) );

		wp_update_post( [ 'ID' => $posts_page_id, 'post_status' => 'publish' ] );
		$this->assertContains( 'Posts list', array_column( Breadcrumbs_Data::get_breadcrumbs(), 'name' ) );
	}

	/**
	 * 未登録のタクソノミーを指定しても投稿のパンくずは表示する.
	 */
	public function test_unregistered_taxonomy_preserves_the_post_breadcrumb() {
		$post_id = self::factory()->post->create();
		$this->go_to( get_permalink( $post_id ) );
		add_filter(
			'ys_breadcrumbs_single_taxonomy',
			static function () {
				return 'ys_unregistered_taxonomy';
			}
		);

		$this->assertSame(
			[ home_url( '/' ), get_permalink( $post_id ) ],
			array_column( Breadcrumbs_Data::get_breadcrumbs(), 'item' )
		);
	}

	/**
	 * 同名カテゴリーがあっても、閲覧中のカテゴリーの階層とURLを使う.
	 */
	public function test_category_archives_use_the_queried_category() {
		$categories = [];
		foreach ( self::factory()->category->create_many( 2 ) as $parent_id ) {
			$category_id = self::factory()->category->create(
				[
					'name'   => 'Shared name',
					'slug'   => 'shared-name-' . $parent_id,
					'parent' => $parent_id,
				]
			);
			self::factory()->post->create( [ 'post_category' => [ $category_id ] ] );
			$categories[ $category_id ] = $parent_id;
		}

		foreach ( $categories as $category_id => $parent_id ) {
			$this->go_to( get_category_link( $category_id ) );
			$this->assertSame(
				[ home_url( '/' ), get_category_link( $parent_id ), get_category_link( $category_id ) ],
				array_column( Breadcrumbs_Data::get_breadcrumbs(), 'item' )
			);
		}
	}

	/**
	 * 月がゼロ埋めされているURLでも英語の月名を表示できる.
	 */
	public function test_english_month_archive_accepts_zero_padded_months() {
		add_filter(
			'locale',
			static function () {
				return 'en_US';
			}
		);
		self::factory()->post->create( [ 'post_date' => '2019-01-15 10:00:00' ] );
		$this->go_to( home_url( '/?m=201901' ) );

		$this->assertSame( [ 'Home', '2019', 'January' ], array_column( Breadcrumbs_Data::get_breadcrumbs(), 'name' ) );
	}

	/**
	 * 投稿ヘッダー無しテンプレートを使用する固定ページを開く.
	 */
	private function go_to_blank_template_page() {
		$post_id = $this->factory->post->create( [ 'post_type' => 'page' ] );
		update_post_meta( $post_id, '_wp_page_template', 'page-template/template-blank.php' );
		$this->go_to( get_permalink( $post_id ) );
	}
}
