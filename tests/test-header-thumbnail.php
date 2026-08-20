<?php
/**
 * Class Test_Header_Thumbnail
 *
 * @package ystandard
 */

/**
 * Class HeaderThumbnailTest
 */
class Header_Thumbnail_Test extends WP_UnitTestCase {

	private function remove_nl_tab( $text ) {

		return \ystandard\utils\Text::remove_tab( \ystandard\utils\Text::remove_nl( $text ) );
	}

	private function create_test_image( $file, $post_id ) {

		return $this->factory->attachment->create_upload_object(
			$file,
			$post_id
		);
	}

	function test_home_thumbnail() {
		$archive = new \ystandard\Archive();
		ob_start();
		$archive->home_post_thumbnail();
		$this->assertSame( '', ob_get_clean() );

		$post_id = $this->factory->post->create(
			[ 'post_type' => 'page', ]
		);
		update_option( 'show_on_front', 'page' );
		update_option( 'page_for_posts', $post_id );
		$this->go_to( get_permalink( $post_id ) );

		ob_start();
		$archive->home_post_thumbnail();
		$this->assertSame( '', ob_get_clean() );

		$attachment_id = $this->create_test_image(
			DIR_TEST_DATA . '/images/test.png',
			$post_id
		);
		set_post_thumbnail( $post_id, $attachment_id );

		wp_high_priority_element_flag( false );

		$expected = get_the_post_thumbnail(
			$post_id,
			'post-thumbnail',
			[
				'id'            => 'site-header-thumbnail__image',
				'class'         => 'site-header-thumbnail__image',
				'alt'           => get_the_title( $post_id ),
				'loading'       => 'eager',
			]
		);

		ob_start();
		$archive->home_post_thumbnail();
		$this->assertSame(
			"<figure class=\"site-header-thumbnail\">{$expected}</figure>",
			$this->remove_nl_tab( ob_get_clean() )
		);
	}
}

/**
 * Class Page_Header_Thumbnail_Test
 */
class Page_Header_Thumbnail_Test extends WP_UnitTestCase {
	/**
	 * テスト用ウィジェットID.
	 */
	private const SIDEBAR_WIDGET_ID = 'header-thumbnail-test-widget';

	/**
	 * テスト終了処理.
	 */
	public function tear_down() {
		foreach ( [
			'ys_page_layout',
			'ys_show_page_header_thumbnail',
			'ys_page_post_thumbnail_type',
			'show_on_front',
			'page_on_front',
		] as $option_name ) {
			delete_option( $option_name );
		}
		remove_filter( 'ys_is_active_post_header_page', '__return_false' );
		wp_unregister_sidebar_widget( self::SIDEBAR_WIDGET_ID );
		wp_set_sidebars_widgets( [] );
		parent::tear_down();
	}

	/**
	 * アイキャッチ画像付きの固定ページを作成する.
	 *
	 * @return int
	 */
	private function create_page_with_thumbnail(): int {
		$post_id       = $this->factory->post->create(
			[
				'post_type' => 'page',
				'post_title' => '固定ページ',
			]
		);
		$attachment_id = $this->factory->attachment->create_upload_object(
			DIR_TEST_DATA . '/images/test.png',
			$post_id
		);
		set_post_thumbnail( $post_id, $attachment_id );
		wp_high_priority_element_flag( false );

		return $post_id;
	}

	/**
	 * 固定ページのアイキャッチ設定を更新する.
	 *
	 * @param string $type 表示タイプ.
	 * @param int    $show 表示状態.
	 */
	private function set_page_thumbnail_options( string $type = 'full', int $show = 1 ): void {
		update_option( 'ys_page_layout', '2col' );
		update_option( 'ys_show_page_header_thumbnail', $show );
		update_option( 'ys_page_post_thumbnail_type', $type );
	}

	/**
	 * サイドバーを有効化する.
	 */
	private function activate_sidebar(): void {
		wp_register_sidebar_widget(
			self::SIDEBAR_WIDGET_ID,
			'Header Thumbnail Test Widget',
			'__return_empty_string'
		);
		$sidebars_widgets                   = wp_get_sidebars_widgets();
		$sidebars_widgets['sidebar-widget'] = [ self::SIDEBAR_WIDGET_ID ];
		wp_set_sidebars_widgets( $sidebars_widgets );
	}

	/**
	 * 全幅アイキャッチの出力を取得する.
	 *
	 * @return string
	 */
	private function get_header_thumbnail_output(): string {
		ob_start();
		\ystandard\Post_Singular_Thumbnail::get_instance()->header_post_thumbnail();

		return ob_get_clean();
	}

	/**
	 * 2カラム固定ページで全幅アイキャッチを表示できることを確認する.
	 */
	public function test_page_two_column_full_header_thumbnail() {
		$post_id = $this->create_page_with_thumbnail();
		$this->set_page_thumbnail_options();
		$this->activate_sidebar();
		$this->go_to( get_permalink( $post_id ) );

		$this->assertContains( 'has-sidebar', get_body_class() );
		$this->assertStringContainsString( '<figure class="site-header-thumbnail">', $this->get_header_thumbnail_output() );
	}

	/**
	 * 本文ヘッダーを非表示にしても全幅アイキャッチを表示できることを確認する.
	 */
	public function test_full_header_thumbnail_is_independent_from_post_header() {
		$post_id = $this->create_page_with_thumbnail();
		$this->set_page_thumbnail_options();
		$this->activate_sidebar();
		add_filter( 'ys_is_active_post_header_page', '__return_false' );
		$this->go_to( get_permalink( $post_id ) );

		$this->assertFalse( \ystandard\Post_Header::is_active_post_header() );
		$this->assertStringContainsString( '<figure class="site-header-thumbnail">', $this->get_header_thumbnail_output() );
	}

	/**
	 * 本文ヘッダーを非表示にした通常タイプは表示されないことを確認する.
	 */
	public function test_default_thumbnail_stays_hidden_with_post_header() {
		$post_id = $this->create_page_with_thumbnail();
		$this->set_page_thumbnail_options( 'default' );
		add_filter( 'ys_is_active_post_header_page', '__return_false' );
		$this->go_to( get_permalink( $post_id ) );

		ob_start();
		\ystandard\Post_Singular_Thumbnail::post_thumbnail_default();
		$this->assertSame( '', ob_get_clean() );
		$this->assertSame( '', $this->get_header_thumbnail_output() );
	}

	/**
	 * アイキャッチ表示設定が無効な場合は全幅画像を表示しないことを確認する.
	 */
	public function test_full_header_thumbnail_respects_display_option() {
		$post_id = $this->create_page_with_thumbnail();
		$this->set_page_thumbnail_options( 'full', 0 );
		$this->go_to( get_permalink( $post_id ) );

		$this->assertSame( '', $this->get_header_thumbnail_output() );
	}

	/**
	 * 投稿単位のONでテーマ設定が非表示のアイキャッチを表示できることを確認する.
	 */
	public function test_post_setting_can_enable_header_thumbnail() {
		$post_id = $this->create_page_with_thumbnail();
		$this->set_page_thumbnail_options( 'full', 0 );
		update_post_meta(
			$post_id,
			\ystandard\Post_Meta::META_KEY,
			[
				'version'        => 1,
				'post_thumbnail' => 'on',
			]
		);
		$this->go_to( get_permalink( $post_id ) );

		$this->assertStringContainsString( '<figure class="site-header-thumbnail">', $this->get_header_thumbnail_output() );
	}

	/**
	 * 投稿単位のOFFでテーマ設定が表示のアイキャッチを非表示にできることを確認する.
	 */
	public function test_post_setting_can_disable_header_thumbnail() {
		$post_id = $this->create_page_with_thumbnail();
		$this->set_page_thumbnail_options();
		update_post_meta(
			$post_id,
			\ystandard\Post_Meta::META_KEY,
			[
				'version'        => 1,
				'post_thumbnail' => 'off',
			]
		);
		$this->go_to( get_permalink( $post_id ) );

		$this->assertSame( '', $this->get_header_thumbnail_output() );
	}

	/**
	 * アイキャッチ未設定の場合は全幅画像を表示しないことを確認する.
	 */
	public function test_full_header_thumbnail_requires_post_thumbnail() {
		$post_id = $this->factory->post->create( [ 'post_type' => 'page' ] );
		$this->set_page_thumbnail_options();
		$this->go_to( get_permalink( $post_id ) );

		$this->assertSame( '', $this->get_header_thumbnail_output() );
	}

	/**
	 * 固定フロントページでは全幅アイキャッチを表示しないことを確認する.
	 */
	public function test_full_header_thumbnail_stays_hidden_on_front_page() {
		$post_id = $this->create_page_with_thumbnail();
		$this->set_page_thumbnail_options();
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $post_id );
		$this->go_to( home_url( '/' ) );

		$this->assertSame( '', $this->get_header_thumbnail_output() );
	}

	/**
	 * 投稿ヘッダーなしテンプレートでは全幅アイキャッチを表示しないことを確認する.
	 */
	public function test_full_header_thumbnail_stays_hidden_on_blank_template() {
		$post_id = $this->create_page_with_thumbnail();
		$this->set_page_thumbnail_options();
		update_post_meta( $post_id, '_wp_page_template', 'page-template/template-blank.php' );
		$this->go_to( get_permalink( $post_id ) );

		$this->assertSame( '', $this->get_header_thumbnail_output() );
	}
}
