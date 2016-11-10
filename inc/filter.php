<?php
//------------------------------------------------------------------------------
//
//	フィルタフック関連
//
//------------------------------------------------------------------------------

//------------------------------------------------------------------------------
// bodyのクラス追加
//------------------------------------------------------------------------------
if (!function_exists( 'ys_filter_body_classes')) {
	function ys_filter_body_classes( $classes ) {

		// 背景画像があればクラス追加
		if ( get_background_image() ) {
			$classes[] = 'custom-background-image';
		}

		// サイドバーがなければクラス追加
		if ( ! is_active_sidebar( 'sidebar-main' ) ) {
			$classes[] = 'no-sidebar';
		}

		return $classes;
	}
}
add_filter( 'body_class', 'ys_filter_body_classes' );




//------------------------------------------------------------------------------
// スクリプトにasync付ける
//------------------------------------------------------------------------------
if( ! function_exists( 'ys_filter_add_async' ) ) {
	function ys_filter_add_async($tag) {
		if(is_admin()){
			return $tag;
		}
		//jQuery関連以外のjsにasyncを付ける
		if ( strpos($tag,'jquery') !== false ) return $tag;
		return str_replace("src", "async src", $tag);
	}
}
add_filter('script_loader_tag','ys_filter_add_async');




//------------------------------------------------------------------------------
// iframeのレスポンシブ化
//------------------------------------------------------------------------------
if( ! function_exists( 'ys_filter_iframe_responsive' ) ) {
	function ys_filter_iframe_responsive($the_content) {
		if ( is_singular() ) {
			//マッチさせたいiframeのURLをリスト化
			$patternlist = array(
								'youtube\.com'
								,'instagram\.com'
								,'vine\.co'
								,'www\.google\.com\/maps'
							);
			//置換する
			foreach ($patternlist as $value) {
				$pattern = '/<iframe[^>]+?'.$value.'[^<]+?<\/iframe>/is';
				$the_content = preg_replace($pattern, '<div class="iframe-responsive-container"><div class="iframe-responsive">${0}</div></div>', $the_content);
			}
		}
		return $the_content;
	}
}
add_filter('the_content','ys_filter_iframe_responsive');




//------------------------------------------------------------------------------
// moreタグの置換
//------------------------------------------------------------------------------
if( ! function_exists( 'ys_filter_more_tag_replace' )){
	function ys_filter_more_tag_replace($the_content) {

		// 開発テーマでカスタマイズする（もしくは子テーマで拡張させる）
		$replace = '';

		if($replace !== ''){
			//more部分を広告に置換
			$the_content = preg_replace('/<p><span id="more-[0-9]+"><\/span><\/p>/', $replace, $the_content);
			//「remove_filter( 'the_content', 'wpautop' )」対策
			$the_content = preg_replace('/<span id="more-[0-9]+"><\/span>/', $replace, $the_content);
		}
		return $the_content;
	}
}
add_filter('the_content', 'ys_filter_more_tag_replace');




//------------------------------------------------------------------------------
// サイトアイコン
//------------------------------------------------------------------------------
if( ! function_exists( 'ys_filter_site_icon_meta_tags' )){
	function ys_filter_site_icon_meta_tags($meta_tags) {
		$meta_tags = array(
				sprintf( '<link rel="icon" href="%s" sizes="32x32" />', esc_url( get_site_icon_url( 32 ) ) ),
				sprintf( '<link rel="icon" href="%s" sizes="192x192" />', esc_url( get_site_icon_url( 192 ) ) )
		);
		return $meta_tags;
	}
}
add_filter( 'site_icon_meta_tags', 'ys_filter_site_icon_meta_tags' );




//------------------------------------------------------------------------------
// 投稿抜粋文字数
//------------------------------------------------------------------------------
if( ! function_exists( 'ys_filter_excerpt_length' )){
	function ys_filter_excerpt_length( $length ) {
		return 160;
	}
}
add_filter( 'excerpt_length', 'ys_filter_excerpt_length', 999 );




//------------------------------------------------------------------------------
// 投稿抜粋の最後に付ける文字列
//------------------------------------------------------------------------------
if( ! function_exists( 'ys_filter_excerpt_more' )){
	function ys_filter_excerpt_more( $more ) {
		return '…';
	}
}
add_filter( 'excerpt_more', 'ys_filter_excerpt_more' );




//------------------------------------------------------------------------------
// コメントフォームの順番を入れ替える
//------------------------------------------------------------------------------
if( ! function_exists( 'ys_filter_comment_form_fields' )){
	function ys_filter_comment_form_fields( $fields ) {
		// 退避
		$comment = $fields['comment'];

		// 一旦削除
		unset( $fields['comment'] );

		// 追加
		$fields['comment'] = $comment;

		return $fields;
	}
}
add_filter( 'comment_form_fields', 'ys_filter_comment_form_fields' );




//------------------------------------------------------------------------------
// アーカイブタイトルを変える
//------------------------------------------------------------------------------
if( ! function_exists( 'ys_filter_get_the_archive_title' )){
	function ys_filter_get_the_archive_title( $title ) {

		// 標準フォーマット
		$title_format = '「%s」の記事一覧';

	 if ( is_category() ) {
			$title = sprintf( $title_format, single_cat_title( '', false ) );
		} elseif ( is_tag() ) {
			$title = sprintf( $title_format, single_tag_title( '', false ) );
		} elseif ( is_author() ) {
			$title = sprintf( $title_format, '<span class="vcard">' . get_the_author() . '</span>' );
		} elseif ( is_post_type_archive() ) {
			$title = sprintf( $title_format, post_type_archive_title( '', false ) );
		} elseif ( is_tax() ) {
			$tax = get_taxonomy( get_queried_object()->taxonomy );
			/* translators: 1: Taxonomy singular name, 2: Current taxonomy term */
			$title = sprintf( '%1$s「%2$s」の投稿一覧' , $tax->labels->singular_name, single_term_title( '', false ) );
		}

		return $title;
	}
}
add_filter( 'get_the_archive_title', 'ys_filter_get_the_archive_title' );



?>