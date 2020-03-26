<?php
/**
 * 投稿コメント関連
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

/**
 * Class Comment
 *
 * @package ystandard
 */
class Comment {

	/**
	 * Comment constructor.
	 */
	public function __construct() {
		add_filter( 'comments_open', [ $this, 'comment_tags' ] );
		add_filter( 'pre_comment_approved', [ $this, 'comment_tags' ] );
		add_filter( 'comment_form_fields', [ $this, 'comment_form_fields' ] );
	}

	/**
	 * コメント内で使用できるタグのセット
	 *
	 * @param array $data Tags.
	 *
	 * @return array
	 */
	public function comment_tags( $data ) {
		global $allowedtags;
		/**
		 * もろもろ削除
		 */
		unset( $allowedtags['a'] );
		unset( $allowedtags['abbr'] );
		unset( $allowedtags['acronym'] );
		unset( $allowedtags['b'] );
		unset( $allowedtags['div'] );
		unset( $allowedtags['cite'] );
		unset( $allowedtags['code'] );
		unset( $allowedtags['del'] );
		unset( $allowedtags['em'] );
		unset( $allowedtags['i'] );
		unset( $allowedtags['q'] );
		unset( $allowedtags['strike'] );
		unset( $allowedtags['strong'] );
		/**
		 * 使えるタグをセットする
		 */
		$allowedtags['a']          = [];
		$allowedtags['pre']        = [];
		$allowedtags['code']       = [];
		$allowedtags['blockquote'] = [];
		$allowedtags['cite']       = [];
		$allowedtags['strong']     = [];

		return $data;
	}

	/**
	 * コメントフォームの順番を入れ替える
	 *
	 * @param array $fields コメントフィールド.
	 *
	 * @return array
	 */
	public function comment_form_fields( $fields ) {
		$comment = $fields['comment'];
		unset( $fields['comment'] );
		$fields['comment'] = $comment;

		return $fields;
	}
}

new Comment();
