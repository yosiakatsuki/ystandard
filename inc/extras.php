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

		return $classes;
	}
}
add_filter( 'body_class', 'ys_extras_body_classes' );




//------------------------------------------------------------------------------
// スクリプトにasync付ける
//------------------------------------------------------------------------------
if( ! function_exists( 'ys_extras_add_async' ) ) {
	function ys_extras_add_async($tag) {
		if(is_admin()){
			return $tag;
		}
		//jQuery関連以外のjsにasyncを付ける
		if ( strpos($tag,'jquery') !== false ) return $tag;
		return str_replace("src", "async src", $tag);
	}
}
add_filter('script_loader_tag','ys_extras_add_async');




//------------------------------------------------------------------------------
// セルフピンバック対策
//------------------------------------------------------------------------------
function ys_no_self_ping( &$links ) {
				$home = get_option( 'home' );
				foreach ( $links as $l => $link )
								if ( 0 === strpos( $link, $home ) )
												unset($links[$l]);
}
add_action( 'pre_ping', 'ys_no_self_ping' );




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

		$ad = ys_template_get_the_advertisement_more_tag();
		$replace = $ad;
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




//------------------------------------------------------------------------------
// サイトアイコン
//------------------------------------------------------------------------------
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
// アーカイブタイトルを変える
//------------------------------------------------------------------------------
if( ! function_exists( 'ys_extras_get_the_archive_title' )){
	function ys_extras_get_the_archive_title( $title ) {

		// 標準フォーマット
		$title_format = '「%s」の記事一覧';

	 if ( is_category() ) {
			$title = sprintf( $title_format, single_cat_title( '', false ) );
		} elseif ( is_tag() ) {
			$title = sprintf( $title_format, single_tag_title( '', false ) );
		} elseif ( is_author() ) {
			$title = sprintf( $title_format, '<span class="vcard">' . get_the_author() . '</span>' );
		} elseif ( is_search() ) {
			$title_format = '「%s」に関連する記事一覧';
			$title = sprintf( $title_format, esc_html( get_search_query( false ) ) );
		} elseif ( is_post_type_archive() ) {
			$title = sprintf( $title_format, post_type_archive_title( '', false ) );
		} elseif ( is_tax() ) {
			$tax = get_taxonomy( get_queried_object()->taxonomy );
			/* translators: 1: Taxonomy singular name, 2: Current taxonomy term */
			$title = sprintf( '%1$s「%2$s」の投稿一覧' , $tax->labels->singular_name, single_term_title( '', false ) );
		} elseif (is_home()) {
			$title = '記事一覧';
		}

		if(get_query_var( 'paged' )) {
			$title .= ' '.get_query_var( 'paged' ).'ページ';
		}

		return $title;
	}
}
add_filter( 'get_the_archive_title', 'ys_extras_get_the_archive_title' );









//------------------------------------------------------------------------------
//
//	wp_headへの出力追加
//
//------------------------------------------------------------------------------
// -------------------------------------------
// canonicalタグ出力
// -------------------------------------------
if(!function_exists( 'ys_extras_add_canonical')) {
	function ys_extras_add_canonical(){

		$canonical = '';
		if( is_home() || is_front_page() ) {
				$canonical = home_url();

		} elseif ( is_category() ) {
			$canonical = get_category_link( get_query_var('cat') );

		} elseif ( is_tag() ) {
			$tag = get_term_by( 'name', urldecode( get_query_var('tag') ), 'post_tag' );
			$canonical = get_tag_link( $tag->term_id );

		} elseif ( is_search() ) {
			$canonical = get_search_link();

		} elseif ( is_page() || is_single() ) {
			$canonical = get_permalink();

		}
		if($canonical !== ''){
			echo '<link rel="canonical" href="'.$canonical.'">'.PHP_EOL;
		}
	}
}
add_action( 'wp_head', 'ys_extras_add_canonical' );




// -------------------------------------------
// next,prevタグ出力
// -------------------------------------------
if(!function_exists( 'ys_extras_adjacent_posts_rel_link_wp_head')) {
	function ys_extras_adjacent_posts_rel_link_wp_head(){


		if(is_single() || is_page()) {
			//固定ページ・投稿ページ
			global $post,$page;
			$pagecnt = substr_count($post->post_content,'<!--nextpage-->') + 1;
			if ($pagecnt > 1){
				//prev
				if($page > 1) {
						if($page == 2){
							//2ページ目なら/nなしのリンク
							echo '<link rel="prev" href="'.get_the_permalink().'" />'.PHP_EOL;
						} else {
							echo '<link rel="prev" href="'.get_the_permalink().($page - 1).'" />'.PHP_EOL;
						}
				}
				//next
				if($page < $pagecnt) {
					if($page == 0){
						//1ページ目なら/2
						echo '<link rel="next" href="'.get_the_permalink().'2" />'.PHP_EOL;
					} else {
						echo '<link rel="next" href="'.get_the_permalink().($page + 1).'" />'.PHP_EOL;
					}
				}
			}
		} else {
			//アーカイブ
			global $wp_query;
			// MAXページ数と現在ページ数を取得
			$total   = isset( $wp_query->max_num_pages ) ? $wp_query->max_num_pages : 1;
			$current = get_query_var( 'paged' ) ? (int) get_query_var( 'paged' )  : 1;
			if($current > 1) {
				echo '<link rel="prev" href="'.get_pagenum_link( $current - 1 ).'" />'.PHP_EOL;
			}
			if($current < $total) {
				echo '<link rel="next" href="'.get_pagenum_link( $current + 1 ).'" />'.PHP_EOL;
			}
		}
	}
}
add_action( 'wp_head', 'ys_extras_adjacent_posts_rel_link_wp_head' );




// -------------------------------------------
// noindexの追加
// -------------------------------------------
if(!function_exists( 'ys_extras_add_noindex')) {
	function ys_extras_add_noindex(){

		$noindexoutput = false;

		if(is_404()){
			//  404ページをnoindex
			$noindexoutput = true;

		} elseif(is_search()) {
			// 検索結果をnoindex
			$noindexoutput = true;

		} elseif(is_category() && ys_get_setting('ys_archive_noindex_category',0) == 1){
			// カテゴリーページのnoindex設定がされていればnoindex
			$noindexoutput = true;

		} elseif(is_tag() && ys_get_setting('ys_archive_noindex_tag',1) == 1){
			// カテゴリーページのnoindex設定がされていればnoindex
			$noindexoutput = true;

		} elseif(is_author() && ys_get_setting('ys_archive_noindex_author',1) == 1){
			// カテゴリーページのnoindex設定がされていればnoindex
			$noindexoutput = true;

		} elseif(is_date() && ys_get_setting('ys_archive_noindex_date',1) == 1){
			// カテゴリーページのnoindex設定がされていればnoindex
			$noindexoutput = true;

		} elseif(is_single() || is_page()){
			if(get_post_meta(get_the_ID(),'ys_noindex',true)==='1'){
				// 投稿・固定ページでnoindex設定されていればnoindex
				$noindexoutput = true;
			}

		} else {
			// その他特別な設定がされていればnoindex
			$noindexoutput = ys_extras_custom_noindex();
		}

		// noindex出力
		if($noindexoutput){
			echo '<meta name="robots" content="noindex,follow">'.PHP_EOL;
		}
	}
}
add_action( 'wp_head', 'ys_extras_add_noindex' );

// -------------------------------------------
// noindex条件追加用関数
// -------------------------------------------
if(!function_exists( 'ys_extras_custom_noindex') ) {
	function ys_extras_custom_noindex() {
		//自分で書き込むまではとりあえずFalse
		return false;
	}
}




// -------------------------------------------
// google analyticsタグ出力
// -------------------------------------------
if(!function_exists( 'ys_extras_add_googleanarytics')) {
	function ys_extras_add_googleanarytics(){
		$ga_tracking_id = trim(get_option('ys_ga_tracking_id',''));
		$output_ga = true;

		if(is_user_logged_in()){
			$user = wp_get_current_user();

			if ($user->has_cap( 'edit_posts' ) ) {
				$output_ga = false;
			}
		}

		if('' == $ga_tracking_id){
			$output_ga = false;
		}

		if($output_ga){
			if(ys_is_amp()){
				$ys_ampanalytics = <<<EOD
<amp-analytics type="googleanalytics" id="analytics1">
<script type="application/json">
{
	"vars": {
		"account": "$ga_tracking_id"
	},
	"triggers": {
		"trackPageview": {
			"on": "visible",
			"request": "pageview"
		}
	}
}
</script>
</amp-analytics>
EOD;
				echo $ys_ampanalytics;
			} else {
				echo "<script>(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');ga('create', '$ga_tracking_id', 'auto');ga('send', 'pageview');</script>".PHP_EOL;
			}
		}
	}
}
add_action( 'wp_head', 'ys_extras_add_googleanarytics',99 );




// -------------------------------------------
// Facebook OGP出力
// -------------------------------------------
if(!function_exists( 'ys_extras_add_facebook_ogp')) {
	function ys_extras_add_facebook_ogp(){
		global $post;

		if(ys_is_ogp_enable()){

			// OGP出力に必要な情報が揃っていれば出力処理
			$ogp = ys_settings_get_ogp();

			// TOPページ用に初期化(アーカイブページにもシェアボタンを置くようならタイトルとかURLを少し考えたほうが良い)
			$og_type = 'website';
			$og_title = get_bloginfo('name');
			$og_url = get_bloginfo('url');
			$og_image = $ogp['image'];
			$og_description = get_bloginfo('description');

			if(is_single() || is_page()){
				$og_type = 'article';
				$og_title = get_the_title();
				$og_url = get_the_permalink();
				$og_description = ys_template_get_the_custom_excerpt($post->post_content,160);
			}
			//
			echo '<meta property="og:site_name" content="'.get_bloginfo('name').'">'.PHP_EOL;
			echo '<meta property="og:locale"    content="ja_JP">'.PHP_EOL;
			echo '<meta property="fb:app_id"    content="'.$ogp['app_id'].'">'.PHP_EOL;
			echo '<meta property="fb:admins"    content="'.$ogp['admins'].'">'.PHP_EOL;
			echo '<meta property="og:type"    content="'.$og_type.'">'.PHP_EOL;
			echo '<meta property="og:title"    content="'.$og_title.'">'.PHP_EOL;
			echo '<meta property="og:url"    content="'.$og_url.'">'.PHP_EOL;
			echo '<meta property="og:image"    content="'.$og_image.'">'.PHP_EOL;
			echo '<meta property="og:description"    content="'.$og_description.'">'.PHP_EOL;
		}
	}
}
add_action( 'wp_head', 'ys_extras_add_facebook_ogp');




// -------------------------------------------
// Twitter Card 出力
// -------------------------------------------
if(!function_exists( 'ys_extras_add_twitter_card')) {
	function ys_extras_add_twitter_card(){
		global $post;

		$ogp_image = esc_url( get_option('ys_ogp_default_image','') );
		$tw_account = esc_attr( get_option('ys_twittercard_user') );

		if($ogp_image != '' && $tw_account != ''){

			// OGP出力に必要な情報が揃っていれば出力処理
			$ogp = ys_settings_get_ogp();

			// TOPページ用に初期化(アーカイブページにもシェアボタンを置くようならタイトルとかURLを少し考えたほうが良い)
			$og_title = get_bloginfo('name');
			$og_image = $ogp_image;
			$og_description = get_bloginfo('description');

			if(is_single() || is_page()){
				$og_title = get_the_title();
				$og_image = ys_utilities_get_post_thumbnail_url('full',$ogp_image);
				$og_description = ys_template_get_the_custom_excerpt($post->post_content,160);
			}
			// 共通項目
			echo '<meta name="twitter:card" content="summary" />'.PHP_EOL;
			echo '<meta name="twitter:site" content="'.$tw_account.'" />'.PHP_EOL;
			echo '<meta name="twitter:title"    content="'.$og_title.'">'.PHP_EOL;
			echo '<meta name="twitter:image"    content="'.$og_image.'">'.PHP_EOL;
			echo '<meta name="twitter:description"    content="'.$og_description.'">'.PHP_EOL;
		}
	}
}
add_action( 'wp_head', 'ys_extras_add_twitter_card');




// -------------------------------------------
// ampページの存在タグ出力
// -------------------------------------------
if(!function_exists( 'ys_extras_add_amphtml')) {
	function ys_extras_add_amphtml(){

		if(is_single() && ys_is_amp_enable()){
			echo '<link rel="amphtml" href="'. get_the_permalink().'?amp=1">';
		}
	}
}
add_action( 'wp_head', 'ys_extras_add_amphtml' );





/**
 *	テーマカスタマイザーのCSS
 */
if( ! function_exists( 'ys_extras_customize_css' )){
	function ys_extras_customize_css() {
		$css = ys_customizer_inline_css();
		if($css){
			echo '<style type="text/css">'.$css.'</style>';
		}
	}
}
add_action( 'wp_head', 'ys_extras_customize_css',11 );







//------------------------------------------------------------------------------
//
//	wp_footer関連
//
//------------------------------------------------------------------------------

//-----------------------------------------------
//	CSS読み込み用JS出力
//-----------------------------------------------
if( ! function_exists( 'ys_extras_load_css_footer_js' ) ) {
	function ys_extras_load_css_footer_js() {

		// 読み込むCSSをリスト化
		$csslist = array(
										get_template_directory_uri().'/css/ys-style.min.css',
										get_template_directory_uri().'/css/font-awesome.min.css',
										get_stylesheet_directory_uri().'/style.css'
										);

		$csslist = apply_filters('ys_load_css_footer_js',$csslist);

		// 読み込むCSSリスト作成
		$cssarray = '';
		foreach($csslist as $css){
			$cssarray .= '\''.$css.'\',';
		}
		$cssarray = 'list = ['.rtrim($cssarray,',').']';

		// js作成
		$script = <<<EOD
<script type="text/javascript">
	var cb = function() {
		var {$cssarray}
				,l
				,h = document.getElementsByTagName('head')[0];
		for (var i = 0; i < list.length; i++){
			l = document.createElement('link');
			l.rel = 'stylesheet';
			l.href = list[i];
			h.appendChild(l);
		}
	};
	var raf = requestAnimationFrame || mozRequestAnimationFrame || webkitRequestAnimationFrame || msRequestAnimationFrame;
	if (raf) raf(cb);
	else window.addEventListener('load', cb);
</script>
EOD;

	echo $script;
	}
}
add_action( 'wp_footer', 'ys_extras_load_css_footer_js' );




//-----------------------------------------------
//	json-LD出力
//-----------------------------------------------
if(!function_exists( 'ys_extras_the_json_ld')) {
	function ys_extras_the_json_ld(){

		if(ys_is_amp()){
			return ;
		}

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