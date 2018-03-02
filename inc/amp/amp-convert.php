<?php
/**
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */
/**
 * AMPページ用HTML置換関連処理
 */
/**
 *	AMP用正規表現置換処理関数
 */
function ys_amp_preg_replace( $pattern, $replacement, $content ) {
	if( 1 === preg_match( $pattern, $content, $matches ) ){
		$content = preg_replace( $pattern, $replacement, $content );
	}
	return $content;
}
/**
 *	AMP用正規表現置換処理関数
 */
function ys_amp_preg_replace_callback( $pattern, $content, $callback ) {
	if( 1 === preg_match( $pattern, $content, $matches ) ){
		$content = preg_replace_callback(
									$pattern,
									$callback,
									$content
								);
	}
	return $content;
}
/**
 * クエリストリングを削除
 */
function ys_amp_remove_query( $url ) {
	$url = preg_replace( '/\?.*$/', '', $url );
	$url = preg_replace( '/\#.*$/', '', $url );
	return $url;
}

/**
 * imgタグの置換
 */
if( ! function_exists( 'ys_amp_convert_image') ) {
	function ys_amp_convert_image( $img, $layout = 'responsive' ) {
		if( ! ys_is_amp() ) {
			return $img;
		}
		$pattern = '/<img(.+?)?>/i';
		$replacement = '<amp-img layout="' . $layout . '"$1></amp-img>';
		$amp_img = ys_amp_preg_replace( $pattern, $replacement, $img );

		return apply_filters( 'ys_amp_convert_image', $amp_img, $img );
	}
}
/**
 * HTML関連の置換
 */
if( ! function_exists( 'ys_amp_convert_html' ) ) {
	function ys_amp_convert_html( $content ) {
		$content = str_replace( ']]>', ']]&gt;', $content );
		$content = str_replace( '\xc2\xa0' , ' ', $content );
		$content = preg_replace( '/ +target=["][^"]*?["]/i', '', $content );
		$content = preg_replace( '/ +target=[\'][^\']*?[\']/i', '', $content );
		$content = preg_replace( '/ +onclick=["][^"]*?["]/i', '', $content );
		$content = preg_replace( '/ +onclick=[\'][^\']*?[\']/i', '', $content );
		$content = preg_replace( '/<font[^>]+?>/i', '', $content );
		$content = preg_replace( '/<\/font>/i', '', $content );
		return $content;
	}
}
/**
 * oembed関連の置換
 */
if( ! function_exists( 'ys_amp_convert_oembed' ) ) {
	function ys_amp_convert_oembed( $content ) {
		/**
		 * iframeを削除
		 */
		$pattern = '/<p><iframe class="wp-embedded-content".*?>.*?<\/iframe><\/p>/is';
		$replacement = '';
		$content = ys_amp_preg_replace( $pattern, $replacement, $content );
		$pattern = '/<iframe class="wp-embedded-content".*?>.*?<\/iframe>/is';
		$replacement = '';
		$content = ys_amp_preg_replace( $pattern, $replacement, $content );
		return $content;
	}
}
/**
 * iframeの変換
 */
if( ! function_exists( 'ys_amp_convert_iframe' ) ) {
	function ys_amp_convert_iframe( $content, $layout = 'responsive' ) {
		$pattern = '/<iframe([^>]+?)<\/iframe>/i';
		$replacement = '<amp-iframe layout="' . $layout . '"$1</amp-iframe>';
		$content = ys_amp_preg_replace( $pattern, $replacement, $content );
		return $content;
	}
}
/**
 * scriptタグの削除
 */
if( ! function_exists( 'ys_amp_delete_script' ) ) {
	function ys_amp_delete_script( $content ) {
		$pattern = '/<script.+?<\/script>/is';
		$replacement = '';
		$content = ys_amp_preg_replace( $pattern, $replacement, $content );
		return $content;
	}
}
/**
 * styleタグの削除
 */
if( ! function_exists( 'ys_amp_delete_style' ) ) {
	function ys_amp_delete_style( $content ) {
		$replacement = '';
		$pattern = '/style=["][^"].+?"/i';
		$content = ys_amp_preg_replace( $pattern, $replacement, $content );
		$pattern = '/style=[\'][^\'].+?\'/i';
		$content = ys_amp_preg_replace( $pattern, $replacement, $content );
		return $content;
	}
}

/**
 * SNS関連の置換
 */
if( ! function_exists( 'ys_amp_convert_sns') ) {
	function ys_amp_convert_sns( $content ) {
		/**
		 * Twitter
		 */
		$content = ys_amp_convert_twitter( $content );
		/**
		 * Instagram
		 */
		$content = ys_amp_convert_instagram( $content );
		/**
		 * YouTube
		 */
		$content = ys_amp_convert_youtube( $content );
		/**
		 * vine
		 */
		$content = ys_amp_convert_vine( $content );
		/**
		 * Facebook post
		 */
		$content = ys_amp_convert_facebook_post( $content );
		/**
		 * Facebook video
		 */
		$content = ys_amp_convert_facebook_video( $content );
		return $content;
	}
}
/**
 * Twitter埋め込みの置換
 */
if( ! function_exists( 'ys_amp_convert_twitter') ) {
	function ys_amp_convert_twitter( $content ) {
		/**
		 * blockquote埋め込み（通常パターン）
		 */
		$pattern = '/<blockquote class="twitter-tweet".*?>.+?<a href="https:\/\/twitter\.com\/.*?\/status\/(.+?)">.+?<\/blockquote>/is';
		$content = ys_amp_preg_replace_callback( $pattern, $content, 'ys_amp_convert_twitter_callback' );
		return $content;
	}
}
/**
 * Twitter AMP置換用コールバック
 */
if( ! function_exists( 'ys_amp_convert_twitter_callback') ) {
	function ys_amp_convert_twitter_callback( $m ) {
		return '<p><amp-twitter width=486 height=657 layout="responsive" data-tweetid="' . ys_amp_remove_query( $m[1] ) . '"></amp-twitter></p>';
	}
}
/**
 * instagram埋め込みの置換
 */
if( ! function_exists( 'ys_amp_convert_instagram') ) {
	function ys_amp_convert_instagram( $content ) {
		/**
		 * blockquote
		 */
		$pattern = '/<blockquote class="instagram-media".+?"https:\/\/www\.instagram\.com\/p\/(.+?)\/".+?<\/blockquote>/is';
		$replacement = '<amp-instagram layout="responsive" data-shortcode="$1" width="400" height="400" ></amp-instagram>';
		$content = ys_amp_preg_replace( $pattern, $replacement, $content );
		return $content;
	}
}
/**
 * youtube 埋め込みの置換
 */
if( ! function_exists( 'ys_amp_convert_youtube') ) {
	function ys_amp_convert_youtube( $content ) {
		$pattern = '/<iframe[^>]+?src="https:\/\/www\.youtube\.com\/embed\/(.+?)(\?feature=oembed)?".*?><\/iframe>/is';
		$content = ys_amp_preg_replace_callback( $pattern, $content, 'ys_amp_convert_youtube_callback' );
		return $content;
	}
}
/**
 * youtube AMP置換用コールバック
 */
if( ! function_exists( 'ys_amp_convert_youtube_callback') ) {
	function ys_amp_convert_youtube_callback( $m ) {
		return '<amp-youtube layout="responsive" data-videoid="' . ys_amp_remove_query( $m[1] ) . '" width="480" height="270"></amp-youtube>';
	}
}
/**
 * vine埋め込みの置換
 */
if( ! function_exists( 'ys_amp_convert_vine') ) {
	function ys_amp_convert_vine( $content ) {
		$pattern = '/<iframe[^>]+?src="https:\/\/vine\.co\/v\/(.+?)\/embed\/simple".+?><\/iframe>/is';
		$replacement = '<amp-vine data-vineid="$1" width="600" height="600" layout="responsive"></amp-vine>';
		$content = ys_amp_preg_replace( $pattern, $replacement, $content );
		return $content;
	}
}
/**
 * facebook post埋め込みの置換
 */
if( ! function_exists( 'ys_amp_convert_facebook_post') ) {
	function ys_amp_convert_facebook_post( $content ) {
		/**
		 * iframe
		 */
		$pattern = '/<iframe[^>]+?src="https:\/\/www\.facebook\.com\/plugins\/post\.php\?href=(.*?)&.+?".+?><\/iframe>/is';
		$content = ys_amp_preg_replace_callback( $pattern, $content, 'ys_amp_convert_facebook_post_callback' );
		/**
		 * embed
		 */
		$pattern = '/<div[^>]+?class="fb-post"[^>]+?data-href="(.+?)".+?<\/div>/is';
		$replacement = '<amp-facebook width=486 height=657 layout="responsive" data-href="$1"></amp-facebook>';
		$content = ys_amp_preg_replace( $pattern, $replacement, $content );
		return $content;
	}
}
/**
 * facebook post AMP置換用コールバック
 */
if( ! function_exists( 'ys_amp_convert_facebook_post_callback') ) {
	function ys_amp_convert_facebook_post_callback( $m ) {
		return '<amp-facebook width=486 height=657 layout="responsive" data-href="' . urldecode( $m[1] ) . '"></amp-facebook>';
	}
}
/**
 * facebook video埋め込みの置換
 */
if( ! function_exists( 'ys_amp_convert_facebook_video') ) {
	function ys_amp_convert_facebook_video( $content ) {
		$pattern = '/<iframe[^>]+?src="https:\/\/www\.facebook\.com\/plugins\/video\.php\?href=(.*?)&.+?".+?><\/iframe>/is';
		$content = ys_amp_preg_replace_callback( $pattern, $content, 'ys_amp_convert_facebook_video_callback' );
		return $content;
	}
}
/**
 * facebook video AMP置換用コールバック
 */
if( ! function_exists( 'ys_amp_convert_facebook_video_callback') ) {
	function ys_amp_convert_facebook_video_callback( $m ) {
		return '<amp-facebook width=552 height=574 layout="responsive" data-embed-as="video" data-href="' . urldecode( $m[1] ) . '"></amp-facebook>';
	}
}