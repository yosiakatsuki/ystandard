<?php
/**
 * AMP用 headタグ関連処理
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

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
function ys_the_amp_document_title() {
	printf(
		'<title>%s</title>',
		apply_filters( 'ys_the_amp_document_title', wp_get_document_title() )
	);
}

add_action( 'ys_amp_head', 'ys_the_amp_document_title' );

/**
 * Google AMP Client ID API を設定する
 * https://support.google.com/analytics/answer/7486764?hl=ja
 */
function ys_the_amp_client_id_api() {
	if ( ys_is_active_amp_client_id_api() ) {
		echo '<meta name="amp-google-client-id-api" content="googleanalytics">' . PHP_EOL;
	}
}

add_action( 'ys_amp_head', 'ys_the_amp_client_id_api' );

/**
 * インラインスタイルのセットと出力
 *
 * @return void
 */
function ys_amp_inline_styles() {
	$scripts = ys_scripts();
	ys_set_enqueue_css();
	$style = $scripts->get_inline_style( true );

	$style = sprintf( '<style amp-custom>%s</style>', $style );
	echo $style . PHP_EOL;
}

add_action( 'ys_amp_head', 'ys_amp_inline_styles', 2 );

if ( ! function_exists( 'ys_the_uc_custom_head_amp' ) ) {
	/**
	 * ユーザーカスタムHEAD出力
	 */
	function ys_the_uc_custom_head_amp() {
		get_template_part( 'user-custom-head-amp' );
	}
}
add_action( 'ys_amp_head', 'ys_the_uc_custom_head_amp', 11 );

if ( ! function_exists( 'ys_the_amp_script' ) ) {
	/**
	 * AMP記事で必要になるスクリプト出力
	 */
	function ys_the_amp_script() {
		global $post;

		$scripts = '';
		$content = apply_filters( 'the_content', $post->post_content );

		/**
		 * 広告表示
		 */
		if ( ys_is_load_amp_ad_script() ) {
			$scripts .= '<script async custom-element="amp-ad" src="https://cdn.ampproject.org/v0/amp-ad-0.1.js"></script>' . PHP_EOL;
		}
		/**
		 * Twitter
		 */
		if ( ys_is_load_amp_twitter_script( $content ) ) {
			$scripts .= '<script async custom-element="amp-twitter" src="https://cdn.ampproject.org/v0/amp-twitter-0.1.js"></script>' . PHP_EOL;
		}
		/**
		 * Instagram
		 */
		if ( ys_is_load_amp_instagram_script( $content ) ) {
			$scripts .= '<script custom-element="amp-instagram" src="https://cdn.ampproject.org/v0/amp-instagram-0.1.js" async></script>' . PHP_EOL;
		}
		/**
		 * Youtube
		 */
		if ( ys_is_load_amp_youtube_script( $content ) ) {
			$scripts .= '<script async custom-element="amp-youtube" src="https://cdn.ampproject.org/v0/amp-youtube-0.1.js"></script>' . PHP_EOL;
		}
		/**
		 * Vine
		 */
		if ( ys_is_load_amp_vine_script( $content ) ) {
			$scripts .= '<script async custom-element="amp-vine" src="https://cdn.ampproject.org/v0/amp-vine-0.1.js"></script>' . PHP_EOL;
		}
		/**
		 * Facebook
		 */
		if ( ys_is_load_amp_facebook_script( $content ) ) {
			$scripts .= '<script async custom-element="amp-facebook" src="https://cdn.ampproject.org/v0/amp-facebook-0.1.js"></script>' . PHP_EOL;
		}
		/**
		 * Iframe
		 */
		if ( ys_is_load_amp_iframe_script( $content ) ) {
			$scripts .= '<script async custom-element="amp-iframe" src="https://cdn.ampproject.org/v0/amp-iframe-0.1.js"></script>' . PHP_EOL;
		}

		echo apply_filters( 'ys_the_amp_script', $scripts );
	}
}
add_action( 'ys_amp_head', 'ys_the_amp_script' );


/**
 * AMPページに広告用scriptをロードするか
 */
function ys_is_load_amp_ad_script() {
	$result = false;
	if ( '' !== ys_get_option( 'ys_amp_advertisement_before_content' ) ) {
		$result = true;
	}
	if ( '' !== ys_get_option( 'ys_amp_advertisement_replace_more' ) ) {
		$result = true;
	}
	if ( '' !== ys_get_option( 'ys_amp_advertisement_under_content' ) ) {
		$result = true;
	}

	return apply_filters( 'ys_is_load_amp_ad_script', $result );
}

/**
 * Twitter用スクリプトを読み込むか
 *
 * @param string $content 投稿内容.
 *
 * @return bool
 */
function ys_is_load_amp_twitter_script( &$content ) {
	$pattern = '/amp-twitter/i';
	if ( 1 === preg_match( $pattern, $content, $matches ) ) {
		return true;
	}
	$pattern = '/https:\/\/twitter\.com\/.*?\/status\/.+/i';
	if ( 1 === preg_match( $pattern, $content, $matches ) ) {
		return true;
	}

	return false;
}

/**
 * Instagram用スクリプトを読み込むか
 *
 * @param string $content 投稿内容.
 *
 * @return bool
 */
function ys_is_load_amp_instagram_script( &$content ) {
	$pattern = '/amp-instagram/i';
	if ( 1 === preg_match( $pattern, $content, $matches ) ) {
		return true;
	}
	$pattern = '/https:\/\/www\.instagram\.com\/p\/.+/i';
	if ( 1 === preg_match( $pattern, $content, $matches ) ) {
		return true;
	}

	return false;
}

/**
 * Youtube用スクリプトを読み込むか
 *
 * @param string $content 投稿内容.
 *
 * @return bool
 */
function ys_is_load_amp_youtube_script( &$content ) {
	$pattern = '/amp-youtube/i';
	if ( 1 === preg_match( $pattern, $content, $matches ) ) {
		return true;
	}
	$pattern = '/<iframe.+?src="https:\/\/www\.youtube\.com\/embed\/(.+?)(\?feature=oembed)?".*?><\/iframe>/i';
	if ( 1 === preg_match( $pattern, $content, $matches ) ) {
		$content = preg_replace( $pattern, '', $content );

		return true;
	}

	return false;
}

/**
 * Vine用スクリプトを読み込むか
 *
 * @param string $content 投稿内容.
 *
 * @return bool
 */
function ys_is_load_amp_vine_script( &$content ) {
	$pattern = '/amp-vine/i';
	if ( 1 === preg_match( $pattern, $content, $matches ) ) {
		return true;
	}
	$pattern = '/<iframe[^>]+?src="https:\/\/vine\.co\/v\/(.+?)\/embed\/simple".+?><\/iframe>/i';
	if ( 1 === preg_match( $pattern, $content, $matches ) ) {
		$content = preg_replace( $pattern, '', $content );

		return true;
	}

	return false;
}

/**
 * Facebook用スクリプトを読み込むか
 *
 * @param string $content 投稿内容.
 *
 * @return bool
 */
function ys_is_load_amp_facebook_script( &$content ) {
	$pattern = '/amp-facebook/i';
	if ( 1 === preg_match( $pattern, $content, $matches ) ) {
		return true;
	}
	$pattern = '/<iframe[^>]+?src="https:\/\/www\.facebook\.com\/plugins\/(.*?)&.+?".+?><\/iframe>/i';
	if ( 1 === preg_match( $pattern, $content, $matches ) ) {
		$content = preg_replace( $pattern, '', $content );

		return true;
	}
	$pattern = '/<div[^>]+?class="fb-post"[^>]+?data-href="(.+?)".+?<\/div>/is';
	if ( 1 === preg_match( $pattern, $content, $matches ) ) {
		$content = preg_replace( $pattern, '', $content );

		return true;
	}

	return false;
}

/**
 * Iframe用スクリプトを読み込むか
 *
 * @param string $content 投稿内容.
 *
 * @return bool
 */
function ys_is_load_amp_iframe_script( &$content ) {
	$pattern = '/amp-iframe/i';
	if ( 1 === preg_match( $pattern, $content, $matches ) ) {
		return true;
	}
	$pattern = '/<iframe/i';
	if ( 1 === preg_match( $pattern, $content, $matches ) ) {
		return true;
	}

	return false;
}