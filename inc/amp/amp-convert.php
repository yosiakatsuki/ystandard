<?php
/**
 * AMPページ用HTML置換関連処理
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
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
 * iframeの変換
 */
if( ! function_exists( 'ys_amp_convert_iframe' ) ) {
	function ys_amp_convert_iframe( $content ) {
		$pattern = '/<iframe([^>]+?)><\/iframe>/i';
		$content = ys_amp_preg_replace_callback( $pattern, $content, 'ys_amp_convert_iframe_callback' );
		return $content;
	}
}
/**
 * iframe AMP置換用コールバック
 */
if( ! function_exists( 'ys_amp_convert_iframe_callback') ) {
	function ys_amp_convert_iframe_callback( $m ) {
		$content = '<amp-iframe sandbox="allow-scripts allow-same-origin" layout="responsive"' . $m[1] . '></amp-iframe>';
		return ys_amp_iframe_kses( $content );
		
	}
}
/**
 * amp-iframe用タグのサニタイズ
 */
function ys_amp_iframe_kses( $string ) {
	$allowed_html = array( 
			'amp-iframe' => array(
				'class' =>true,
				'id' => true,
				'src' => true,
				'height' => true,
				'width' => true,
				'frameborder' => true,
				'allowfullscreen' => true,
				'allowtransparency' => true,
				'allowpaymentrequest' => true,
				'referrerpolicy' => true,
				'srcdoc' => true,
				'sandbox' => true,
				'layout' => true,
				'resizable' => true
			)
		);
	return wp_kses( $string, $allowed_html );
}
/**
 * scriptタグの削除
 */
if( ! function_exists( 'ys_amp_delete_script' ) ) {
	function ys_amp_delete_script( $content ) {
		/**
		 * wp_autop対策
		 */
		$pattern = '/<p>[^<]*?<script[^<]+?<\/script>[^<]*?<\/p>[^<]*?(\r\n|\r|\n)?/is';
		$replacement = '';
		$content = ys_amp_preg_replace( $pattern, $replacement, $content );
		/**
		 * script 削除
		 */
		$pattern = '/<script[^<]+?<\/script>/is';
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
		$pattern = '/style=["][^"]+?"/i';
		$content = ys_amp_preg_replace( $pattern, $replacement, $content );
		$pattern = '/style=[\'][^\']+?\'/i';
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
		return '<amp-twitter width=486 height=657 layout="responsive" data-tweetid="' . ys_amp_remove_query( $m[1] ) . '"></amp-twitter>';
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
		$pattern = '/<p>[^>]*?<iframe[^>]+?src=["\']https:\/\/www\.youtube\.com\/embed\/(.+?)(\?feature=oembed)?["\'][^>]*?><\/iframe>[^>]*?<\/p>/is';
		$content = ys_amp_preg_replace_callback( $pattern, $content, 'ys_amp_convert_youtube_callback' );
		$pattern = '/<iframe[^>]+?src=["\']https:\/\/www\.youtube\.com\/embed\/(.+?)(\?feature=oembed)?["\'][^>]*?><\/iframe>/is';
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