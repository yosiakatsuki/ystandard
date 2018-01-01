<?php
//------------------------------------------------------------------------------
//
//	AMPページに関する処理
//
//------------------------------------------------------------------------------


// ----------------------------------------------
// amp用フォーマットに変換
// ----------------------------------------------
if(!function_exists( 'ys_amp_convert_amp')) {
	function ys_amp_convert_amp($the_content) {

		$content = $the_content;

		if(ys_is_amp()) {

			// 先に置換したい場合
			$content = apply_filters( 'ys_convert_amp_before', $content );

			// HTMLタグなどの置換
			$content = ys_amp_replace_tag($content);
			// oembed埋め込みの置換
			$content = ys_amp_replace_oembed( $content );
			// sns埋め込みの置換
			$content = ys_amp_replace_sns($content);
			// imgの置換
			$content = ys_amp_replace_image($content);
			// iframeの置換
			$content = ys_amp_replace_iframe($content);

			// scriptの削除
			if(get_option('ys_amp_del_script',0) == 1){
				// $content = ys_amp_delete_script($content);
			}
			// styleの削除
			if(get_option('ys_amp_del_style',0) == 1){
				$content = ys_amp_delete_style($content);
			}

			return apply_filters('ys_convert_amp',$content);
		} else {
			return $the_content;
		}

	}//ys_amp_convert_amp
}
add_filter('the_content','ys_amp_convert_amp',11);




// ----------------------------------------------
// HTMLタグなどの置換
// ----------------------------------------------
if(!function_exists( 'ys_amp_replace_tag')) {
	function ys_amp_replace_tag($content) {

		$content = str_replace( ']]>', ']]&gt;', $content );
		$content = str_replace('\xc2\xa0', ' ', $content);
		$content = preg_replace('/ +target=["][^"]*?["]/i', '', $content);
		$content = preg_replace('/ +target=[\'][^\']*?[\']/i', '', $content);
		$content = preg_replace('/ +onclick=["][^"]*?["]/i', '', $content);
		$content = preg_replace('/ +onclick=[\'][^\']*?[\']/i', '', $content);
		$content = preg_replace('/<font[^>]+?>/i', '', $content);
		$content = preg_replace('/<\/font>/i', '', $content);

		return $content;
	}
}




// ----------------------------------------------
// imgタグの置換
// ----------------------------------------------
if(!function_exists( 'ys_amp_replace_image')) {
	function ys_amp_replace_image($content) {

		$pattern = '/<img(.+?)?>/i';
		$replacement = '<amp-img layout="responsive"$1></amp-img>';
		$content = ys_amp_preg_replace( $pattern, $replacement, $content );

		return $content;
	}
}




// ----------------------------------------------
// iframeタグの置換
// ----------------------------------------------
if(!function_exists( 'ys_amp_replace_iframe')) {
	function ys_amp_replace_iframe($content) {

		$pattern = '/<iframe/i';
		$append = '<amp-iframe layout="responsive"';
		$content = preg_replace($pattern, $append, $content);

		$pattern = '/<\/iframe>/i';
		$append = '</amp-iframe>';
		$content = preg_replace($pattern, $append, $content);

		return $content;
	}
}




/**
 * [if description]
 */
if(!function_exists( 'ys_amp_replace_oembed')) {
	function ys_amp_replace_oembed( $content ) {

		// iframeを削除
		$pattern = '/<p><iframe class="wp-embedded-content".*?>.*?<\/iframe><\/p>/is';
		$replacement = '';
		$content = ys_amp_preg_replace( $pattern, $replacement, $content );

		$pattern = '/<iframe class="wp-embedded-content".*?>.*?<\/iframe>/is';
		$replacement = '';
		$content = ys_amp_preg_replace( $pattern, $replacement, $content );

		return $content;
	}
}




// ----------------------------------------------
// sns埋め込み置換
// ----------------------------------------------
if(!function_exists( 'ys_amp_replace_sns')) {
	function ys_amp_replace_sns($content) {

		// Twitter　>>>>
		$pattern = '/<p>https:\/\/twitter\.com\/.+?\/status\/(.+?)".+?<\/p>/i';
		$replacement = '<p><amp-twitter width=486 height=657 layout="responsive" data-tweetid="$1"></amp-twitter></p>';
		$content = ys_amp_preg_replace( $pattern, $replacement, $content );

		$pattern = '/<blockquote class="twitter-tweet".*?>.+?<a href="https:\/\/twitter\.com\/.*?\/status\/(.*?)">.+?<\/blockquote>/is';
		$replacement = '<p><amp-twitter width=486 height=657 layout="responsive" data-tweetid="$1"></amp-twitter></p>';
		$content = ys_amp_preg_replace( $pattern, $replacement, $content );

		// scriptの処理
		// scriptにwpautopが効くパターン
		$pattern = '/<p><script async src="\/\/platform\.twitter\.com\/widgets\.js" charset="utf-8"><\/script><\/p>/is';
		$replacement = '';
		$content = ys_amp_preg_replace( $pattern, $replacement, $content );

		// scriptにwpautopが効かなかったパターン
		$pattern = '/<script async src="\/\/platform\.twitter\.com\/widgets\.js" charset="utf-8"><\/script>/is';
		$replacement = '';
		$content = ys_amp_preg_replace( $pattern, $replacement, $content );
		// <<<< Twitter

		// Instagram >>>>
		// blockquoteのみパターン
		$pattern = '/<blockquote class="instagram-media".+?"https:\/\/www\.instagram\.com\/p\/(.+?)\/".+?<\/blockquote>/is';
		$replacement = '<amp-instagram layout="responsive" data-shortcode="$1" width="400" height="400" ></amp-instagram>';
		$content = ys_amp_preg_replace( $pattern, $replacement, $content );

		// scriptにwpautopが効くパターン

		$pattern = '/<p><script async defer src="\/\/platform\.instagram\.com\/.+?\/embeds\.js"><\/script><\/p>/is';
		$replacement = '';
		$content = ys_amp_preg_replace( $pattern, $replacement, $content );

		// scriptにwpautopが効かなかったパターン
		$pattern = '/<script async defer src="\/\/platform\.instagram\.com\/.+?\/embeds\.js"><\/script>/is';
		$replacement = '';
		$content = ys_amp_preg_replace( $pattern, $replacement, $content );
		// <<<< Instagram

		// YouTube
		$pattern = '/<iframe[^>]+?src="https:\/\/www\.youtube\.com\/embed\/(.+?)(\?feature=oembed)?".*?><\/iframe>/is';
		if(preg_match($pattern,$content,$matches) === 1){
			$content = preg_replace_callback(
										$pattern
										, function ($m) {
												return '<amp-youtube layout="responsive" data-videoid="'.preg_replace('/\?.*$/','',$m[1]).'" width="480" height="270"></amp-youtube>';
											}
										, $content);
		}

		// vine
		$pattern = '/<iframe[^>]+?src="https:\/\/vine\.co\/v\/(.+?)\/embed\/simple".+?><\/iframe>/is';
		$replacement = '<amp-vine data-vineid="$1" width="600" height="600" layout="responsive"></amp-vine>';
		$content = ys_amp_preg_replace( $pattern, $replacement, $content );

		// Facebook post
		$pattern = '/<iframe[^>]+?src="https:\/\/www\.facebook\.com\/plugins\/post\.php\?href=(.*?)&.+?".+?><\/iframe>/is';
		if(preg_match($pattern,$content,$matches) === 1){
			$content = preg_replace_callback(
										$pattern
										, function ($m) {
												return '<amp-facebook width=486 height=657 layout="responsive" data-href="'.urldecode($m[1]).'"></amp-facebook>';
											}
										, $content);
		}

		// Facebook video
		$pattern = '/<iframe[^>]+?src="https:\/\/www\.facebook\.com\/plugins\/video\.php\?href=(.*?)&.+?".+?><\/iframe>/is';
		if(preg_match($pattern,$content,$matches) === 1){
			$content = preg_replace_callback(
										$pattern
										, function ($m) {
												return '<amp-facebook width=552 height=574 layout="responsive" data-embed-as="video" data-href="'.urldecode($m[1]).'"></amp-facebook>';
											}
										, $content);
		}


		return $content;
	}
}


/**
 *	AMP用正規表現置換処理関数
 */
function ys_amp_preg_replace( $pattern, $replacement, $content ) {

	if( 1 === preg_match( $pattern, $content, $matches ) ){
		$content = preg_replace( $pattern, $replacement, $content );
	}
	return $content;
}


// ----------------------------------------------
// scriptタグの置換
// ----------------------------------------------
if(!function_exists( 'ys_amp_delete_script')) {
	function ys_amp_delete_script($content) {

		$pattern = '/<script.+?<\/script>/is';
		$append = '';
		$content = preg_replace($pattern, $append, $content);

		return $content;
	}
}




// ----------------------------------------------
// styleタグの置換
// ----------------------------------------------
if(!function_exists( 'ys_amp_delete_style')) {
	function ys_amp_delete_style($content) {

		$pattern = '/style=["][^"].+?"/i';
		$append = '';
		$content = preg_replace($pattern, $append, $content);

		$pattern = '/style=[\'][^\'].+?\'/i';
		$append = '';
		$content = preg_replace($pattern, $append, $content);

		return $content;
	}
}


?>