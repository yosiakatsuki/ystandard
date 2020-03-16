<?php
/**
 * 投稿関連クラス
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

/**
 * Class Post
 */
class Post {

	/**
	 * YS_Post constructor.
	 */
	public function __construct() {
		add_filter( 'post_class', [ $this, 'post_class' ] );
		add_filter( 'the_content', [ $this, 'replace_more' ] );
		add_filter( 'the_content', [ $this, 'responsive_iframe' ] );
		add_filter( 'widget_text', [ $this, 'responsive_iframe' ] );
	}

	/**
	 * Post Classを操作する
	 *
	 * @param array $classes Classes.
	 *
	 * @return array
	 */
	public function post_class( $classes ) {
		/**
		 * [hentryの削除]
		 */
		if ( apply_filters( 'ystd_remove_hentry', true ) ) {
			$classes = array_diff( $classes, [ 'hentry' ] );
		}
		/**
		 * アイキャッチ画像の有無
		 */
		if ( is_singular() ) {
			if ( ys_is_active_post_thumbnail() ) {
				$classes[] = 'has-thumbnail';
			}
		}

		return $classes;
	}

	/**
	 * Moreタグの置換
	 *
	 * @param string $content Content.
	 *
	 * @return string
	 */
	public function replace_more( $content ) {

		$replace = apply_filters( 'ys_more_content', '' );
		if ( '' !== $replace ) {
			$content = preg_replace(
				'/<p><span id="more-[0-9]+"><\/span><\/p>/',
				$replace,
				$content
			);
			/**
			 * 「remove_filter( 'the_content', 'wpautop' )」対策
			 */
			$content = preg_replace(
				'/<span id="more-[0-9]+"><\/span>/',
				$replace,
				$content
			);
		}

		return $content;
	}

	/**
	 * 投稿内のiframeレスポンシブ対応
	 *
	 * @param string $content Content.
	 *
	 * @return string
	 */
	public function responsive_iframe( $content ) {
		if ( ys_is_amp() ) {
			return $content;
		}
		/**
		 * マッチさせたいiframeのURLをリスト化
		 */
		$list = [
			[
				'url'    => 'https:\/\/www\.google\.com\/maps\/embed',
				'aspect' => '4-3',
			],
		];
		$list = apply_filters( 'ys_responsive_iframe_pattern', $list );
		/**
		 * 置換する
		 */
		foreach ( $list as $value ) {
			if ( isset( $value['url'] ) && isset( $value['aspect'] ) ) {
				$replace = '<div class="wp-embed-aspect-' . $value['aspect'] . ' wp-has-aspect-ratio"><div class="wp-block-embed__wrapper">${0}</div></div>';
				$pattern = '/<iframe[^>]+?' . $value['url'] . '[^<]+?<\/iframe>/is';
				$content = preg_replace( $pattern, $replace, $content );
			}
		}

		return $content;
	}

	/**
	 * 投稿オプション(post-meta)取得
	 *
	 * @param string  $key     設定キー.
	 * @param integer $post_id 投稿ID.
	 *
	 * @return string
	 */
	public static function get_post_meta( $key, $post_id = 0 ) {
		if ( 0 === $post_id ) {
			$post_id = get_the_ID();
		}

		return get_post_meta( $post_id, $key, true );
	}

}

new Post();
