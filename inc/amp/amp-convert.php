<?php
/**
 *
 * AMPページ用HTML置換関連処理
 *
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
		 * oembedパターン
		 */
		$pattern = '/<p>https:\/\/twitter\.com\/.+?\/status\/(.+?)".+?<\/p>/i';
		$replacement = '<p><amp-twitter width=486 height=657 layout="responsive" data-tweetid="$1"></amp-twitter></p>';
		$content = ys_amp_preg_replace( $pattern, $replacement, $content );

		$pattern = '/^https:\/\/twitter\.com\/.+?\/status\/(.+?)".+$/i';
		$replacement = '<p><amp-twitter width=486 height=657 layout="responsive" data-tweetid="$1"></amp-twitter></p>';
		$content = ys_amp_preg_replace( $pattern, $replacement, $content );
		/**
		 * blockquote埋め込み（通常パターン）
		 */
		$pattern = '/<blockquote class="twitter-tweet".*?>.+?<a href="https:\/\/twitter\.com\/.*?\/status\/(.*?)">.+?<\/blockquote>/is';
		$replacement = '<p><amp-twitter width=486 height=657 layout="responsive" data-tweetid="$1"></amp-twitter></p>';
		$content = ys_amp_preg_replace( $pattern, $replacement, $content );
		/**
		 * scriptの処理
		 */
		// scriptにwpautopが効くパターン
		$pattern = '/<p><script async src="\/\/platform\.twitter\.com\/widgets\.js" charset="utf-8"><\/script><\/p>/is';
		$replacement = '';
		$content = ys_amp_preg_replace( $pattern, $replacement, $content );
		// scriptにwpautopが効かなかったパターン
		$pattern = '/<script async src="\/\/platform\.twitter\.com\/widgets\.js" charset="utf-8"><\/script>/is';
		$replacement = '';
		$content = ys_amp_preg_replace( $pattern, $replacement, $content );
		return $content;
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
		/**
		 * scriptの処理
		 */
		// scriptにwpautopが効くパターン
		$pattern = '/<p><script async defer src="\/\/platform\.instagram\.com\/.+?\/embeds\.js"><\/script><\/p>/is';
		$replacement = '';
		$content = ys_amp_preg_replace( $pattern, $replacement, $content );
		// scriptにwpautopが効かなかったパターン
		$pattern = '/<script async defer src="\/\/platform\.instagram\.com\/.+?\/embeds\.js"><\/script>/is';
		$replacement = '';
		$content = ys_amp_preg_replace( $pattern, $replacement, $content );
		return $content;
	}
}
/**
 * youtube埋め込みの置換
 */
if( ! function_exists( 'ys_amp_convert_youtube') ) {
	function ys_amp_convert_youtube( $content ) {
		$pattern = '/<iframe[^>]+?src="https:\/\/www\.youtube\.com\/embed\/(.+?)(\?feature=oembed)?".*?><\/iframe>/is';
		if( 1 === preg_match( $pattern, $content, $matches ) ){
			$content = preg_replace_callback(
										$pattern,
										function($m) {
												return '<amp-youtube layout="responsive" data-videoid="' . preg_replace( '/\?.*$/', '', $m[1] ) . '" width="480" height="270"></amp-youtube>';
										},
										$content
									);
		}
		return $content;
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
		$pattern = '/<iframe[^>]+?src="https:\/\/www\.facebook\.com\/plugins\/post\.php\?href=(.*?)&.+?".+?><\/iframe>/is';
		if( 1 === preg_match( $pattern, $content, $matches ) ){
			$content = preg_replace_callback(
										$pattern,
										function ($m) {
											return '<amp-facebook width=486 height=657 layout="responsive" data-href="' . urldecode( $m[1] ) . '"></amp-facebook>';
										},
										$content
									);
		}
		return $content;
	}
}
/**
 * facebook post埋め込みの置換
 */
if( ! function_exists( 'ys_amp_convert_facebook_post') ) {
	function ys_amp_convert_facebook_post( $content ) {
		$pattern = '/<iframe[^>]+?src="https:\/\/www\.facebook\.com\/plugins\/post\.php\?href=(.*?)&.+?".+?><\/iframe>/is';
		if( 1 === preg_match( $pattern, $content, $matches ) ){
			$content = preg_replace_callback(
										$pattern,
										function ($m) {
											return '<amp-facebook width=486 height=657 layout="responsive" data-href="' . urldecode( $m[1] ) . '"></amp-facebook>';
										},
										$content
									);
		}
		return $content;
	}
}
/**
 * facebook video埋め込みの置換
 */
if( ! function_exists( 'ys_amp_convert_facebook_video') ) {
	function ys_amp_convert_facebook_video( $content ) {
		$pattern = '/<iframe[^>]+?src="https:\/\/www\.facebook\.com\/plugins\/video\.php\?href=(.*?)&.+?".+?><\/iframe>/is';
		if( 1 === preg_match( $pattern, $content, $matches ) ){
			$content = preg_replace_callback(
										$pattern,
										function ($m) {
											return '<amp-facebook width=552 height=574 layout="responsive" data-embed-as="video" data-href="' . urldecode( $m[1] ) . '"></amp-facebook>';
										},
										$content
									);
		}
		return $content;
	}
}