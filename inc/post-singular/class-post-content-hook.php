<?php
/**
 * 投稿・固定ページ・投稿タイプ 本文にフックする処理
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

/**
 * Class Post_Content_Hook
 */
class Post_Content_Hook {
	/**
	 * インスタンス
	 *
	 * @var Post_Content_Hook
	 */
	private static $instance;

	/**
	 * 埋め込みの縦横比
	 *
	 * @var string
	 */
	private static $embed_aspect;

	/**
	 * インスタンス取得
	 *
	 * @return Post_Content_Hook
	 */
	public static function get_instance(): Post_Content_Hook {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Post_Content_Hook constructor.
	 */
	private function __construct() {
		add_filter( 'the_content', [ __CLASS__, 'replace_more' ] );
		add_filter( 'the_content', [ __CLASS__, 'responsive_iframe' ] );
		add_filter( 'the_content', [ __CLASS__, 'replace_first_heading' ] );
	}

	/**
	 * Moreタグの置換
	 *
	 * @param string $content Content.
	 *
	 * @return string
	 */
	public static function replace_more( $content ) {

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
	public static function responsive_iframe( $content ) {
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
				self::$embed_aspect = $value['aspect'];
				$pattern            = '/<iframe[^>]+?' . $value['url'] . '[^<]+?<\/iframe>/is';
				$content            = preg_replace_callback(
					$pattern,
					function ( $matches ) {
						$map    = $matches[0];
						$aspect = preg_match( '/data-aspect-ratio="(.+?)"/is', $map, $aspect_match );
						if ( empty( $aspect ) || ( isset( $aspect_match[1] ) && 'none' !== $aspect_match[1] ) ) {
							$embed_aspect = isset( $aspect_match[1] ) ? $aspect_match[1] : self::$embed_aspect;
							$map          = '<div class="wp-embed-aspect-' . $embed_aspect . ' wp-has-aspect-ratio"><div class="wp-block-embed__wrapper">' . $map . '</div></div>';
						}

						return $map;
					},
					$content
				);
			}
		}

		return $content;
	}

	/**
	 * 最初の見出しの置換
	 *
	 * @param string $content Content.
	 *
	 * @return string
	 */
	public static function replace_first_heading( $content ) {

		if ( preg_match_all( '/(<h([1-6]{1})[^>]*>).*<\/h\2>/msuU', $content, $matches, PREG_SET_ORDER ) ) {
			$replace = apply_filters( 'ys_before_first_heading_content', '', $content );
			if ( isset( $matches[0] ) && isset( $matches[0][0] ) ) {
				$content = str_replace(
					$matches[0][0],
					$replace . $matches[0][0],
					$content
				);
			}
		}

		return $content;
	}
}

Post_Content_Hook::get_instance();
