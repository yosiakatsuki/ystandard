<?php
/**
 * Class SampleTest
 *
 * @package ystandard
 */

/**
 * Sample test case.
 */
class SampleTest extends WP_UnitTestCase {

	/**
	 * A single example test.
	 */
	function test_sample() {
		// Replace this with some actual testing code.
		$this->assertTrue( true );
	}
	/**
	 * @test 
	 * A single example test2.
	 */
	function sample2() {
		// Replace this with some actual testing code.
		$this->assertTrue( true );
	}
	/**
	 * 投稿追加
	 */
	function test_post_add() {
		/**
		 * 投稿を追加して、追加した投稿IDを取得
		 */
		$post_id = $this->factory->post->create();
		/**
		 * 追加した投稿の更新
		 */
		wp_update_post( array( 'ID' => $post_id, 'post_title' => '投稿が追加出来ました' ) );
		/**
		 * 検証
		 */
		$this->assertEquals( '投稿が追加出来ました', get_the_title( $post_id ) );
	}
	/**
	 * the_contentの出力結果を確認
	 */
	function test_post_content() {
		global $post;
		$post_id = $this->factory->post->create( array( 'post_content' => 'コンテンツ' ) );
		$post = get_post( $post_id );
		setup_postdata( $post );
		/**
		 * 検証
		 */
		$content2 = '<p>コンテンツ</p>' . PHP_EOL;
		$this->expectOutputString( $content2 );
		the_content();
	}
}
