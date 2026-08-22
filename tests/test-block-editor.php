<?php
/**
 * Class BlockEditorTest
 *
 * @package ystandard
 */

/**
 * Class BlockEditorTest
 */
class BlockEditorTest extends WP_UnitTestCase {

	/**
	 * コンストラクタのフック登録を行わずテスト対象を生成する
	 *
	 * @return \ystandard\Block_Editor
	 */
	private function get_block_editor() {
		$reflection = new ReflectionClass( \ystandard\Block_Editor::class );

		return $reflection->newInstanceWithoutConstructor();
	}

	/**
	 * Test: サイトエディター向けコアブロックを除外する
	 */
	function test_disallow_fse_blocks_by_default() {
		$block_editor = $this->get_block_editor();
		$actual       = $block_editor->disallow_fse_blocks( [ 'example/block' ] );

		$this->assertContains( 'example/block', $actual );
		$this->assertContains( 'core/query', $actual );
		$this->assertContains( 'core/navigation', $actual );
	}

	/**
	 * Test: 上級者向け設定でサイトエディター向けコアブロックを有効にする
	 */
	function test_enable_fse_blocks_by_option() {
		update_option( 'ys_enable_fse_block_types', 1 );

		$block_editor = $this->get_block_editor();
		$expected     = [ 'example/block' ];

		$this->assertSame( $expected, $block_editor->disallow_fse_blocks( $expected ) );
	}

	/**
	 * Test: 既存フィルターでサイトエディター向けコアブロックを有効にする
	 */
	function test_enable_fse_blocks_by_filter() {
		add_filter( 'ys_enable_fse_block_types', '__return_true' );

		$block_editor = $this->get_block_editor();
		$expected     = [ 'example/block' ];

		$this->assertSame( $expected, $block_editor->disallow_fse_blocks( $expected ) );

		remove_filter( 'ys_enable_fse_block_types', '__return_true' );
	}
}
