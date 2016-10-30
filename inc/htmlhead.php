<?php
//------------------------------------------------------------------------------
//
//	head(HTML)関連
//
//------------------------------------------------------------------------------




//-----------------------------------------------
//	html-head要素の出力
//-----------------------------------------------
if(!function_exists( 'ys_htmlhead')) {
	function ys_htmlhead(){

		// ------------------------
		// html,head開始タグ,charset,viewport
		// ------------------------
		if(ys_is_amp()):
			// AMPページ用
			ys_htmlhead_amp();
		else :
			// 通常ページ
			ys_htmlhead_normal();
		endif;
		echo "</head>";
	}
}


//-----------------------------------------------
//	AMPページのHTML-head要素
//-----------------------------------------------
if(!function_exists( 'ys_htmlhead_amp')) {
	function ys_htmlhead_amp(){
		?>
<html <?php language_attributes(); ?>>
<head>
<?php
	}
}




//-----------------------------------------------
//	通常ページのHTML-head要素
//-----------------------------------------------
if(!function_exists( 'ys_htmlhead_normal')) {
	function ys_htmlhead_normal(){
		?>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php
	if ( is_singular() && pings_open( get_queried_object() ) ) :
?>
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php
	endif;
	// wp_head呼び出し
	wp_head();
	}
}
?>