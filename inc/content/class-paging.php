<?php
/**
 * ウィジェット
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

use ystandard\utils\Post_Type;

defined( 'ABSPATH' ) || die();

/**
 * Class Paging
 *
 * @package ystandard
 */
class Paging {

	/**
	 * フックやショートコードの登録
	 */
	public function register() {
		add_action( 'ys_set_singular_content', [ $this, 'set_singular_paging' ] );
	}

	/**
	 * ページングのセット
	 */
	public function set_singular_paging() {
		Post_Footer::set_singular_footer(
			'paging',
			[ __CLASS__, 'post_paging' ]
		);
	}

	/**
	 * ページング表示
	 */
	public static function post_paging() {

		if ( ! self::is_active_paging() ) {
			return;
		}
		$in_same_term = apply_filters( 'ys_paging_in_same_term', false );
		$post_prev    = get_previous_post( $in_same_term );
		$post_next    = get_next_post( $in_same_term );
		if ( empty( $post_prev ) && empty( $post_next ) ) {
			return;
		}
		$data         = [];
		$data['prev'] = empty( $post_prev ) ? 0 : $post_prev->ID;
		$data['next'] = empty( $post_next ) ? 0 : $post_next->ID;

		/**
		 * テンプレート読み込み
		 */
		get_template_part(
			'template-parts/post/post-paging',
			'',
			[ 'paging' => $data ]
		);
	}

	/**
	 * ページング表示するか
	 *
	 * @return bool
	 */
	private static function is_active_paging() {
		if ( ! is_singular() ) {
			return false;
		}
		if ( is_page() ) {
			return false;
		}
		$post_type = Post_Type::get_post_type();
		$filter    = apply_filters( "ys_show_{$post_type}_paging", null );
		if ( is_null( $filter ) ) {
			$option  = ! is_post_type_hierarchical( $post_type );
			$setting = "ys_show_{$post_type}_paging";
			// 投稿タイプ別設定が保存されていれば、その値を優先する.
			if ( Option::exists_option( $setting ) ) {
				$option = Option::get_option_by_bool( $setting, $option );
			} else {
				// 新設定が未保存の場合は、投稿タイプの性質に応じた従来設定を引き継ぐ.
				$fallback = Post_Type::get_fallback_post_type( $post_type );
				$option   = Option::get_option_by_bool( "ys_show_{$fallback}_paging", $option );
			}
		} else {
			$option = $filter;
		}

		// 固定ページ以外の詳細ページでは投稿単位設定で表示状態を上書きする.
		if ( is_singular( $post_type ) && ! Post_Meta::resolve_state( 'paging', $option ) ) {
			return false;
		}

		return true;
	}

}

$class_paging = new Paging();
$class_paging->register();
