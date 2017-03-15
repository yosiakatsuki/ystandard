<?php
//------------------------------------------------------------------------------
//
//	AMPページに関する処理
//
//------------------------------------------------------------------------------


//-----------------------------------------------
//	AMPページのHTML-head要素
//-----------------------------------------------
if(!function_exists( 'ys_amp_the_head_amp')) {
	function ys_amp_the_head_amp(){
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
<script async src="https://cdn.ampproject.org/v0.js"></script>
<script async custom-element="amp-social-share" src="https://cdn.ampproject.org/v0/amp-social-share-0.1.js"></script>
<script async custom-element="amp-sidebar" src="https://cdn.ampproject.org/v0/amp-sidebar-0.1.js"></script>
<script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>
<?php ys_amp_the_amp_script(); ?>
<?php
// インラインCSS読み込み
	$args =array(
		TEMPLATEPATH.'/css/ys-firstview.min.css',
		TEMPLATEPATH.'/css/ys-style.min.css',
		ys_customizer_inline_css(),
		locate_template('style-firstview.css'),
		locate_template('style.css')
	);
	ys_template_the_inline_css($args,true);
?>
<?php
	// noindex
	ys_extras_add_noindex();
	// canonical
	ys_extras_add_canonical();
	// next,prev
	ys_extras_adjacent_posts_rel_link_wp_head();
	// タイトル
	echo '<title>'.wp_get_document_title().'</title>';
?>
<?php
	}
}




// ----------------------------------------------
// amp用フォーマットに変換
// ----------------------------------------------
if(!function_exists( 'ys_amp_the_amp_script')) {
	function ys_amp_the_amp_script() {
		global $post;

		$html = '';
		$content = $post->post_content;

		// ad
		if(ys_get_setting('ys_amp_advertisement_under_title') != ''
				|| ys_get_setting('ys_amp_advertisement_replace_more') != ''
				|| ys_get_setting('ys_amp_advertisement_under_content') != '' ){

			$html .= '<script async custom-element="amp-ad" src="https://cdn.ampproject.org/v0/amp-ad-0.1.js"></script>'.PHP_EOL;
		}

		// Twitter
		$pattern = '/https:\/\/twitter\.com\/.*?\/status\/(.*?)"/i';
		if(preg_match($pattern,$content,$matches) === 1){
			$html .= '<script async custom-element="amp-twitter" src="https://cdn.ampproject.org/v0/amp-twitter-0.1.js"></script>'.PHP_EOL;
		}

		// Instagram
		$pattern = '/https:\/\/www\.instagram\.com\/p\/(.+?)\/"/i';
		if(preg_match($pattern,$content,$matches) === 1){
			$html .= '<script custom-element="amp-instagram" src="https://cdn.ampproject.org/v0/amp-instagram-0.1.js" async></script>'.PHP_EOL;
		}

		// YouTube
		$pattern = '/<iframe.+?src="https:\/\/www\.youtube\.com\/embed\/(.+?)(\?feature=oembed)?".*?><\/iframe>/i';
		if(preg_match($pattern,$content,$matches) === 1){
			$html .= '<script async custom-element="amp-youtube" src="https://cdn.ampproject.org/v0/amp-youtube-0.1.js"></script>'.PHP_EOL;
			$content = preg_replace($pattern, '', $content);
		}

		// vine
		$pattern = '/<iframe[^>]+?src="https:\/\/vine\.co\/v\/(.+?)\/embed\/simple".+?><\/iframe>/i';
		if(preg_match($pattern,$content,$matches) === 1){
			$html .= '<script async custom-element="amp-vine" src="https://cdn.ampproject.org/v0/amp-vine-0.1.js"></script>'.PHP_EOL;
			$content = preg_replace($pattern, '', $content);
		}

		// Facebook
		$pattern = '/<iframe[^>]+?src="https:\/\/www\.facebook\.com\/plugins\/(.*?)&.+?".+?><\/iframe>/i';
		if(preg_match($pattern,$content,$matches) === 1){
			$html .= '<script async custom-element="amp-facebook" src="https://cdn.ampproject.org/v0/amp-facebook-0.1.js"></script>'.PHP_EOL;
			$content = preg_replace($pattern, '', $content);
		}

		// iframe
		$pattern = '/<iframe/i';
		if(preg_match($pattern,$content,$matches) === 1){
			$html .= '<script async custom-element="amp-iframe" src="https://cdn.ampproject.org/v0/amp-iframe-0.1.js"></script>'.PHP_EOL;
		}

		echo apply_filters('ys_the_amp_script',$html);
	}
}



// ----------------------------------------------
// amp用フォーマットに変換
// ----------------------------------------------
if(!function_exists( 'ys_amp_convert_amp')) {
	function ys_amp_convert_amp($the_content) {

		$content = $the_content;

		if(ys_is_amp()) {

			// HTMLタグなどの置換
			$content = ys_amp_replace_tag($content);
			// sns埋め込みの置換
			$content = ys_amp_replace_sns($content);
			// imgの置換
			$content = ys_amp_replace_image($content);
			// iframeの置換
			$content = ys_amp_replace_iframe($content);

			// scriptの削除
			if(get_option('ys_amp_del_script',0) == 1){
				$content = ys_amp_delete_script($content);
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

		$pattern = '/<img/i';
		$append = '<amp-img layout="responsive"';
		$content = preg_replace($pattern, $append, $content);

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




// ----------------------------------------------
// sns埋め込み置換
// ----------------------------------------------
if(!function_exists( 'ys_amp_replace_sns')) {
	function ys_amp_replace_sns($content) {

		// Twitter　>>>>
		$pattern = '/<p>https:\/\/twitter\.com\/.*?\/status\/(.*?)".*?<\/p>/i';
		$append = '<p><amp-twitter width=486 height=657 layout="responsive" data-tweetid="$1"></amp-twitter></p>';
		if(preg_match($pattern,$content,$matches) === 1){
			$content = preg_replace($pattern, $append, $content);
		}

		// scriptにwpautopが効くパターン

		$pattern = '/<blockquote class="twitter-tweet".*?>.*?<a href="https:\/\/twitter\.com\/.*?\/status\/(.*?)">.*?<\/blockquote>.*?<script async src="\/\/platform\.twitter\.com\/widgets\.js" charset="utf-8"><\/script><\/p>/is';
		$append = '<p><amp-twitter width=486 height=657 layout="responsive" data-tweetid="$1"></amp-twitter></p>';
		if(preg_match($pattern,$content,$matches) === 1){
			$content = preg_replace($pattern, $append, $content);
		}

		// scriptにwpautopが効かなかったパターン
		$pattern = '/<blockquote class="twitter-tweet".*?>.+?<a href="https:\/\/twitter\.com\/.*?\/status\/(.*?)">.+?<\/blockquote>.*?<script async src="\/\/platform\.twitter\.com\/widgets\.js" charset="utf-8"><\/script>/is';
		$append = '<p><amp-twitter width=486 height=657 layout="responsive" data-tweetid="$1"></amp-twitter></p>';
		if(preg_match($pattern,$content,$matches) === 1){
			$content = preg_replace($pattern, $append, $content);
		}

		// blockquoteのみパターン
		$pattern = '/<blockquote class="twitter-tweet".*?>.*?<a href="https:\/\/twitter\.com\/.*?\/status\/(.*?)".*?<\/blockquote>/is';
		$append = '<p><amp-twitter width=486 height=657 layout="responsive" data-tweetid="$1"></amp-twitter></p>';
		if(preg_match($pattern,$content,$matches) === 1){
			$content = preg_replace($pattern, $append, $content);
		}
		// <<<< Twitter

		// Instagram >>>>
		// scriptにwpautopが効くパターン
		$pattern = '/<blockquote class="instagram-media".+?"https:\/\/www\.instagram\.com\/p\/(.+?)\/".+?<\/blockquote>.*?<script async defer src="\/\/platform\.instagram\.com\/.+?\/embeds\.js"><\/script><\/p>/is';
		$append = '<amp-instagram layout="responsive" data-shortcode="$1" width="400" height="400" ></amp-instagram>';
		if(preg_match($pattern,$content,$matches) === 1){
			$content = preg_replace($pattern, $append, $content);
		}

		// scriptにwpautopが効かなかったパターン
		$pattern = '/<blockquote class="instagram-media".+?"https:\/\/www\.instagram\.com\/p\/(.+?)\/".+?<\/blockquote>.*?<script async defer src="\/\/platform\.instagram\.com\/.+?\/embeds\.js"><\/script>/is';
		$append = '<amp-instagram layout="responsive" data-shortcode="$1" width="400" height="400" ></amp-instagram>';
		if(preg_match($pattern,$content,$matches) === 1){
			$content = preg_replace($pattern, $append, $content);
		}

		// blockquoteのみパターン
		$pattern = '/<blockquote class="instagram-media".+?"https:\/\/www\.instagram\.com\/p\/(.+?)\/".+?<\/blockquote>/is';
		$append = '<amp-instagram layout="responsive" data-shortcode="$1" width="400" height="400" ></amp-instagram>';
		if(preg_match($pattern,$content,$matches) === 1){
			$content = preg_replace($pattern, $append, $content);
		}
		// <<<< Instagram

		// YouTube
		$pattern = '/<iframe[^>]+?src="https:\/\/www\.youtube\.com\/embed\/(.+?)(\?feature=oembed)?".*?><\/iframe>/is';
		$append = '<amp-youtube layout="responsive" data-videoid="$1" width="480" height="270"></amp-youtube>';
		if(preg_match($pattern,$content,$matches) === 1){
			$content = preg_replace($pattern, $append, $content);
		}

		// vine
		$pattern = '/<iframe[^>]+?src="https:\/\/vine\.co\/v\/(.+?)\/embed\/simple".+?><\/iframe>/is';
		$append = '<amp-vine data-vineid="$1" width="600" height="600" layout="responsive"></amp-vine>';
		if(preg_match($pattern,$content,$matches) === 1){
			$content = preg_replace($pattern, $append, $content);
		}

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