<?php
//------------------------------------------------------------------------------
//
//	フィルタフック/アクション関連の関数
//
//------------------------------------------------------------------------------










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
// add_action( 'wp_footer', 'ys_extras_the_json_ld' );
?>