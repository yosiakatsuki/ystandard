<?php
/**
 * コンテンツ周りの処理
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

if ( ! function_exists( 'ys_document_title_separator' ) ) {
	/**
	 * ブログ名区切り文字設定
	 *
	 * @param  string $sep 区切り文字.
	 *
	 * @return string
	 */
	function ys_document_title_separator( $sep ) {
		$sep_option = ys_get_option( 'ys_title_separate' );
		if ( '' != $sep_option ) {
			$sep = $sep_option;
		}

		return $sep;
	}
}
add_filter( 'document_title_separator', 'ys_document_title_separator' );

if ( ! function_exists( 'ys_more_tag_replace' ) ) {
	/**
	 * Moreタグの置換
	 *
	 * @param string $the_content 投稿本文.
	 *
	 * @return string
	 */
	function ys_more_tag_replace( $the_content ) {
		$replace = '';
		if ( ys_is_active_advertisement() ) {
			$replace = ys_get_ad_more_tag();
		}
		$replace = apply_filters( 'ys_more_tag_replace', $replace );
		if ( '' !== $replace ) {
			$the_content = preg_replace( '/<p><span id="more-[0-9]+"><\/span><\/p>/', $replace, $the_content );
			/**
			 * 「remove_filter( 'the_content', 'wpautop' )」対策
			 */
			$the_content = preg_replace( '/<span id="more-[0-9]+"><\/span>/', $replace, $the_content );
		}

		return $the_content;
	}
}
add_filter( 'the_content', 'ys_more_tag_replace' );

if ( ! function_exists( 'ys_add_iframe_responsive_container' ) ) {
	/**
	 * Iframeのレスポンシブ化
	 *
	 * @param string $the_content 投稿本文.
	 * @param string $type        タイプ.
	 *
	 * @return string
	 */
	function ys_add_iframe_responsive_container( $the_content, $type = '' ) {
		if ( ys_is_amp() ) {
			return $the_content;
		}
		/**
		 * マッチさせたいiframeのURLをリスト化
		 */
		$pattern_list = array(
			'youtube\.com',
			'vine\.co',
			'https:\/\/www\.google\.com\/maps\/embed',
		);
		$pattern_list = apply_filters( 'ys_iframe_responsive_pattern_list', $pattern_list, $type );
		/**
		 * 置換する
		 */
		$replace = apply_filters( 'ys_iframe_responsive_wrap', '<div class="embed__container"><div class="embed__item">${0}</div></div>' );
		foreach ( $pattern_list as $value ) {
			$pattern     = '/<iframe[^>]+?' . $value . '[^<]+?<\/iframe>/is';
			$the_content = preg_replace( $pattern, $replace, $the_content );
		}

		return $the_content;
	}
}

/**
 * 投稿本文内のiframeレスポンシブ対応
 *
 * @param string $the_content コンテンツ.
 *
 * @return string
 */
function ys_iframe_responsive_content( $the_content ) {
	$the_content = ys_add_iframe_responsive_container( $the_content, 'the_content' );

	return $the_content;
}

add_filter( 'the_content', 'ys_iframe_responsive_content' );

/**
 * ウィジェット内のiframeレスポンシブ対応
 *
 * @param string $the_content コンテンツ.
 *
 * @return string
 */
function ys_iframe_responsive_widget( $the_content ) {
	$the_content = ys_add_iframe_responsive_container( $the_content, 'widget_text' );

	return $the_content;
}

add_filter( 'widget_text', 'ys_iframe_responsive_widget' );

if ( ! function_exists( 'ys_excerpt_length' ) ) {
	/**
	 * 投稿抜粋文字数
	 *
	 * @param int $length 抜粋文字数.
	 *
	 * @return string
	 */
	function ys_excerpt_length( $length = null ) {
		$option_length = ys_get_option( 'ys_option_excerpt_length' );
		/**
		 * 直接呼び出しでもフックでも設定値を返す
		 */
		if ( ! is_null( $length ) ) {
			return $option_length;
		}

		return $option_length;
	}
}
add_filter( 'excerpt_length', 'ys_excerpt_length', 999 );

if ( ! function_exists( 'ys_excerpt_more' ) ) {
	/**
	 * 投稿抜粋の最後につける文字
	 *
	 * @param string $more 抜粋につける文字.
	 *
	 * @return string
	 */
	function ys_excerpt_more( $more ) {
		$more_str = ' …';
		if ( 0 == ys_excerpt_length() ) {
			$more_str = '';
		}

		return $more_str;
	}
}
add_filter( 'excerpt_more', 'ys_excerpt_more' );

/**
 * 投稿抜粋文を作成
 *
 * @param  string  $sep     抜粋最後の文字.
 * @param  integer $length  抜粋長さ.
 * @param  integer $post_id 投稿ID.
 *
 * @return string
 */
function ys_get_the_custom_excerpt( $sep = ' …', $length = 0, $post_id = 0 ) {
	if ( 0 === $post_id ) {
		$post_id = get_the_ID();
	}
	if ( 0 === $length ) {
		$length = ys_excerpt_length();
	}
	$post = get_post( $post_id );
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
		$content = ys_get_plain_text( $content );
	}
	/**
	 * 長さ調節
	 */
	if ( mb_strlen( $content ) > $length ) {
		$content = mb_substr( $content, 0, $length - mb_strlen( $sep ) ) . $sep;
	}

	return apply_filters( 'ys_get_the_custom_excerpt', $content, $post_id );
}

if ( ! function_exists( 'ys_the_custom_excerpt' ) ) {
	/**
	 * 投稿抜粋文を出力
	 *
	 * @param  string  $sep     抜粋最後の文字.
	 * @param  integer $length  抜粋長さ.
	 * @param  integer $post_id 投稿ID.
	 */
	function ys_the_custom_excerpt( $sep = ' …', $length = 0, $post_id = 0 ) {
		echo ys_get_the_custom_excerpt( $length, $sep, $post_id );
	}
}

if ( ! function_exists( 'ys_get_entry_read_more_text' ) ) {
	/**
	 * 続きを読む テキスト
	 */
	function ys_get_entry_read_more_text() {
		$read_more = 'READ MORE';

		return apply_filters( 'ys_entry_read_more_text', $read_more );
	}
}
/**
 * 続きを読む テキスト
 */
function ys_the_entry_read_more_text() {
	echo ys_get_entry_read_more_text();
}

/**
 * 関連記事データ取得
 *
 * @return bool|mixed
 */
function ys_get_related_posts_data() {

	if ( ! ys_is_active_related_post() ) {
		return false;
	}
	$categories = ys_get_the_category_id_list();
	$args       = array(
		'post__not_in' => array( get_the_ID() ),
		'category__in' => $categories,
	);

	/**
	 * キャッシュデータ作成・取得の準備
	 */
	$expiration = ys_get_option( 'ys_query_cache_related_posts' );
	$cache_args = array(
		'category__in' => $categories,
	);
	$cache_key  = 'related_posts';
	$cache_data = YS_Cache::get_cache( $cache_key, $cache_args );
	/**
	 * 関連記事データの取得
	 */
	if ( false === $cache_data ) {
		$related_posts = get_posts( ys_get_posts_args_rand( 5, $args ) );
		/**
		 * キャッシュ作成
		 */
		YS_Cache::set_cache( $cache_key, $related_posts, $cache_args, $expiration );

	} else {
		$related_posts = $cache_data;
	}

	return apply_filters( 'ys_get_related_posts_data', $related_posts, $args );
}