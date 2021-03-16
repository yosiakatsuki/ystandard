<?php
/**
 * Class Test_Header
 *
 * @package ystandard
 */

/**
 * Class Test_Header
 */
class HeaderTest extends WP_UnitTestCase {

	/**
	 * ブログ概要テスト
	 */
	function test_get_blog_description() {
		update_option( 'blogdescription', 'Blog Description!!!' );
		$post_id = $this->factory->post->create();
		$this->go_to( home_url( '/' ) );
		ob_start();
		ys_the_blog_description();
		$description = ob_get_clean();
		$this->assertSame(
			'<p class="site-description">Blog Description!!!</p>',
			$description
		);
	}

	/**
	 * ブログ概要 空
	 */
	function test_get_blog_description_none() {
		update_option( 'blogdescription', '' );
		$post_id = $this->factory->post->create();
		$this->go_to( home_url( '/' ) );
		ob_start();
		ys_the_blog_description();
		$description = ob_get_clean();
		$this->assertEmpty( $description );
	}

	/**
	 * ブログ概要 隠す
	 */
	function test_get_blog_description_hide() {
		update_option( 'blogdescription', 'Blog Description!!!' );
		update_option( 'ys_wp_hidden_blogdescription', true );
		$post_id = $this->factory->post->create();
		$this->go_to( home_url( '/' ) );
		ob_start();
		ys_the_blog_description();
		$description = ob_get_clean();
		$this->assertEmpty( $description );
	}
}
