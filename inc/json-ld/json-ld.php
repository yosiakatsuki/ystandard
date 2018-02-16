<?php
/**
 * json-LD出力
 */
if( ! function_exists( 'ys_the_json_ld' ) ) {
	function ys_the_json_ld(){

		// 全体に関わる部分
		$context = 'http://schema.org';
		$logourl = '';
		$logowidth = 0;
		$logoheight = 0;

		if(has_custom_logo()){
			$logo = ys_get_custom_logo_image_object();
			$logourl = $logo[0];
			$logowidth = $logo[1];
			$logoheight = $logo[2];
		}

		$blogurl = get_bloginfo('url');
		$blogname = get_bloginfo('name');


		if ( is_singular() && ! is_front_page() && ! is_home() ) {
			// //スキーマ
			// $type = 'Article';
			// $mainEntityOfPagetype = 'WebPage';
			// $name = get_the_title();
			// //著者情報
			// $authorType = 'Person';
			// $authorName = get_the_author();
			// //時間に関する項目
			// $dataPublished = get_the_date('Y-n-j');
			// $dateModified = get_the_modified_date('Y-n-j');
			// //画像
			// $imageurl = '';
			// $image = ys_get_the_image_object( 'full', get_the_ID() );
			// $imgwidth = 0;
			// $imgheight = 0;
			// if($image){
			// 	$imageurl = $image[0];
			// 	$imgwidth = $image[1];
			// 	$imgheight = $image[2];
			// }
			// //カテゴリー
			// $category = get_the_category();
			// if($category):
			// 	$articleSection = $category[0]->name;
			// else:
			// 	$articleSection = '';
			// endif;
      //
			// //記事内容
			// $articleBody = get_the_content();
			// //URL
			// $url = get_the_permalink();
			// //パブリッシャー
			// $publisherType = 'Organization';
			// $publisherName = get_bloginfo('name');
			// $imgtype = 'ImageObject';
			// $publisherlogo = $logourl;
			// $publisherlogowidth = $logowidth;
			// $publisherlogoheight = $logoheight;
      //
			// $json = "{
			// 			\"@context\" : \"{$context}\",
			// 			\"@type\" : \"{$type}\",
			// 			\"mainEntityOfPage\" : {
			// 					\"@type\" : \"{$mainEntityOfPagetype}\",
			// 					\"@id\" : \"{$url}\"
			// 					},
			// 			\"name\" : \"{$name}\",
			// 			\"headline\" : \"{$name}\",
			// 			\"author\" : {
			// 					 \"@type\" : \"{$authorType}\",
			// 					 \"name\" : \"{$authorName}\"
			// 					 },
			// 			\"datePublished\" : \"{$dataPublished}\",
			// 			\"dateModified\" : \"{$dateModified}\",
			// 			\"image\" : {
			// 				\"@type\" : \"{$imgtype}\",
			// 				\"url\" : \"{$imageurl}\",
			// 				\"width\" : \"{$imgwidth}\",
			// 				\"height\" : \"{$imgheight}\"
			// 					},
			// 			\"articleSection\" : \"{$articleSection}\",
			// 			\"url\" : \"{$url}\",
			// 			\"publisher\" : {
			// 					 \"@type\" : \"{$publisherType}\",
			// 					 \"name\" : \"{$publisherName}\",
			// 					 \"logo\" : {
			// 							\"@type\" : \"{$imgtype}\",
			// 							\"url\" : \"{$publisherlogo}\",
			// 							\"width\" : \"{$publisherlogowidth}\",
			// 							\"height\" : \"{$publisherlogoheight}\"
			// 							}
			// 					 }
			// 			}";
			$json = ys_get_json_ld_article();
		} else {
			/**
			 * TOP・一覧ページなど
			 */
			$json = array(
								ys_get_json_ld_organization(),
								ys_get_json_ld_website()
							);
		}
		$json = json_encode( $json, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT );
		if( '' !== $json ){
			echo '<script type="application/ld+json">'.$json.'</script>';
		}
	}
}
add_action( 'wp_footer', 'ys_the_json_ld' );

/**
 * json-LD Organization作成
 */
function ys_get_json_ld_organization() {
	$json = array();
	$json['@context'] = 'http://schema.org';
	$json['@type'] = 'Organization';
	$json['url'] = get_bloginfo('url');
	if( has_custom_logo() ){
		$logo = ys_get_custom_logo_image_object();
		$json['logo'] = $logo[0];
	}
	return $json;
}
/**
 * json-LD Website 作成
 */
function ys_get_json_ld_website() {
	$json = array();
	$json['@context'] = 'http://schema.org';
	$json['@type'] = 'Website';
	$json['url'] = get_bloginfo('url');
	$json['name'] = get_bloginfo('name');
	$json['alternateName'] = get_bloginfo('name');
	return $json;
}
/**
 * json-LD Article 作成
 */
function ys_get_json_ld_article() {
	global $post;
	$json = array();
	$url = get_the_permalink( $post->ID );
	$name = get_the_title( $post->ID );
	$excerpt = esc_html( ys_get_the_custom_excerpt( '', 0, $post->ID ) );
	$content = esc_html( ys_get_plain_text( $post->post_content ) );
	$json['@context'] = 'http://schema.org';
	$json['@type'] = 'Article';
	$json['mainEntityOfPage'] = array(
																'@type' => 'WebPage',
																'@id'   => $url
															);
	$json['name'] = $name;
	$json['headline'] = $name;
	$json['description'] = $excerpt;
	$json['articleBody'] = $content;
	$json['author'] = array(
											'@type' => 'Person',
											'name'  => ys_get_author_display_name()
										);
	$json['datePublished'] = get_the_date( DATE_ATOM,  $post->ID );
	$json['dateModified'] = get_post_modified_time( DATE_ATOM, false, $post->ID );
	$image = ys_get_the_image_object( 'full', $post->ID );
	if( $image ) {
		$json['image'] = array(
											'@type'  => 'ImageObject',
											'url'    => $image[0],
											'width'  => $image[1],
											'height' => $image[2],
										);
	}
	$category = get_the_category();
	if( $category ){
		$json['articleSection'] = esc_html( $category[0]->name );
	}
	$json['url'] = $url;
	$json['publisher'] = array(
												'@type' => 'Organization',
												'name'  => get_bloginfo('name')
											);
	$publisher_img = ys_get_publisher_image();
	if( $publisher_img ) {
		$json['publisher']['logo'] = array(
																		'@type' => 'ImageObject',
																		'url' => $publisher_img[0],
																		'width' => $publisher_img[1],
																		'height' => $publisher_img[2]
																	);
	}
	return $json;
}