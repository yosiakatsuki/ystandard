<?php
//------------------------------------------------------------------------------
//
//	wp_headへの出力追加
//
//------------------------------------------------------------------------------


// -------------------------------------------
// canonicalタグ出力
// -------------------------------------------
if(!function_exists( 'ys_wphead_add_canonical')) {
	function ys_wphead_add_canonical(){

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
add_action( 'wp_head', 'ys_wphead_add_canonical' );




// -------------------------------------------
// next,prevタグ出力
// -------------------------------------------
if(!function_exists( 'ys_wphead_adjacent_posts_rel_link_wp_head')) {
	function ys_wphead_adjacent_posts_rel_link_wp_head(){


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
add_action( 'wp_head', 'ys_wphead_adjacent_posts_rel_link_wp_head' );




// -------------------------------------------
// noindexの追加
// -------------------------------------------
if(!function_exists( 'ys_wphead_add_noindex')) {
	function ys_wphead_add_noindex(){

		$noindexoutput = false;

		if(is_404()){
			//  404ページをnoindex
			$noindexoutput = true;

		} elseif(is_search()) {
			// 検索結果をnoindex
			$noindexoutput = true;

		} elseif(is_category() && get_option('ys_archive_noindex_category',0) == 1){
			// カテゴリーページのnoindex設定がされていればnoindex
			$noindexoutput = true;

		} elseif(is_tag() && get_option('ys_archive_noindex_tag',1) == 1){
			// カテゴリーページのnoindex設定がされていればnoindex
			$noindexoutput = true;

		} elseif(is_author() && get_option('ys_archive_noindex_author',1) == 1){
			// カテゴリーページのnoindex設定がされていればnoindex
			$noindexoutput = true;

		} elseif(is_date() && get_option('ys_archive_noindex_date',1) == 1){
			// カテゴリーページのnoindex設定がされていればnoindex
			$noindexoutput = true;

		} elseif(is_single() || is_page()){
			if(get_post_meta(get_the_ID(),'ys_noindex',true)==='1'){
				// 投稿・固定ページでnoindex設定されていればnoindex
				$noindexoutput = true;
			}

		} else {
			// その他特別な設定がされていればnoindex
			$noindexoutput = ys_wphead_custom_noindex();
		}

		// noindex出力
		if($noindexoutput){
			echo '<meta name="robots" content="noindex,follow">'.PHP_EOL;
		}
	}
}
add_action( 'wp_head', 'ys_wphead_add_noindex' );




// -------------------------------------------
// noindex条件追加用関数
// -------------------------------------------
if(!function_exists( 'ys_wphead_custom_noindex') ) {
	function ys_wphead_custom_noindex() {
		//自分で書き込むまではとりあえずFalse
		return false;
	}
}




// -------------------------------------------
// google analyticsタグ出力
// -------------------------------------------
if(!function_exists( 'ys_wphead_add_googleanarytics')) {
	function ys_wphead_add_googleanarytics(){
		$ga_tracking_id = trim(get_option('ys_ga_tracking_id',''));

		if($ga_tracking_id !== ''){
			if(ys_is_amp()){
				$ampanalytics = <<<EOD
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
				echo $ampanalytics;
			} else {
				echo "<script>(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');ga('create', '$ga_tracking_id', 'auto');ga('send', 'pageview');</script>".PHP_EOL;
			}
		}
	}
}
add_action( 'wp_head', 'ys_wphead_add_googleanarytics',99 );



// -------------------------------------------
// Facebook OGP出力
// -------------------------------------------
if(!function_exists( 'ys_wphead_add_facebook_ogp')) {
	function ys_wphead_add_facebook_ogp(){
		global $post;

		if(ys_is_ogp_enable()){

			// OGP出力に必要な情報が揃っていれば出力処理
			$ogp = ys_option_get_ogp();

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
				$og_description = ys_entry_get_the_custom_excerpt($post->post_content,160);
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
add_action( 'wp_head', 'ys_wphead_add_facebook_ogp');




// -------------------------------------------
// Twitter Card 出力
// -------------------------------------------
if(!function_exists( 'ys_wphead_add_twitter_card')) {
	function ys_wphead_add_twitter_card(){
		global $post;

		$ogp_image = esc_url( get_option('ys_ogp_default_image','') );
		$tw_account = esc_attr( get_option('ys_twittercard_user') );

		if($ogp_image != '' && $tw_account != ''){

			// OGP出力に必要な情報が揃っていれば出力処理
			$ogp = ys_option_get_ogp();

			// TOPページ用に初期化(アーカイブページにもシェアボタンを置くようならタイトルとかURLを少し考えたほうが良い)
			$og_title = get_bloginfo('name');
			$og_image = $ogp_image;
			$og_description = get_bloginfo('description');

			if(is_single() || is_page()){
				$og_title = get_the_title();
				$og_image = ys_image_get_post_thumbnail_url(0,'full',$ogp_image);
				$og_description = ys_entry_get_the_custom_excerpt($post->post_content,160);
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
add_action( 'wp_head', 'ys_wphead_add_twitter_card');




// -------------------------------------------
// ampページの存在タグ出力
// -------------------------------------------
if(!function_exists( 'ys_wphead_add_amphtml')) {
	function ys_wphead_add_amphtml(){

		if(is_single() && ys_is_amp_enable()){
			echo '<link rel="amphtml" href="'. get_the_permalink().'?amp=1">';
		}
	}
}
add_action( 'wp_head', 'ys_wphead_add_amphtml' );


?>