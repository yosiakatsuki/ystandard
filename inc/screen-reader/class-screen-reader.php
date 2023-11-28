<?php
/**
 * スクリーンリーダー関連の処理
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

class Screen_Reader {

	/**
	 * Screen_Reader constructor.
	 */
	public function __construct() {
		add_action( 'wp_body_open', [ $this, 'skip_to_content' ], 1 );
	}

	/**
	 * コンテンツにスキップ
	 */
	public function skip_to_content() {
		$href = '#content';
		$text = __( 'Skip to content', 'ystandard' );
		printf(
			'<a class="skip-link screen-reader-text" href="%s">%s</a>' . PHP_EOL,
			esc_url( $href ),
			esc_html( $text )
		);
	}
}

new Screen_Reader();
