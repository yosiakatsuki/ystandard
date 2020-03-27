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
		add_filter( 'post_class', [ $this, 'post_class' ] );
		add_filter( 'the_content', [ $this, 'replace_more' ] );
		add_filter( 'the_content', [ $this, 'responsive_iframe' ] );
		add_filter( 'get_the_excerpt', '\ystandard\Content::get_custom_excerpt' );
		add_filter( 'widget_text', [ $this, 'responsive_iframe' ] );
		add_filter( 'get_the_archive_title', [ $this, 'archive_title' ] );
		add_filter( 'document_title_separator', [ $this, 'title_separator' ] );
		add_filter( 'excerpt_length', [ $this, 'excerpt_length' ], 999 );
	}

	/**
	 * Post Type
	 *
	 * @return false|string
	 */
	public static function get_post_type() {
		/**
		 * @global \WP_Query
		 */
		global $wp_query;
		$post_type = get_post_type();
		if ( ! $post_type ) {
			if ( isset( $wp_query->query['post_type'] ) ) {
				$post_type = $wp_query->query['post_type'];
			}
		}

		return $post_type;
	}

	/**
	 * アイキャッチ画像を表示するか
	 *
	 * @param int $post_id 投稿ID.
	 *
	 * @return bool
	 */
	public static function is_active_post_thumbnail( $post_id = null ) {
		$result = true;
		if ( ! is_singular() ) {
			return false;
		}
		if ( ! has_post_thumbnail( $post_id ) ) {
			$result = false;
		}
		/**
		 * 投稿ページ
		 */
		if ( is_single() ) {
			if ( ! ys_get_option_by_bool( 'ys_show_post_thumbnail', true ) ) {
				$result = false;
			}
		}
		/**
		 * 固定ページ
		 */
		if ( is_page() ) {
			if ( ! ys_get_option_by_bool( 'ys_show_page_thumbnail', true ) ) {
				$result = false;
			}
		}

		return apply_filters( 'ys_is_active_post_thumbnail', $result );
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
			if ( self::is_active_post_thumbnail() ) {
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
		if ( AMP::is_amp() ) {
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
	public static function get_archive_url() {
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
			$post_type = self::get_post_type();
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
		$length  = 0 === $length ? Option::get_option_by_int( 'ys_option_excerpt_length', 110 ) : $length;
		$content = self::get_custom_excerpt_raw( $post_id );
		/**
		 * 長さ調節
		 */
		if ( mb_strlen( $content ) > $length ) {
			$content = mb_substr( $content, 0, $length - mb_strlen( $sep ) ) . $sep;
		}

		return apply_filters( 'ys_get_the_custom_excerpt', $content, $post_id );
	}

	/**
	 * 切り取らない投稿抜粋文を作成
	 *
	 * @param integer $post_id 投稿ID.
	 *
	 * @return string
	 */
	public static function get_custom_excerpt_raw( $post_id = 0 ) {
		$post_id = 0 === $post_id ? get_the_ID() : $post_id;
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
			$content = Utility::get_plain_text( $content );
		}

		return $content;
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
