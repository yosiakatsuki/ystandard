<?php
/**
 * ウィジェット
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

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
		add_action( 'after_setup_theme', [ $this, 'set_widget' ] );
	}

	/**
	 * フィルターのセット
	 */
	public function set_widget() {
		add_action(
			'ys_singular_footer',
			[ $this, 'post_paging' ],
			Content::get_footer_priority( 'paging' )
		);
	}

	/**
	 * ページング表示
	 */
	public function post_paging() {
		if ( ! $this->is_active_paging() ) {
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
		Template::get_template_part(
			'template-parts/parts/post-paging',
			'',
			[ 'ys_paging' => $data ]
		);
	}

	/**
	 * ページング表示するか
	 *
	 * @return bool
	 */
	private function is_active_paging() {
		if ( ! is_single() ) {
			return false;
		}
		if ( ! Option::get_option_by_bool( 'ys_show_post_paging', true ) ) {
			return false;
		}
		if ( Utility::to_bool( Content::get_post_meta( 'ys_hide_paging' ) ) ) {
			return false;
		}

		return false;
	}

}

$class_paging = new Paging();
$class_paging->register();
