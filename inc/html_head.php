<?php
//------------------------------------------------------------------------------
//
//	head(HTML)関連
//
//------------------------------------------------------------------------------




//-----------------------------------------------
//	html-head要素の出力
//-----------------------------------------------
if(!function_exists( 'ys_html_head')) {
	function ys_html_head(){

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
<?php if(ys_is_ogp_enable()): ?>
<?php 	if(is_single() || is_page()): ?>
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# article: http://ogp.me/ns/article#">
<?php 	else: ?>
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# blog: http://ogp.me/ns/blog#">
<?php 	endif; ?>
<?php else: ?>
<head>
<?php endif; ?>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="referrer" content="origin-when-crossorigin">
<meta name="format-detection" content="telephone=no" />
<meta itemscope id="EntityOfPageid" itemprop="mainEntityOfPage" itemType="https://schema.org/WebPage" itemid="<?php echo the_permalink(); ?>"/>
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