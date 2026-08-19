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
		add_filter( 'post_class', [ $this, 'post_class' ] );
	}

	/**
	 * アイキャッチ画像を表示するか
	 *
	 * @param int|null $post_id 投稿ID.
	 *
	 * @return bool
	 */
	public static function is_active_post_thumbnail( ?int $post_id = null ): bool {
		// 詳細ページ以外では本文ヘッダー用のアイキャッチを表示しない.
		if ( ! is_singular() ) {
			return false;
		}
		// 通常タイプは本文ヘッダーと一緒に表示状態を切り替える.
		if ( ! Post_Header::is_active_post_header() ) {
			return false;
		}

		return self::is_enabled_post_thumbnail( $post_id );
	}

	/**
	 * アイキャッチ画像の設定が有効か
	 *
	 * @param int|null $post_id 投稿ID.
	 *
	 * @return bool
	 */
	private static function is_enabled_post_thumbnail( ?int $post_id = null ): bool {
		$result = true;
		// アーカイブなどでは投稿タイプ別の詳細ページ設定を適用しない.
		if ( ! is_singular() ) {
			return false;
		}
		// 画像が未設定の場合は表示対象から外す.
		if ( ! has_post_thumbnail( $post_id ) ) {
			$result = false;
		}
		$post_type = Post_Type::get_post_type();
		$filter    = apply_filters( "ys_show_{$post_type}_header_thumbnail", null );
		// フィルター指定がなければカスタマイザーの設定を使用する.
		if ( is_null( $filter ) ) {
			$fallback = Post_Type::get_fallback_post_type( $post_type );
			$option   = Option::get_option_by_bool( "ys_show_{$fallback}_header_thumbnail", true );
		} else {
			// 子テーマやプラグインから指定された表示設定を優先する.
			$option = $filter;
		}
		// 現在の投稿タイプに対する表示設定を最終結果へ反映する.
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
		get_template_part( 'template-parts/parts/post-thumbnail' );
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
		get_template_part(
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
		// HTMLが明示された場合はテーマ標準の判定より優先する.
		if ( ! is_null( $hook ) ) {
			return $hook;
		}
		// フロントページと投稿ヘッダーなしテンプレートでは従来どおり表示しない.
		if ( Front_Page::is_single_front_page() || Template_Type::is_no_title_template() ) {
			return '';
		}
		// 本文内へ表示する通常タイプはサイトヘッダー直下へ出力しない.
		if ( ! self::is_full_post_thumbnail() ) {
			return '';
		}
		// 全幅タイプは本文ヘッダーの表示状態と分離して判定する.
		if ( ! self::is_enabled_post_thumbnail() ) {
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

	/**
	 * Post Classを操作する
	 *
	 * @param array $classes Classes.
	 *
	 * @return array
	 */
	public function post_class( $classes ) {

		/**
		 * アイキャッチ画像の有無
		 */
		if ( is_singular() ) {
			if ( self::is_active_post_thumbnail() ) {
				$classes[] = 'has-thumbnail';
			}
		}

		return $classes;
	}
}

Post_Singular_Thumbnail::get_instance();
