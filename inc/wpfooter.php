<?php
//------------------------------------------------------------------------------
//
//	wp_footer関連
//
//------------------------------------------------------------------------------




//-----------------------------------------------
//	json-LD出力
//-----------------------------------------------
if(!function_exists( 'ys_wpfooter_the_json_ld')) {
	function ys_wpfooter_the_json_ld(){

		if(ys_is_amp()){
			return ;
		}

		// 全体に関わる部分
		$context = 'http://schema.org';
		$logourl = '';
		$logowidth = 0;
		$logoheight = 0;
		
		if(has_custom_logo()){
			$logo = ys_image_get_custom_logo_image_src();
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
			$image = ys_image_get_post_thumbnail( get_the_ID(), 'full' );
			if($image){
				$imageurl = $image[0];
				$imgwidth = $image[1];
				$imgheight = $image[2];
			}
			//カテゴリー
			$category = get_the_category();
			if($category):
				$articleSection = get_the_category()[0]->name;
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
add_action( 'wp_footer', 'ys_wpfooter_the_json_ld' );

?>