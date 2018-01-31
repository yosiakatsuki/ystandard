<?php
/**
 * AMPフォーマットのheadでタグを出力
 */
function ys_amp_head() {
	do_action( 'ys_amp_head' );
}

/**
 * 通常のフォーマットと同じ関数を使うもの
 */
add_action( 'ys_amp_head', 'ys_the_canonical_tag' );
add_action( 'ys_amp_head', 'ys_the_rel_link' );
add_action( 'ys_amp_head', 'ys_the_noindex' );

/**
 * AMPでのタイトル
 */
if( ! function_exists( 'ys_get_the_amp_document_title' ) ) {
	function ys_get_the_amp_document_title() {
		printf(
			'<title>%s</title>',
			apply_filters( 'ys_get_the_amp_document_title', wp_get_document_title() )
		);
	}
}
add_action( 'ys_amp_head', 'ys_get_the_amp_document_title' );

/**
 * AMP記事で必要になるスクリプト出力
 */
if( ! function_exists( 'ys_the_amp_script' ) ) {
	function ys_the_amp_script() {
		global $post;

		$scripts = '';
		$content = $post->post_content;

		/**
		 * 広告表示
		 */
		if( ys_is_load_amp_ad_script() ){
			$scripts .= '<script async custom-element="amp-ad" src="https://cdn.ampproject.org/v0/amp-ad-0.1.js"></script>' . PHP_EOL;
		}
		/**
		 * twitter
		 */
		$pattern = '/https:\/\/twitter\.com\/.*?\/status\/(.*?)"/i';
		if( 1 === preg_match( $pattern, $content, $matches ) ){
			$scripts .= '<script async custom-element="amp-twitter" src="https://cdn.ampproject.org/v0/amp-twitter-0.1.js"></script>' . PHP_EOL;
		}
		/**
		 * Instagram
		 */
		$pattern = '/https:\/\/www\.instagram\.com\/p\/(.+?)\/"/i';
		if( 1 === preg_match( $pattern, $content, $matches ) ){
			$scripts .= '<script custom-element="amp-instagram" src="https://cdn.ampproject.org/v0/amp-instagram-0.1.js" async></script>' . PHP_EOL;
		}
		/**
		 * Youtube
		 */
		$pattern = '/<iframe.+?src="https:\/\/www\.youtube\.com\/embed\/(.+?)(\?feature=oembed)?".*?><\/iframe>/i';
		if( 1 === preg_match( $pattern, $content, $matches ) ){
			$scripts .= '<script async custom-element="amp-youtube" src="https://cdn.ampproject.org/v0/amp-youtube-0.1.js"></script>' . PHP_EOL;
			$content = preg_replace( $pattern, '', $content );
		}
		/**
		 * Vine
		 */
		$pattern = '/<iframe[^>]+?src="https:\/\/vine\.co\/v\/(.+?)\/embed\/simple".+?><\/iframe>/i';
		if( 1 === preg_match( $pattern, $content, $matches ) ){
			$scripts .= '<script async custom-element="amp-vine" src="https://cdn.ampproject.org/v0/amp-vine-0.1.js"></script>' . PHP_EOL;
			$content = preg_replace( $pattern, '', $content );
		}
		/**
		 * facebook
		 */
		$pattern = '/<iframe[^>]+?src="https:\/\/www\.facebook\.com\/plugins\/(.*?)&.+?".+?><\/iframe>/i';
		if( 1 === preg_match( $pattern, $content, $matches ) ){
			$scripts .= '<script async custom-element="amp-facebook" src="https://cdn.ampproject.org/v0/amp-facebook-0.1.js"></script>' . PHP_EOL;
			$content = preg_replace( $pattern, '', $content );
		}
		/**
		 * iframe
		 */
		$pattern = '/<iframe/i';
		if( 1=== preg_match( $pattern, $content, $matches ) ){
			$scripts .= '<script async custom-element="amp-iframe" src="https://cdn.ampproject.org/v0/amp-iframe-0.1.js"></script>' . PHP_EOL;
		}

		echo apply_filters( 'ys_the_amp_script', $scripts );
	}
}