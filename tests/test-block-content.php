<?php
/**
 * 共通ブロックコンテンツ領域のテスト
 *
 * @package ystandard
 */

/**
 * Class BlockContentTest
 */
class BlockContentTest extends WP_UnitTestCase {

	/**
	 * 投稿・固定ページが本文の識別用クラスとブロック共通クラスを持つことを確認
	 *
	 * @dataProvider content_template_provider
	 *
	 * @param string $post_type 投稿タイプ.
	 * @param string $template  テンプレートのスラグ.
	 */
	public function test_content_templates_use_block_content( $post_type, $template ) {
		$post_id = self::factory()->post->create(
			[
				'post_type'    => $post_type,
				'post_status'  => 'publish',
				'post_content' => '<!-- wp:paragraph --><p>Block content marker</p><!-- /wp:paragraph -->',
			]
		);
		$this->go_to( get_permalink( $post_id ) );
		the_post();

		ob_start();
		get_template_part( $template );
		$output = ob_get_clean();

		$this->assertStringContainsString( 'class="entry-content block-content"', $output );
		$this->assertStringContainsString( '<p>Block content marker</p>', $output );
	}

	/**
	 * 本文テンプレートの組み合わせ
	 *
	 * @return array
	 */
	public function content_template_provider() {
		return [
			'post' => [ 'post', 'template-parts/single/content' ],
			'page' => [ 'page', 'template-parts/page/content' ],
		];
	}

	/**
	 * ブロックを含むパーツが既定で共通クラスを持つことを確認
	 */
	public function test_block_parts_get_shared_content_wrapper() {
		$parts_id = $this->create_parts( '<!-- wp:paragraph --><p>Block parts content</p><!-- /wp:paragraph -->' );
		$output   = \ystandard\Parts::do_shortcode( [ 'parts_id' => $parts_id ] );

		$this->assertStringStartsWith( '<div class="block-content">', $output );
		$this->assertStringContainsString( '<p>Block parts content</p>', $output );
	}

	/**
	 * 従来の本文スタイル指定でも共通クラスだけを出力することを確認
	 */
	public function test_parts_keep_legacy_content_style_option() {
		$parts_id = $this->create_parts( 'Legacy parts content' );
		$output   = \ystandard\Parts::do_shortcode(
			[
				'parts_id'          => $parts_id,
				'use_entry_content' => true,
			]
		);

		$this->assertSame( '<div class="block-content">Legacy parts content</div>', $output );
	}

	/**
	 * ブロックのないパーツには既定でラッパーを追加しないことを確認
	 */
	public function test_plain_parts_preserve_inline_output() {
		$parts_id = $this->create_parts( 'Inline parts content' );

		$this->assertSame( 'Inline parts content', \ystandard\Parts::do_shortcode( [ 'parts_id' => $parts_id ] ) );
	}

	/**
	 * ブロックウィジェットが既存クラスを保ちながら共通クラスを持つことを確認
	 */
	public function test_block_widgets_get_shared_content_class() {
		$widget = new WP_Widget_Block();
		ob_start();
		$widget->widget(
			[
				'before_widget' => '<div class="widget widget_block">',
				'after_widget'  => '</div>',
			],
			[ 'content' => '<!-- wp:paragraph --><p>Block widget content</p><!-- /wp:paragraph -->' ]
		);
		$output    = ob_get_clean();
		$processor = new WP_HTML_Tag_Processor( $output );

		$this->assertTrue( $processor->next_tag( [ 'class_name' => 'widget_block' ] ) );
		$this->assertTrue( $processor->has_class( 'block-content' ) );
		$this->assertTrue( $processor->has_class( 'widget_text' ) );
		$this->assertStringContainsString( '<p>Block widget content</p>', $output );
	}

	/**
	 * テスト用の公開パーツを作成
	 *
	 * @param string $content パーツ本文.
	 *
	 * @return int
	 */
	private function create_parts( $content ) {
		return self::factory()->post->create(
			[
				'post_type'    => \ystandard\Parts::POST_TYPE,
				'post_status'  => 'publish',
				'post_content' => $content,
			]
		);
	}
}
