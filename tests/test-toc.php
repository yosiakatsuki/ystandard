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
}
