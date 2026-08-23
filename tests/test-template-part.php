<?php
/**
 * テンプレートパーツのテスト
 *
 * @package ystandard
 */

/**
 * Class TemplatePartTest
 */
class TemplatePartTest extends WP_UnitTestCase {

	/**
	 * 名前付きテンプレートへ引数を渡せることを確認
	 */
	public function test_get_template_part_loads_named_template_with_args() {
		ob_start();
		get_template_part(
			'tests/data/template-part/sample',
			'named',
			[ 'message' => 'named template' ]
		);
		$output = ob_get_clean();

		$this->assertSame( 'named template', $output );
	}

	/**
	 * 名前付きテンプレートがない場合に汎用テンプレートを読み込むことを確認
	 */
	public function test_get_template_part_falls_back_to_generic_template() {
		ob_start();
		get_template_part( 'tests/data/template-part/sample', 'missing' );
		$output = ob_get_clean();

		$this->assertSame( 'generic', $output );
	}

	/**
	 * テンプレートがない場合に出力せず失敗を返すことを確認
	 */
	public function test_get_template_part_returns_false_when_template_is_missing() {
		ob_start();
		$result = get_template_part( 'tests/data/template-part/missing' );
		$output = ob_get_clean();

		$this->assertFalse( $result );
		$this->assertSame( '', $output );
	}

	/**
	 * 廃止した独自APIが読み込まれていないことを確認
	 */
	public function test_legacy_template_part_api_is_removed() {
		$this->assertFalse( function_exists( 'ys_get_template_part' ) );
		$this->assertFalse( class_exists( '\\ystandard\\Template' ) );
	}

	/**
	 * 意味別ディレクトリへ移動したテンプレートが存在することを確認
	 */
	public function test_reorganized_template_parts_exist() {
		$template_parts = [
			'template-parts/author/author-box.php',
			'template-parts/blog-card/blog-card.php',
			'template-parts/google-analytics/gtag.php',
			'template-parts/header/header-thumbnail.php',
			'template-parts/info-bar/info-bar.php',
			'template-parts/archive/pagination.php',
			'template-parts/post/post-date.php',
			'template-parts/post/post-paging.php',
			'template-parts/post/post-taxonomy.php',
			'template-parts/post/post-thumbnail.php',
			'template-parts/post/post-title.php',
			'template-parts/recent-posts/recent-posts.php',
			'template-parts/recent-posts/recent-posts-simple.php',
		];

		foreach ( $template_parts as $template_part ) {
			$this->assertFileExists( get_theme_file_path( $template_part ) );
		}
	}

	/**
	 * 旧partsディレクトリのテンプレートが残っていないことを確認
	 */
	public function test_legacy_parts_directory_has_no_templates() {
		$this->assertSame( [], glob( get_theme_file_path( 'template-parts/parts/*.php' ) ) );
	}

	/**
	 * 最近の投稿で投稿タイプ別候補とテンプレート引数を維持することを確認
	 */
	public function test_recent_posts_preserves_named_template_and_args() {
		self::factory()->post->create( [ 'post_title' => '最近の投稿テスト' ] );
		$captured = [];
		$callback = function ( $slug, $name, $args ) use ( &$captured ) {
			$captured = compact( 'slug', 'name', 'args' );
		};
		add_action( 'get_template_part_template-parts/recent-posts/recent-posts', $callback, 10, 3 );

		$recent_posts = new \ystandard\Recent_Posts();
		$output       = $recent_posts->do_shortcode(
			[
				'post_type' => 'post',
				'count'     => 1,
				'cache'     => 'template_parts_test',
			]
		);

		remove_action( 'get_template_part_template-parts/recent-posts/recent-posts', $callback, 10 );
		$this->assertSame( 'template-parts/recent-posts/recent-posts', $captured['slug'] );
		$this->assertSame( 'post', $captured['name'] );
		$this->assertArrayHasKey( 'recent_posts', $captured['args'] );
		$this->assertArrayHasKey( 'posts_query', $captured['args'] );
		$this->assertStringContainsString( 'class="ys-posts is-list"', $output );
		$this->assertStringContainsString( '最近の投稿テスト', $output );
	}
}
