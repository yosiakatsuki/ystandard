<?php
/**
 * サイドバー関連.
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

use ystandard\utils\Post_Type;

/**
 * Class Sidebar
 */
class Sidebar {

	/**
	 * インスタンス
	 *
	 * @var Sidebar
	 */
	private static $instance;

	/**
	 * インスタンス取得
	 *
	 * @return Sidebar
	 */
	public static function get_instance(): Sidebar {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * コンストラクタ.
	 */
	private function __construct() {
		add_filter( 'ys_sidebar_class', [ $this, 'sidebar_class' ] );
	}

	/**
	 * サイドバークラス
	 *
	 * @param array $classes Classes.
	 *
	 * @return array
	 */
	public function sidebar_class( array $classes ): array {
		// 投稿本文エリア背景色設定.
		$content_background = Post_Content::get_content_background_color( Post_Type::get_post_type() );
		if ( $content_background ) {
			$classes[] = 'has-content-background';
		}

		return $classes;
	}

}
