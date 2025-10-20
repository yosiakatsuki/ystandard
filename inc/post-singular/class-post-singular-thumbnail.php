<?php
/**
 * 投稿・固定ページ・投稿タイプ サムネイル関連
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

use ystandard\utils\Post_Type;

defined( 'ABSPATH' ) || die();

/**
 * Class Post_Singular_Thumbnail
 */
class Post_Singular_Thumbnail {
	/**
	 * インスタンス
	 *
	 * @var Post_Singular_Thumbnail
	 */
	private static $instance;

	/**
	 * インスタンス取得
	 *
	 * @return Post_Singular_Thumbnail
	 */
	public static function get_instance(): Post_Singular_Thumbnail {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Post_Singular_Thumbnail constructor.
	 */
	private function __construct() {
		add_action( 'ys_after_site_header', [ $this, 'header_post_thumbnail' ] );
	}

	/**
	 * アイキャッチ画像を表示するか
	 *
	 * @param int|null $post_id 投稿ID.
	 *
	 * @return bool
	 */
	public static function is_active_post_thumbnail( int $post_id = null ): bool {
		$result = true;
		if ( ! is_singular() ) {
			return false;
		}
		if ( ! Post_Header::is_active_post_header() ) {
			return false;
		}
		if ( ! has_post_thumbnail( $post_id ) ) {
			$result = false;
		}
		$post_type = Post_Type::get_post_type();
		$filter    = apply_filters( "ys_show_{$post_type}_header_thumbnail", null );
		if ( is_null( $filter ) ) {
			$fallback = Post_Type::get_fallback_post_type( $post_type );
			$option   = Option::get_option_by_bool( "ys_show_{$fallback}_header_thumbnail", true );
		} else {
			$option = $filter;
		}
		if ( is_singular( $post_type ) ) {
			$result = ! $option ? false : $result;
		}

		return apply_filters( 'ys_is_active_post_thumbnail', $result );
	}

	/**
	 * フル幅サムネイル設定か
	 *
	 * @return bool
	 */
	public static function is_full_post_thumbnail() {
		$post_type = Post_Type::get_post_type();
		$filter    = apply_filters( "ys_{$post_type}_post_thumbnail_type", null );
		if ( is_null( $filter ) ) {
			$fallback = Post_Type::get_fallback_post_type( $post_type );
			$type     = Option::get_option( "ys_{$fallback}_post_thumbnail_type", 'default' );
		} else {
			$type = $filter;
		}

		if ( is_singular( $post_type ) && 'full' === $type ) {
			return true;
		}

		return false;
	}

	/**
	 * アイキャッチ画像の表示
	 */
	public static function post_thumbnail_default() {
		if ( self::is_full_post_thumbnail() ) {
			return;
		}
		if ( ! self::is_active_post_thumbnail() ) {
			return;
		}
		ob_start();
		Template::get_template_part( 'template-parts/parts/post-thumbnail' );
		echo ob_get_clean();
	}

	/**
	 * アイキャッチ画像の表示 - ヘッダー
	 */
	public function header_post_thumbnail() {
		$thumbnail = $this->get_header_post_thumbnail();
		if ( empty( $thumbnail ) ) {
			return;
		}
		ob_start();
		Template::get_template_part(
			'template-parts/parts/header-thumbnail',
			'',
			[ 'header_thumbnail' => $thumbnail ]
		);
		$thumbnail_html = ob_get_clean();
		echo apply_filters( 'ys_the_header_post_thumbnail', $thumbnail_html );
	}


	/**
	 * ヘッダーサムネイル取得
	 *
	 * @return string
	 */
	private function get_header_post_thumbnail() {

		$hook = apply_filters( 'ys_get_header_post_thumbnail', null );
		if ( ! is_null( $hook ) ) {
			return $hook;
		}
		if ( ! self::is_full_post_thumbnail() ) {
			return '';
		}
		if ( ! self::is_active_post_thumbnail() ) {
			return '';
		}

		return get_the_post_thumbnail(
			get_the_ID(),
			'post-thumbnail',
			[
				'id'      => 'site-header-thumbnail__image',
				'class'   => 'site-header-thumbnail__image',
				'alt'     => get_the_title(),
				'loading' => 'eager',
			]
		);
	}
}

Post_Singular_Thumbnail::get_instance();
