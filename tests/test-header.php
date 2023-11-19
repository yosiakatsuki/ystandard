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

	function test_header_height_css_all() {
		update_option( 'ys_header_fixed_height_pc', 10 );
		update_option( 'ys_header_fixed_height_tablet', 20 );
		update_option( 'ys_header_fixed_height_mobile', 30 );
		$expected = <<<EOD
.site-header {
	height:var(--ys-site-header-height,auto);
}
@media (max-width: 599px) {
	:root {
		--ys-site-header-height:30px;
	}
}
@media (min-width: 600px) {
	:root {
		--ys-site-header-height:20px;
	}
}
@media (min-width: 769px) {
	:root {
		--ys-site-header-height:10px;
	}
}
EOD;
		$actual   = \ystandard\Header::get_header_height_css();
		$this->assertSame(
			utils\Text::remove_nl_tab_space( $expected ),
			utils\Text::remove_nl_tab_space( $actual )
		);
	}

	function test_header_height_css_empty() {
		update_option( 'ys_header_fixed_height_pc', 0 );
		update_option( 'ys_header_fixed_height_tablet', 0 );
		update_option( 'ys_header_fixed_height_mobile', 0 );
		$expected = '';
		$actual   = \ystandard\Header::get_header_height_css();
		$this->assertSame(
			utils\Text::remove_nl_tab_space( $expected ),
			utils\Text::remove_nl_tab_space( $actual )
		);
	}
	function test_header_height_css_pc() {
		update_option( 'ys_header_fixed_height_pc', 10 );
		update_option( 'ys_header_fixed_height_tablet', 0 );
		update_option( 'ys_header_fixed_height_mobile', 0 );
		$expected = <<<EOD
.site-header {
	height:var(--ys-site-header-height,auto);
}
@media (min-width: 769px) {
	:root {
		--ys-site-header-height:10px;
	}
}
EOD;
		$actual   = \ystandard\Header::get_header_height_css();
		$this->assertSame(
			utils\Text::remove_nl_tab_space( $expected ),
			utils\Text::remove_nl_tab_space( $actual )
		);
	}
	function test_header_height_css_tablet() {
		update_option( 'ys_header_fixed_height_pc', 0 );
		update_option( 'ys_header_fixed_height_tablet', 20 );
		update_option( 'ys_header_fixed_height_mobile', 0 );
		$expected = <<<EOD
.site-header {
	height:var(--ys-site-header-height,auto);
}
@media (min-width: 600px) {
	:root {
		--ys-site-header-height:20px;
	}
}
EOD;
		$actual   = \ystandard\Header::get_header_height_css();
		$this->assertSame(
			utils\Text::remove_nl_tab_space( $expected ),
			utils\Text::remove_nl_tab_space( $actual )
		);
	}
	function test_header_height_css_mobile() {
		update_option( 'ys_header_fixed_height_pc', 0 );
		update_option( 'ys_header_fixed_height_tablet', 0 );
		update_option( 'ys_header_fixed_height_mobile', 30 );
		$expected = <<<EOD
.site-header {
	height:var(--ys-site-header-height,auto);
}
@media (max-width: 599px) {
	:root {
		--ys-site-header-height:30px;
	}
}
EOD;
		$actual   = \ystandard\Header::get_header_height_css();
		$this->assertSame(
			utils\Text::remove_nl_tab_space( $expected ),
			utils\Text::remove_nl_tab_space( $actual )
		);
	}
}
