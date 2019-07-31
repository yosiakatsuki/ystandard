<?php
/**
 * AMPページ用HTML置換関連処理
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * AMP用正規表現置換処理関数
 *
 * @param string $pattern     パターン.
 * @param string $replacement 置換文字列.
 * @param string $content     投稿内容.
 *
 * @return string
 */
function ys_amp_preg_replace( $pattern, $replacement, $content ) {
	if ( 1 === preg_match( $pattern, $content, $matches ) ) {
		$content = preg_replace( $pattern, $replacement, $content );
	}

	return $content;
}

/**
 * AMP用正規表現置換処理関数
 *
 * @param string $pattern  パターン.
 * @param string $content  投稿内容.
 * @param string $callback 関数名.
 *
 * @return string
 */
function ys_amp_preg_replace_callback( $pattern, $content, $callback ) {
	if ( 1 === preg_match( $pattern, $content, $matches ) ) {
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
 *
 * @param string $url url.
 *
 * @return string
 */
function ys_amp_remove_query( $url ) {
	$url = preg_replace( '/\?.*$/', '', $url );
	$url = preg_replace( '/\#.*$/', '', $url );

	return $url;
}

/**
 * タグの置換 : img
 *
 * @param string $content コンテンツ.
 * @param string $layout  layout.
 *
 * @return string
 */
function ys_amp_convert_image( $content, $layout = 'responsive' ) {
	if ( ! ys_is_amp() ) {
		return $content;
	}
	$amp_content = $content;
	/**
	 * 変換対象の画像を検索
	 */
	$pattern = '/<img(.+?)\/?>/i';
	$result  = preg_match_all( $pattern, $amp_content, $matches );
	if ( false !== $result && 0 < $result ) {
		foreach ( $matches[0] as $key => $img_tag ) {
			/**
			 * 画像タグ(img)の置換
			 */
			$amp_content = str_replace(
				$img_tag,
				ys_amp_get_amp_image_tag( $img_tag, $layout ),
				$amp_content
			);
		}
	}

	return apply_filters(
		'ys_amp_convert_image',
		$amp_content,
		$content,
		$layout
	);
}

/**
 * AMP用画像タグの取得
 *
 * @param string $img    imgタグ.
 * @param string $layout レイアウト.
 *
 * @return string
 */
function ys_amp_get_amp_image_tag( $img, $layout = 'responsive' ) {
	if ( ! ys_is_amp() ) {
		return $img;
	}
	$format = '<amp-img %s></amp-img>';
	if ( 1 === preg_match( '/<img(.+?)\/?>/i', $img, $m ) ) {
		/**
		 * 変換後の属性を取得
		 */
		$attr = ys_amp_get_amp_image_attr( $m[1], $layout );
		/**
		 * <amp-img>タグ作成
		 */
		$img = sprintf( $format, $attr );
	}

	return apply_filters( 'ys_amp_get_amp_image_tag', $img, $layout );
}

/**
 * AMP画像の属性値取得
 *
 * @param string $attr   属性.
 * @param string $layout レイアウト.
 *
 * @return string
 */
function ys_amp_get_amp_image_attr( $attr, $layout = 'responsive' ) {
	$src = '';
	/**
	 * 画像URLを取得
	 */
	if ( 1 === preg_match( '/src="(.+?)"/i', $attr, $m ) ) {
		$src = $m[1];
	}
	/**
	 * レイアウトの指定を作成
	 */
	$layout = apply_filters( 'ys_amp_get_amp_image_attr_layout', $layout, $src );
	if ( $layout ) {
		$layout = 'layout="' . $layout . '"';
	}

	/**
	 * <amp-img>の属性部分を作成
	 */
	return apply_filters(
		'ys_amp_get_amp_image_attr',
		$layout . $attr,
		$src
	);
}

/**
 * HTML関連の置換
 *
 * @param string $content 投稿データ.
 *
 * @return string
 */
function ys_amp_convert_html( $content ) {
	$content = str_replace( ']]>', ']]&gt;', $content );
	$content = str_replace( '\xc2\xa0', ' ', $content );
	$content = preg_replace( '/ +target=["][^"]*?["]/i', '', $content );
	$content = preg_replace( '/ +target=[\'][^\']*?[\']/i', '', $content );
	$content = preg_replace( '/ +onclick=["][^"]*?["]/i', '', $content );
	$content = preg_replace( '/ +onclick=[\'][^\']*?[\']/i', '', $content );
	$content = preg_replace( '/<font[^>]+?>/i', '', $content );
	$content = preg_replace( '/<\/font>/i', '', $content );

	return $content;
}

/**
 * タグの変換 : iframe
 *
 * @param string $content 投稿内容.
 *
 * @return string
 */
function ys_amp_convert_iframe( $content ) {
	$pattern = '/<iframe([^>]+?)><\/iframe>/i';
	$content = ys_amp_preg_replace_callback( $pattern, $content, 'ys_amp_convert_iframe_callback' );

	return $content;
}

/**
 * AMP置換用コールバック : iframe
 *
 * @param array $m マッチ.
 *
 * @return string
 */
function ys_amp_convert_iframe_callback( $m ) {
	$content = '<amp-iframe sandbox="allow-scripts allow-same-origin" layout="responsive"' . $m[1] . '></amp-iframe>';

	return ys_amp_iframe_kses( $content );
}

/**
 * タグのサニタイズ : amp-iframe
 *
 * @param string $string string.
 *
 * @return string
 */
function ys_amp_iframe_kses( $string ) {
	$allowed_html = array(
		'amp-iframe' => array(
			'class'               => true,
			'id'                  => true,
			'src'                 => true,
			'height'              => true,
			'width'               => true,
			'frameborder'         => true,
			'allowfullscreen'     => true,
			'allowtransparency'   => true,
			'allowpaymentrequest' => true,
			'referrerpolicy'      => true,
			'srcdoc'              => true,
			'sandbox'             => true,
			'layout'              => true,
			'resizable'           => true,
		),
	);

	return wp_kses( $string, $allowed_html );
}

/**
 * タグの削除 : scripts
 *
 * @param string $content 投稿内容.
 *
 * @return string
 */
function ys_amp_delete_script( $content ) {
	/**
	 * 対策 : wp_autop
	 */
	$pattern     = '/<p>[^<]*?<script[^<]+?<\/script>[^<]*?<\/p>[^<]*?(\r\n|\r|\n)?/is';
	$replacement = '';
	$content     = ys_amp_preg_replace( $pattern, $replacement, $content );
	/**
	 * Script削除
	 */
	$pattern     = '/<script[^<]+?<\/script>/is';
	$replacement = '';
	$content     = ys_amp_preg_replace( $pattern, $replacement, $content );

	return $content;
}

/**
 * Styleタグの削除
 *
 * @param string $content 投稿内容.
 *
 * @return string
 */
function ys_amp_delete_style( $content ) {
	$replacement = '';
	$pattern     = '/style=["][^"]+?"/i';
	$content     = ys_amp_preg_replace( $pattern, $replacement, $content );
	$pattern     = '/style=[\'][^\']+?\'/i';
	$content     = ys_amp_preg_replace( $pattern, $replacement, $content );

	return $content;
}

/**
 * SNS関連の置換
 *
 * @param string $content 投稿内容.
 *
 * @return string
 */
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
	 * Facebook post
	 */
	$content = ys_amp_convert_facebook_post( $content );
	/**
	 * Facebook video
	 */
	$content = ys_amp_convert_facebook_video( $content );

	return $content;
}

/**
 * Twitter埋め込みの置換
 *
 * @param string $content 記事データ.
 *
 * @return string
 */
function ys_amp_convert_twitter( $content ) {
	/**
	 * 埋め込み（通常パターン）: blockquote
	 */
	$pattern = '/<blockquote class="twitter-tweet".*?>.+?<a href="https:\/\/twitter\.com\/.*?\/status\/(.+?)">.+?<\/blockquote>/is';
	$content = ys_amp_preg_replace_callback( $pattern, $content, 'ys_amp_convert_twitter_callback' );

	return $content;
}

/**
 * Twitter AMP置換用コールバック
 *
 * @param array $m マッチした結果.
 *
 * @return string
 */
function ys_amp_convert_twitter_callback( $m ) {
	return '<amp-twitter width=486 height=657 layout="responsive" data-tweetid="' . ys_amp_remove_query( $m[1] ) . '"></amp-twitter>';
}

/**
 * Instagram埋め込みの置換
 *
 * @param string $content 投稿内容.
 *
 * @return string
 */
function ys_amp_convert_instagram( $content ) {
	/**
	 * Blockquote
	 */
	$pattern     = '/<blockquote class="instagram-media".+?"https:\/\/www\.instagram\.com\/p\/(.+?)\/.*?".+?<\/blockquote>/is';
	$replacement = '<amp-instagram layout="responsive" data-shortcode="$1" width="400" height="400" ></amp-instagram>';
	$content     = ys_amp_preg_replace( $pattern, $replacement, $content );

	return $content;
}

/**
 * Youtube 埋め込みの置換
 *
 * @param string $content 投稿内容.
 *
 * @return string
 */
function ys_amp_convert_youtube( $content ) {
	$pattern = '/<p>[^>]*?<iframe[^>]+?src=["\']https:\/\/www\.youtube\.com\/embed\/(.+?)(\?feature=oembed)?["\'][^>]*?><\/iframe>[^>]*?<\/p>/is';
	$content = ys_amp_preg_replace_callback( $pattern, $content, 'ys_amp_convert_youtube_callback' );
	$pattern = '/<iframe[^>]+?src=["\']https:\/\/www\.youtube\.com\/embed\/(.+?)(\?feature=oembed)?["\'][^>]*?><\/iframe>/is';
	$content = ys_amp_preg_replace_callback( $pattern, $content, 'ys_amp_convert_youtube_callback' );

	return $content;
}

/**
 * Youtube AMP置換用コールバック
 *
 * @param array $m マッチした結果.
 *
 * @return string
 */
function ys_amp_convert_youtube_callback( $m ) {
	return '<amp-youtube layout="responsive" data-videoid="' . ys_amp_remove_query( $m[1] ) . '" width="480" height="270"></amp-youtube>';
}

/**
 * Facebook post埋め込みの置換
 *
 * @param string $content 投稿内容.
 *
 * @return string
 */
function ys_amp_convert_facebook_post( $content ) {
	/**
	 * 置換 : iframe
	 */
	$pattern = '/<iframe[^>]+?src="https:\/\/www\.facebook\.com\/plugins\/post\.php\?href=(.*?)&.+?".+?><\/iframe>/is';
	$content = ys_amp_preg_replace_callback( $pattern, $content, 'ys_amp_convert_facebook_post_callback' );
	/**
	 * 置換 : embed
	 */
	$pattern     = '/<div[^>]+?class="fb-post"[^>]+?data-href="(.+?)".+?<\/div>/is';
	$replacement = '<amp-facebook width=486 height=657 layout="responsive" data-href="$1"></amp-facebook>';
	$content     = ys_amp_preg_replace( $pattern, $replacement, $content );

	return $content;
}

/**
 * Facebook post AMP置換用コールバック
 *
 * @param array $m マッチした結果.
 *
 * @return string
 */
function ys_amp_convert_facebook_post_callback( $m ) {
	return '<amp-facebook width=486 height=657 layout="responsive" data-href="' . urldecode( $m[1] ) . '"></amp-facebook>';
}

/**
 * Facebook video埋め込みの置換
 *
 * @param string $content 投稿内容.
 *
 * @return string
 */
function ys_amp_convert_facebook_video( $content ) {
	$pattern = '/<iframe[^>]+?src="https:\/\/www\.facebook\.com\/plugins\/video\.php\?href=(.*?)&.+?".+?><\/iframe>/is';
	$content = ys_amp_preg_replace_callback( $pattern, $content, 'ys_amp_convert_facebook_video_callback' );

	return $content;
}

/**
 * Facebook video AMP置換用コールバック
 *
 * @param array $m マッチした結果.
 *
 * @return string
 */
function ys_amp_convert_facebook_video_callback( $m ) {
	return '<amp-facebook width=552 height=574 layout="responsive" data-embed-as="video" data-href="' . urldecode( $m[1] ) . '"></amp-facebook>';
}
