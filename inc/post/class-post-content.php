<?php
/**
 * 投稿・固定ページ・投稿タイプ関連
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

use ystandard\utils\Post_Type;

/**
 * Class Post_Content
 */
class Post_Content {

	/**
	 * Post_Content constructor.
	 */
	public function __construct() {
		add_filter( 'post_class', [ $this, 'post_class' ] );
		add_filter( 'ys_sidebar_class', [ $this, 'sidebar_class' ] );
		add_filter( 'ys_get_css_custom_properties_args', [ $this, 'add_custom_property' ] );
	}

	/**
	 * 本文用のカスタムプロパティ追加
	 *
	 * @param array $vars CSSカスタムプロパティ設定.
	 *
	 * @return array
	 */
	public function add_custom_property( $vars ) {
		// 投稿本文エリア背景色設定.
		$content_background = $this->get_content_background_color( Post_Type::get_post_type() );
		if ( $content_background ) {
			$vars['--ystd--content--background'] = $content_background;
		}

		return $vars;
	}

	/**
	 * Post Classを操作する
	 *
	 * @param array $classes Classes.
	 *
	 * @return array
	 */
	public function post_class( $classes ) {
		// 構造化データ「hentry」の削除
		if ( apply_filters( 'ystd_remove_hentry', true ) ) {
			$classes = array_diff( $classes, [ 'hentry' ] );
		}
		// 投稿本文エリア背景色設定
		$content_background = $this->get_content_background_color( Post_Type::get_post_type() );
		if ( $content_background ) {
			$classes[] = 'has-content-background';
		}

		return $classes;
	}

	/**
	 * サイドバークラス
	 *
	 * @param array $classes Classes.
	 *
	 * @return array
	 */
	public function sidebar_class( $classes ) {
		// 投稿本文エリア背景色設定
		$content_background = $this->get_content_background_color( Post_Type::get_post_type() );
		if ( $content_background ) {
			$classes[] = 'has-content-background';
		}

		return $classes;
	}

	/**
	 * 投稿本文エリア背景色設定取得.
	 *
	 * @param string $post_type 投稿タイプ.
	 *
	 * @return string
	 */
	private function get_content_background_color( $post_type ) {
		// 設定存在チェック、設定がなければ代替の投稿タイプを取得.
		if ( Option::exists_option( "ys_{$post_type}_use_content_bg" ) ) {
			$post_type = Post_Type::get_fallback_post_type( $post_type );
		}

		$use_bg = Option::get_option_by_bool( "ys_{$post_type}_use_content_bg" );
		$color  = Option::get_option( "ys_{$post_type}_content_bg", '' );
		// 背景色を使わない設定であればcolorを空白にする.
		if ( ! $use_bg ) {
			$color = '';
		}

		return apply_filters( 'ys_get_content_background_color', $color, $post_type );
	}
}

new Post_Content();
