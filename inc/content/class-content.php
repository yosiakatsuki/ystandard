<?php
/**
 * コンテンツ部分の処理
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

/**
 * Class Content
 *
 * @package ystandard
 */
class Content {

	/**
	 * アクション・フィルターの登録
	 */
	public function register() {
		add_filter( 'get_the_archive_title', [ $this, 'archive_title' ] );

		add_filter( 'document_title_separator', [ $this, 'title_separator' ] );
		add_filter( 'excerpt_length', [ $this, 'excerpt_length' ], 999 );
	}

	/**
	 * アーカイブタイトル
	 *
	 * @param string $title title.
	 *
	 * @return string
	 */
	public function archive_title( $title ) {
		/**
		 * 標準フォーマット
		 */
		$title_format = apply_filters( 'ys_archive_title_format', '%s' );
		/**
		 * ページング
		 */
		$paged = get_query_var( 'paged' );

		if ( is_category() ) {
			$title = sprintf( $title_format, single_cat_title( '', false ) );
		} elseif ( is_tag() ) {
			$title = sprintf( $title_format, single_tag_title( '', false ) );
		} elseif ( is_author() ) {
			$title = sprintf( $title_format, get_the_author() );
		} elseif ( is_search() ) {
			$title_format = '「%s」の検索結果';
			$title        = sprintf( $title_format, esc_html( get_search_query( false ) ) );
		} elseif ( is_post_type_archive() ) {
			$title = sprintf( $title_format, post_type_archive_title( '', false ) );
		} elseif ( is_tax() ) {
			$object = get_queried_object();
			$tax    = get_taxonomy( $object->taxonomy );
			/* translators: 1: Taxonomy singular name, 2: Current taxonomy term */
			$title = sprintf(
				'%1$s : %2$s',
				$tax->labels->singular_name,
				single_term_title( '', false )
			);
		} elseif ( is_home() ) {
			if ( 'page' === get_option( 'show_on_front' ) && get_option( 'page_for_posts' ) ) {
				$title = get_the_title( get_option( 'page_for_posts' ) );
			} else {
				$title = '';
			}
		}

		return apply_filters( 'ys_get_the_archive_title', $title, $paged );
	}

	/**
	 * アーカイブURL
	 */
	function get_archive_url() {
		$url            = '';
		$queried_object = get_queried_object();
		if ( is_category() ) {
			$url = get_category_link( $queried_object->term_id );
		} elseif ( is_tag() ) {
			$url = get_tag_link( $queried_object->term_id );
		} elseif ( is_author() ) {
			$author_id = get_query_var( 'author' );
			$url       = esc_url( get_author_posts_url( get_the_author_meta( 'ID', $author_id ) ) );
		} elseif ( is_search() ) {
			$url = home_url( '/?s=' . urlencode( get_search_query( false ) ) );
			$url = get_post_type_archive_link( esc_url_raw( $url ) );
		} elseif ( is_post_type_archive() ) {
			$post_type = ys_get_post_type();
			$url       = get_post_type_archive_link( $post_type );
		} elseif ( is_tax() ) {
			$tax = get_taxonomy( $queried_object->taxonomy );
			$url = get_term_link( $queried_object->term_id, $tax->name );
		}

		return apply_filters( 'ys_get_the_archive_url', $url );
	}

	/**
	 * アーカイブアイテムクラス作成
	 *
	 * @return array
	 */
	public static function get_archive_item_class() {
		$class = [];
		/**
		 * 共通でセットするクラス
		 */
		$class[] = 'archive__item';
		$class[] = 'is-' . ys_get_option( 'ys_archive_type', 'list' );

		return $class;
	}


	/**
	 * 投稿抜粋文を作成
	 *
	 * @param string  $sep     抜粋最後の文字.
	 * @param integer $length  抜粋長さ.
	 * @param integer $post_id 投稿ID.
	 *
	 * @return string
	 */
	public static function get_custom_excerpt( $sep = ' …', $length = 0, $post_id = 0 ) {
		$post_id = 0 === $post_id ? get_the_ID() : $post_id;
		$length  = 0 === $length ? ys_get_option_by_int( 'ys_option_excerpt_length', 110 ) : $length;
		$post    = get_post( $post_id );
		if ( post_password_required( $post ) ) {
			return __( 'There is no excerpt because this is a protected post.' );
		}
		$content = $post->post_excerpt;
		if ( '' === $content ) {
			/**
			 * Excerptが無ければ本文から作る
			 */
			$content = $post->post_content;
			/**
			 * Moreタグ以降を削除
			 */
			$content = preg_replace( '/<!--more-->.+/is', '', $content );
			$content = Template_Function::get_plain_text( $content );
		}
		/**
		 * 長さ調節
		 */
		if ( mb_strlen( $content ) > $length ) {
			$content = mb_substr( $content, 0, $length - mb_strlen( $sep ) ) . $sep;
		}

		return apply_filters( 'ys_get_the_custom_excerpt', $content, $post_id );
	}

	/**
	 * 投稿抜粋文字数
	 *
	 * @param int $length 抜粋文字数.
	 *
	 * @return int
	 */
	public function excerpt_length( $length = null ) {

		return ys_get_option_by_int( 'ys_option_excerpt_length', 110 );
	}

	/**
	 * ブログ名区切り文字設定
	 *
	 * @param string $sep 区切り文字.
	 *
	 * @return string
	 */
	public function title_separator( $sep ) {
		$sep_option = ys_get_option( 'ys_title_separate', '' );
		if ( '' !== $sep_option ) {
			$sep = $sep_option;
		}

		return $sep;
	}
}

$class_content = new Content();
$class_content->register();
