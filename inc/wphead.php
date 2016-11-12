<?php
//------------------------------------------------------------------------------
//
//	wp_headへの出力追加
//
//------------------------------------------------------------------------------

// -------------------------------------------
// noindexの追加
// -------------------------------------------
if(!function_exists( 'ys_wphead_add_noindex')) {
	function ys_wphead_add_noindex(){

		$noindexoutput = false;

		if(is_404()){
			//  404ページをnoindex
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
			echo "<script>(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');ga('create', '$ga_tracking_id', 'auto');ga('send', 'pageview');</script>".PHP_EOL;
		}
	}
}
add_action( 'wp_head', 'ys_wphead_add_googleanarytics',99 );



// -------------------------------------------
// Facebook OGP出力
// -------------------------------------------
if(!function_exists( 'ys_wphead_add_facebook_ogp')) {
	function ys_wphead_add_facebook_ogp(){
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
				$og_description = get_the_excerpt();
			}
			// 共通項目
			echo '<meta property="og:site_name" content="'.get_bloginfo('name').'">'.PHP_EOL;
			echo '<meta property="og:locale"    content="ja_JP">'.PHP_EOL;
			echo '<meta property="fb:app_id"    content="'.$ogp['app_id'].'">'.PHP_EOL;
			echo '<meta property="fb:admins"    content="'.$ogp['admins'].'">'.PHP_EOL;
			echo '<meta property="fb:type"    content="'.$og_type.'">'.PHP_EOL;
			echo '<meta property="fb:title"    content="'.$og_title.'">'.PHP_EOL;
			echo '<meta property="fb:url"    content="'.$og_url.'">'.PHP_EOL;
			echo '<meta property="fb:image"    content="'.$og_image.'">'.PHP_EOL;
			echo '<meta property="fb:description"    content="'.$og_description.'">'.PHP_EOL;
		}
	}
}
add_action( 'wp_head', 'ys_wphead_add_facebook_ogp');



?>