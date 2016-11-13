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
<html ⚡>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="referrer" content="origin-when-crossorigin">
<meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
<meta name="format-detection" content="telephone=no" />
<meta itemscope id="EntityOfPageid" itemprop="mainEntityOfPage" itemType="https://schema.org/WebPage" itemid="<?php echo the_permalink(); ?>"/>
<style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>
<script async custom-element="amp-iframe" src="https://cdn.ampproject.org/v0/amp-iframe-0.1.js"></script>
<script async custom-element="amp-social-share" src="https://cdn.ampproject.org/v0/amp-social-share-0.1.js"></script>
<script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>
<script async custom-element="amp-ad" src="https://cdn.ampproject.org/v0/amp-ad-0.1.js"></script>
<script async src="https://cdn.ampproject.org/v0.js"></script>
<style amp-custom>
<?php
if(locate_template('css/ys-style.min.css') !== '') {
	$css = file_get_contents(STYLESHEETPATH.'/css/ys-style.min.css');
} else {
	$css = file_get_contents(STYLESHEETPATH.'/css/ys-style.css');
}
echo str_replace('@charset "UTF-8";','',$css);
?>
</style>
<?php
	// noindex
	ys_wphead_add_noindex();
	// canonical
	ys_wphead_add_canonical();
	// next,prev
	ys_wphead_adjacent_posts_rel_link_wp_head();
	// タイトル
	echo '<title>'.wp_get_document_title().'</title>';
?>
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
<meta name="viewport" content="width=device-width, initial-scale=1.0,user-scalable=no">
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