<?php
/**
 * Class Blocks_Test
 *
 * @package ystandard
 */

/**
 * Class Blocks_Test
 */
class Blocks_Test extends WP_UnitTestCase {

	/**
	 * セットアップ.
	 */
	public function set_up() {
		parent::set_up();

		$GLOBALS['wp_styles'] = null;
		wp_styles();
	}

	/**
	 * ブロックCSS登録フックの確認.
	 */
	public function test_register_block_styles_hook() {
		$blocks = $this->get_blocks_instance();

		$this->assertSame( 20, has_action( 'init', [ $blocks, 'register_theme_block_styles' ] ) );
		$this->assertSame( 20, has_action( 'wp_enqueue_scripts', [ $blocks, 'enqueue_required_styles' ] ) );
		$this->assertSame( 100, has_action( 'enqueue_block_assets', [ $blocks, 'enqueue_block_editor_styles' ] ) );
	}

	/**
	 * ボタンブロック使用時のCSS読み込み確認.
	 */
	public function test_enqueue_button_style_when_button_block_is_rendered() {
		if ( $this->should_load_block_assets_on_demand() ) {
			do_blocks( $this->get_button_block_content() );
		} else {
			do_action( 'wp_enqueue_scripts' );
		}

		$handle = $this->get_button_style_handle();
		$this->assertTrue( wp_style_is( $handle, 'enqueued' ) );
		$this->assertSame(
			get_template_directory() . '/css/block-styles/core__button/button.css',
			wp_styles()->registered[ $handle ]->extra['path']
		);
	}

	/**
	 * 未使用のボタンCSSが読み込まれないことの確認.
	 */
	public function test_do_not_enqueue_unused_button_style_on_demand() {
		if ( ! $this->should_load_block_assets_on_demand() ) {
			$this->markTestSkipped( 'ブロックCSSのオンデマンド読み込みが無効です。' );
		}

		$this->assertFalse( wp_style_is( $this->get_button_style_handle(), 'enqueued' ) );
	}

	/**
	 * 同じブロックが複数ある場合の重複確認.
	 */
	public function test_enqueue_button_style_only_once() {
		if ( ! $this->should_load_block_assets_on_demand() ) {
			$this->markTestSkipped( 'ブロックCSSのオンデマンド読み込みが無効です。' );
		}

		do_blocks( $this->get_button_block_content() . $this->get_button_block_content() );

		$this->assertSame(
			1,
			count( array_keys( wp_styles()->queue, $this->get_button_style_handle(), true ) )
		);
	}

	/**
	 * 常時読み込みCSSの確認.
	 */
	public function test_enqueue_required_button_style_without_button_block() {
		$filter = static function ( $style_names ) {
			$style_names[] = 'button';

			return $style_names;
		};
		add_filter( 'ys_required_block_style_names', $filter );

		try {
			$blocks = $this->get_blocks_instance();
			$blocks->register_theme_block_styles();
			$blocks->enqueue_required_styles();
		} finally {
			remove_filter( 'ys_required_block_style_names', $filter );
		}

		$this->assertTrue( wp_style_is( $this->get_button_style_handle(), 'enqueued' ) );
	}

	/**
	 * ブロックエディターCSSの読み込み順確認.
	 */
	public function test_enqueue_block_editor_styles_after_shared_styles() {
		set_current_screen( 'post' );

		try {
			$blocks = $this->get_blocks_instance();
			$blocks->register_theme_block_styles();
			do_action( 'enqueue_block_assets' );

			$style_handle        = $this->get_button_style_handle();
			$editor_style_handle = "{$style_handle}-editor";
			$style_position      = array_search( $style_handle, wp_styles()->queue, true );
			$editor_position     = array_search( $editor_style_handle, wp_styles()->queue, true );
		} finally {
			set_current_screen( 'front' );
		}

		$this->assertNotFalse( $style_position );
		$this->assertNotFalse( $editor_position );
		$this->assertGreaterThan( $style_position, $editor_position );
	}

	/**
	 * Blocksインスタンスを取得.
	 *
	 * @return \ystandard\Blocks
	 */
	private function get_blocks_instance() {
		global $wp_filter;

		$callbacks = $wp_filter['init']->callbacks[20];
		foreach ( $callbacks as $callback ) {
			$function = $callback['function'];
			if (
				is_array( $function ) &&
				$function[0] instanceof \ystandard\Blocks &&
				'register_theme_block_styles' === $function[1]
			) {
				return $function[0];
			}
		}

		$this->fail( 'Blocksインスタンスを取得できません。' );
	}

	/**
	 * ボタンCSSのハンドルを取得.
	 *
	 * @return string
	 */
	private function get_button_style_handle() {
		$style_handle = 'ystd-core/button';
		$button_dir   = get_template_directory() . '/css/block-styles/core__button';
		if ( false !== strpos( $button_dir, get_stylesheet_directory() ) ) {
			$style_handle .= '-child';
		}

		return $style_handle;
	}

	/**
	 * ボタンブロックのコンテンツを取得.
	 *
	 * @return string
	 */
	private function get_button_block_content() {
		return '<!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button">Button</a></div><!-- /wp:button -->';
	}

	/**
	 * ブロックCSSをオンデマンドで読み込むか.
	 *
	 * @return bool
	 */
	private function should_load_block_assets_on_demand() {
		if ( function_exists( 'wp_should_load_block_assets_on_demand' ) ) {
			return wp_should_load_block_assets_on_demand();
		}

		return wp_should_load_separate_core_block_assets();
	}
}
