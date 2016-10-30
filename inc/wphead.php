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

		} elseif(is_single() && get_post_meta(get_the_ID(),'ys_noindex',true)==='1'){
			// 投稿ページでnoindex設定されていればnoindex
			$noindexoutput = true;

		} elseif(is_page() && get_post_meta(get_the_ID(),'ys_noindex',true)==='1'){
			// 固定ページでnoindex設定されていればnoindex
			$noindexoutput = true;

		//  } elseif(ysL03_GetNoindexSetting()) {
			// タグ・日別アーカイブ等のnoindex設定されていればnoindex
		// 	 $noindexoutput = true;

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

		echo '<meta name="robots" content="test">'.PHP_EOL;
	}
}
add_action( 'wp_head', 'ys_wphead_add_googleanarytics',11 );





?>