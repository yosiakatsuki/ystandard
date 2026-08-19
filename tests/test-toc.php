<?php
/**
 * Class TocTest
 *
 * @package ystandard
 */

/**
 * Class TocTest
 */
class TocTest extends WP_UnitTestCase {

	/**
	 * テスト終了処理.
	 */
	public function tear_down() {
		foreach ( [
			'ys_create_post_toc',
			'ys_disable_toc_post_type_post',
			'ys_toc_display_type',
		] as $option_name ) {
			delete_option( $option_name );
		}
		parent::tear_down();
	}

	/**
	 * タブと改行削除
	 *
	 * @param $text
	 *
	 * @return string|string[]
	 */
	private function remove_tab_nl( $text ) {
		return str_replace(
			[
				"\r\n",
				"\r",
				"\n",
				"\t",
			],
			'',
			$text
		);
	}

	private function get_the_content( $content, $args = [] ) {
		$args    = array_merge(
			[
				'post_type'    => 'post',
				'post_content' => $content,
			],
			$args
		);
		$post_id = $this->factory->post->create( $args );
		$this->go_to( get_permalink( $post_id ) );
		the_post();

		ob_start();
		remove_filter( 'the_content', 'wpautop' );
		add_filter( 'ys_toc_matches', function ( $matches ) {
			return null;
		} );
		the_content();

		return ob_get_clean();
	}

	/**
	 * 目次テスト
	 */
	function test_heading() {
		$content = '
		<p>あああああ</p>
		<h2>みだし２</h2>
		<div class="ystdb-heading has-text-align-left is-style-ystdtb-h2">
		<div class="ystdb-heading__container">
		<h2 class="ystdb-heading__text is-clear-style">カスタム見出しああああ</h2>
		</div>
		</div>
		<h2>みだし２</h2>
		';

		$content = $this->get_the_content( $content );

		$expected = '
		<p>あああああ</p>
		<div class="ys-toc">
		<p class="ys-toc__title">目次</p>
		<ul class="ys-toc__list">
			<li class="ys-toc__item"><a class="ys-toc__link" href="#index-1">みだし２</a></li>
			<li class="ys-toc__item"><a class="ys-toc__link" href="#index-2">カスタム見出しああああ</a></li>
			<li class="ys-toc__item"><a class="ys-toc__link" href="#index-3">みだし２</a></li>
		</ul>
		</div>
		<h2 id="index-1">みだし２</h2>
		<div class="ystdb-heading has-text-align-left is-style-ystdtb-h2">
		<div class="ystdb-heading__container">
		<h2 class="ystdb-heading__text is-clear-style" id="index-2">カスタム見出しああああ</h2>
		</div>
		</div>
		<h2 id="index-3">みだし２</h2>';

		$this->assertSame(
			$this->remove_tab_nl( $expected ),
			$this->remove_tab_nl( $content )
		);
	}


	/**
	 * 目次テスト
	 */
	function test_custom_heading() {
		$content = '
		<div class="ystdb-heading has-text-align-left is-style-ystdtb-h2">
		<div class="ystdb-heading__container">
		<h2 class="ystdb-heading__text is-clear-style">カスタム見出しああああ</h2>
		</div>
		</div>
		<h2>みだし２</h2>
		<h2>みだし２</h2>';

		$content = $this->get_the_content( $content );

		$expected = '
		<div class="ys-toc">
		<p class="ys-toc__title">目次</p>
		<ul class="ys-toc__list">
			<li class="ys-toc__item"><a class="ys-toc__link" href="#index-1">カスタム見出しああああ</a></li>
			<li class="ys-toc__item"><a class="ys-toc__link" href="#index-2">みだし２</a></li>
			<li class="ys-toc__item"><a class="ys-toc__link" href="#index-3">みだし２</a></li>
		</ul>
		</div>
		<div class="ystdb-heading has-text-align-left is-style-ystdtb-h2">
		<div class="ystdb-heading__container">
		<h2 class="ystdb-heading__text is-clear-style" id="index-1">カスタム見出しああああ</h2>
		</div>
		</div>
		<h2 id="index-2">みだし２</h2>
		<h2 id="index-3">みだし２</h2>';

		$this->assertSame(
			$this->remove_tab_nl( $expected ),
			$this->remove_tab_nl( $content )
		);
	}

	/**
	 * 新設定が未保存の場合だけ旧設定を参照することを確認.
	 */
	public function test_post_type_setting_falls_back_to_legacy_setting() {
		$this->assertTrue( \ystandard\TOC::is_enabled_for_post_type( 'post' ) );

		update_option( 'ys_disable_toc_post_type_post', 1 );
		$this->assertFalse( \ystandard\TOC::is_enabled_for_post_type( 'post' ) );

		update_option( 'ys_create_post_toc', 1 );
		$this->assertTrue( \ystandard\TOC::is_enabled_for_post_type( 'post' ) );

		update_option( 'ys_disable_toc_post_type_post', 0 );
		update_option( 'ys_create_post_toc', '' );
		$legacy_value = get_option( 'ys_disable_toc_post_type_post' );
		$this->assertFalse( \ystandard\TOC::is_enabled_for_post_type( 'post' ) );
		$this->assertSame( $legacy_value, get_option( 'ys_disable_toc_post_type_post' ) );
	}

	/**
	 * 自動作成を無効化してもショートコードを利用できることを確認.
	 */
	public function test_shortcode_works_when_auto_creation_is_disabled() {
		$content = '<h2>見出し1</h2><h2>見出し2</h2><h2>見出し3</h2>';
		$post_id = $this->factory->post->create(
			[
				'post_content' => $content,
			]
		);
		$this->go_to( get_permalink( $post_id ) );
		the_post();
		update_option( 'ys_create_post_toc', '' );
		update_option( 'ys_toc_display_type', 'none' );

		$toc    = new \ystandard\TOC();
		$result = $toc->do_shortcode(
			[
				'title' => 'ショートコード目次',
			],
			$content
		);

		$this->assertStringContainsString( '<p class="ys-toc__title">ショートコード目次</p>', $result );
		$this->assertSame( 1, substr_count( $result, '<div class="ys-toc">' ) );
	}
}
