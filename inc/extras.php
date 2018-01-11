<?php
//------------------------------------------------------------------------------
//
//	フィルタフック/アクション関連の関数
//
//------------------------------------------------------------------------------

//------------------------------------------------------------------------------
// bodyのクラス追加
//------------------------------------------------------------------------------
if (!function_exists( 'ys_extras_body_classes')) {
	function ys_extras_body_classes( $classes ) {

		// 背景画像があればクラス追加
		if ( get_background_image() ) {
			$classes[] = 'custom-background-image';
		}

		// ampならクラス追加
		if ( ys_is_amp() ) {
			$classes[] = 'amp';
		} else {
			$classes[] = 'no-amp';
		}

		// 1カラムの場合
		if( ys_is_one_column() ) {
			$classes[] = 'ys-one-column';
		}

		return $classes;
	}
}
add_filter( 'body_class', 'ys_extras_body_classes' );





//------------------------------------------------------------------------------
// セルフピンバック対策
//------------------------------------------------------------------------------
if( ! function_exists( 'ys_extras_no_self_ping' ) ) {
	function ys_extras_no_self_ping( &$links ) {
					$home = get_option( 'home' );
					foreach ( $links as $l => $link )
									if ( 0 === strpos( $link, $home ) )
													unset($links[$l]);
	}
}
add_action( 'pre_ping', 'ys_extras_no_self_ping' );



/*
 *	RSSフィードにアイキャッチ画像を表示
 */
if( ! function_exists( 'ys_extras_rss_the_post_thumbnail' ) ) {
	function ys_extras_rss_the_post_thumbnail($content) {
		global $post;
		if( has_post_thumbnail( $post->ID ) && false == ys_get_setting( 'ys_hide_post_thumbnail' ) ) {
			$content = get_the_post_thumbnail($post->ID) . $content;
		}
		return $content;
	}
}
add_filter('the_excerpt_rss', 'ys_extras_rss_the_post_thumbnail');
add_filter('the_content_feed', 'ys_extras_rss_the_post_thumbnail');




/*
 *	ブログタイトルの区切り文字変更
 */
if( ! function_exists( 'ys_extras_document_title_separator' ) ) {
	function ys_extras_document_title_separator($sep) {

		$sep_option = ys_get_setting('ys_title_separate');
		if('' != $sep_option){
			$sep = $sep_option;
		}
		return $sep;
	}
}
add_filter('document_title_separator', 'ys_extras_document_title_separator');




//------------------------------------------------------------------------------
// iframeのレスポンシブ化
//------------------------------------------------------------------------------
if( ! function_exists( 'ys_extras_iframe_responsive' ) ) {
	function ys_extras_iframe_responsive($the_content) {
		if ( is_singular() && !ys_is_amp() ) {
			//マッチさせたいiframeのURLをリスト化
			$patternlist = array(
								'youtube\.com',
								'vine\.co',
								'https:\/\/www\.google\.com\/maps\/embed'
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
add_filter('the_content','ys_extras_iframe_responsive');




//------------------------------------------------------------------------------
// moreタグの置換
//------------------------------------------------------------------------------
if( ! function_exists( 'ys_extras_more_tag_replace' )){
	function ys_extras_more_tag_replace($the_content) {

		$replace = ys_template_get_the_advertisement_more_tag();
		if( is_page() ) $replace = '';
		$replace = apply_filters('ys_more_tag_replace',$replace);

		if($replace !== ''){
			//more部分を広告に置換
			$the_content = preg_replace('/<p><span id="more-[0-9]+"><\/span><\/p>/', $replace, $the_content);
			//「remove_filter( 'the_content', 'wpautop' )」対策
			$the_content = preg_replace('/<span id="more-[0-9]+"><\/span>/', $replace, $the_content);
		}
		return $the_content;
	}
}
add_filter('the_content', 'ys_extras_more_tag_replace');



/**
 * サイトアイコン
 */
if( ! function_exists( 'ys_extras_site_icon_meta_tags' )){
	function ys_extras_site_icon_meta_tags($meta_tags) {
		$meta_tags = array(
				sprintf( '<link rel="icon" href="%s" sizes="32x32" />', esc_url( get_site_icon_url( 32 ) ) ),
				sprintf( '<link rel="icon" href="%s" sizes="192x192" />', esc_url( get_site_icon_url( 192 ) ) )
		);
		return $meta_tags;
	}
}
add_filter( 'site_icon_meta_tags', 'ys_extras_site_icon_meta_tags' );








//------------------------------------------------------------------------------
// 投稿抜粋文字数
//------------------------------------------------------------------------------
if( ! function_exists( 'ys_extras_excerpt_length' )){
	function ys_extras_excerpt_length( $length ) {
		return 120;
	}
}
add_filter( 'excerpt_length', 'ys_extras_excerpt_length', 999 );




//------------------------------------------------------------------------------
// 投稿抜粋の最後に付ける文字列
//------------------------------------------------------------------------------
if( ! function_exists( 'ys_extras_excerpt_more' )){
	function ys_extras_excerpt_more( $more ) {
		return '…';
	}
}
add_filter( 'excerpt_more', 'ys_extras_excerpt_more' );




//------------------------------------------------------------------------------
// コメントフォームの順番を入れ替える
//------------------------------------------------------------------------------
if( ! function_exists( 'ys_extras_comment_form_fields' )){
	function ys_extras_comment_form_fields( $fields ) {
		// 退避
		$comment = $fields['comment'];

		// 一旦削除
		unset( $fields['comment'] );

		// 追加
		$fields['comment'] = $comment;

		return $fields;
	}
}
add_filter( 'comment_form_fields', 'ys_extras_comment_form_fields' );




//------------------------------------------------------------------------------
// コメントフォームで使えるタグを追加
//------------------------------------------------------------------------------
function ys_extras_comment_tags($data) {
	global $allowedtags;
	$allowedtags['pre'] = array('class'=>array());
	$allowedtags['blockquote'] = array();
	return $data;
}
add_filter('comments_open', 'ys_extras_comment_tags');
add_filter('pre_comment_approved', 'ys_extras_comment_tags');





















//------------------------------------------------------------------------------
//
//	wp_footer関連
//
//------------------------------------------------------------------------------








//-----------------------------------------------
//	json-LD出力
//-----------------------------------------------
if(!function_exists( 'ys_extras_the_json_ld')) {
	function ys_extras_the_json_ld(){

		// 全体に関わる部分
		$context = 'http://schema.org';
		$logourl = '';
		$logowidth = 0;
		$logoheight = 0;

		if(has_custom_logo()){
			$logo = ys_utilities_get_custom_logo_image_src();
			$logourl = $logo[0];
			$logowidth = $logo[1];
			$logoheight = $logo[2];
		}

		$blogurl = get_bloginfo('url');
		$blogname = get_bloginfo('name');


		if (is_singular() && !is_front_page() && !is_home()) {
			//スキーマ
			$type = 'Article';
			$mainEntityOfPagetype = 'WebPage';
			$name = get_the_title();
			//著者情報
			$authorType = 'Person';
			$authorName = get_the_author();
			//時間に関する項目
			$dataPublished = get_the_date('Y-n-j');
			$dateModified = get_the_modified_date('Y-n-j');
			//画像
			$imageurl = '';
			$image = ys_utilities_get_post_thumbnail('full','',get_the_ID());
			if($image){
				$imageurl = $image[0];
				$imgwidth = $image[1];
				$imgheight = $image[2];
			}
			//カテゴリー
			$category = get_the_category();
			if($category):
				$articleSection = $category[0]->name;
			else:
				$articleSection = '';
			endif;

			//記事内容
			$articleBody = get_the_content();
			//URL
			$url = get_the_permalink();
			//パブリッシャー
			$publisherType = 'Organization';
			$publisherName = get_bloginfo('name');
			$imgtype = 'ImageObject';
			$publisherlogo = $logourl;
			$publisherlogowidth = $logowidth;
			$publisherlogoheight = $logoheight;

			$json = "{
						\"@context\" : \"{$context}\",
						\"@type\" : \"{$type}\",
						\"mainEntityOfPage\" : {
								\"@type\" : \"{$mainEntityOfPagetype}\",
								\"@id\" : \"{$url}\"
								},
						\"name\" : \"{$name}\",
						\"headline\" : \"{$name}\",
						\"author\" : {
								 \"@type\" : \"{$authorType}\",
								 \"name\" : \"{$authorName}\"
								 },
						\"datePublished\" : \"{$dataPublished}\",
						\"dateModified\" : \"{$dateModified}\",
						\"image\" : {
							\"@type\" : \"{$imgtype}\",
							\"url\" : \"{$imageurl}\",
							\"width\" : \"{$imgwidth}\",
							\"height\" : \"{$imgheight}\"
								},
						\"articleSection\" : \"{$articleSection}\",
						\"url\" : \"{$url}\",
						\"publisher\" : {
								 \"@type\" : \"{$publisherType}\",
								 \"name\" : \"{$publisherName}\",
								 \"logo\" : {
										\"@type\" : \"{$imgtype}\",
										\"url\" : \"{$publisherlogo}\",
										\"width\" : \"{$publisherlogowidth}\",
										\"height\" : \"{$publisherlogoheight}\"
										}
								 }
						}";

		} else {
			// TOP・一覧ページなど

			$json = "
					[
						{
							\"@context\": \"{$context}\",
							\"@type\": \"Organization\",
							\"url\": \"{$blogurl}\",
							\"logo\": \"{$logourl}\"
						},
						{
							\"@context\": \"{$context}\",
							\"@type\": \"Website\",
							\"url\": \"{$blogurl}\",
							\"name\": \"{$blogname}\",
							\"alternateName\": \"{$blogname}\"
						}
					]
					";
		}
		if($json !== ''){
			echo '<script type="application/ld+json">'.$json.'</script>';
		}
	}
}
add_action( 'wp_footer', 'ys_extras_the_json_ld' );
?>