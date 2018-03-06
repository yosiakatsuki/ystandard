<?php
/**
 * ブログカード用データ作成
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * ブログカード形式に変換する命令追加
 */
function ys_blog_card_embed_register_handler() {
	/**
	 * Embed 変換されるURLパターンを取得
	 */
	$oembed    = _wp_oembed_get_object();
	$providers = array_keys( $oembed->providers );
	/**
	 * デリミタの削除
	 */
	foreach ( $providers as $key => $value ) {
		$providers[ $key ] = preg_replace( '/^#(.+)#.*$/', '$1', $value );
	}
	/**
	 * 変換予約されているもの以外のURLを対象にする
	 */
	$regex = '#^(?!.*(' . implode( '|', $providers ) . '))https?://.*$#i';
	wp_embed_register_handler( 'ys_blog_card', $regex, 'ys_blog_card_handler' );
}
/**
 * Embedの変換ハンドラ
 *
 * @param [type] $matches matches.
 * @param [type] $attr attr.
 * @param [type] $url url.
 * @param [type] $rawattr rawattr.
 * @return single ブログカード用ショートコード
 */
function ys_blog_card_handler( $matches, $attr, $url, $rawattr ) {
	return '[ysblogcard url="' . $url . '"]';
}
/**
 * ブログカード展開用ショートコード
 *
 * @param array $args パラメーター.
 * @return void
 */
function ys_blog_card_shortcode( $args ) {
	$pairs = array( 'url' => '' );
	$args  = shortcode_atts( $pairs, $args );
	if ( '' === $args['url'] ) {
		return;
	}
	$url = $args['url'];
	if ( ! wp_http_validate_url( $url ) ) {
		return $url;
	}
	/**
	 * HTMLの展開に必要な情報取得
	 */
	$post_id = ys_blog_card_get_post_id( $url );
	$data    = array(
		'post_id'   => $post_id,
		'url'       => $url,
		'target'    => '',
		'thumbnail' => '',
		'title'     => '',
		'dscr'      => '',
		'domain'    => '',
		'blog_card' => false,
	);
	/**
	 * データ取得
	 */
	if ( 0 !== $post_id ) {
		$data = ys_blog_card_get_post_data( $data );
	} else {
		$data = ys_blog_card_get_site_data( $data );
	}
	/**
	 * データが取れていなければ中断
	 */
	if ( ! $data['blog_card'] ) {
		return $url;
	}
	/**
	 * 整形
	 */
	if ( '' !== $data['thumbnail'] ) {
		$data['thumbnail'] = sprintf( '<figure class="ys-blog-card__thumb">%s</figure>', $data['thumbnail'] );
	}
	if ( '' !== $data['dscr'] ) {
		$data['dscr'] = sprintf( '<div class="ys-blog-card__dscr">%s</div>', $data['dscr'] );
	}
	/**
	 * テンプレート取得
	 */
	ob_start();
	get_template_part( 'template-parts/blog-card/blog-card' );
	$template = ob_get_clean();
	/**
	 * 置換
	 */
	$template = preg_replace( '/\{url\}/', $data['url'], $template );
	$template = preg_replace( '/\{target\}/', $data['target'], $template );
	$template = preg_replace( '/\{thumbnail\}/', $data['thumbnail'], $template );
	$template = preg_replace( '/\{title\}/', $data['title'], $template );
	$template = preg_replace( '/\{dscr\}/', $data['dscr'], $template );
	$template = preg_replace( '/\{domain\}/', $data['domain'], $template );
	return $template;
}
add_shortcode( 'ysblogcard', 'ys_blog_card_shortcode' );
/**
 * URLからPost ID取得
 *
 * @param [type] $url Url.
 * @return integer Post ID.
 */
function ys_blog_card_get_post_id( $url ) {
	if ( false === strpos( $url, home_url() ) ) {
		return 0;
	}
	return url_to_postid( $url );
}
/**
 * 自サイトの情報取得
 *
 * @param array $data data.
 */
function ys_blog_card_get_post_data( $data ) {
	/**
	 * サムネイルの取得
	 */
	$url     = $data['url'];
	$post_id = $data['post_id'];
	$post    = get_post( $post_id );
	if ( has_post_thumbnail( $post_id ) ) {
		$thumb_size        = apply_filters( 'ys_blog_card_thumbnail_size', 'post-thumbnail' );
		$thumb             = get_the_post_thumbnail( $post_id, $thumb_size, array( 'class' => 'ys-blog-card__img' ) );
		$thumb             = apply_filters( 'ys_blog_card_thumbnail', $thumb, $post_id );
		$data['thumbnail'] = ys_amp_convert_image( $thumb );
	}
	/**
	 * タイトルの取得
	 */
	$data['title'] = $post->post_title;
	/**
	 * 抜粋の取得
	 */
	$data['dscr'] = ys_get_the_custom_excerpt( ' …', 0, $post_id );

	/**
	 * ドメイン
	 */
	$icon = ys_get_apple_touch_icon_url();
	if ( ! empty( $icon ) ) {
		$icon = '<img class="ys-blog-card__site-icon" src="' . $icon . '" alt="" width="10" height="10" />';
		$icon = ys_amp_convert_image( $icon );
	} else {
		$icon = '';
	}
	$data['domain']    = $icon . ys_blog_card_get_domain_string( $url );
	$data['blog_card'] = true;
	return $data;
}
/**
 * 外部サイト、自サイトの個別投稿以外 の情報取得
 *
 * @param array $data data.
 */
function ys_blog_card_get_site_data( $data ) {
	$url      = $data['url'];
	$response = wp_remote_get( $url );
	if ( ! is_array( $response ) || 200 !== $response['response']['code'] ) {
		return $data;
	}
	$content = ys_blog_card_get_site_content( $response );
	/**
	 * タイトルと概要を取得
	 */
	$title = ys_blog_card_get_site_title( $content );
	if ( '' === $title ) {
		return $data;
	}
	$dscr              = ys_blog_card_get_site_description( $content );
	$data['thumbnail'] = apply_filters( 'ys_blog_card_site_thumbnail', $data['thumbnail'], $content, $url );
	$data['title']     = esc_html( $title );
	$data['dscr']      = esc_html( $dscr );
	$data['target']    = ' target="_blank"';
	$data['domain']    = ys_blog_card_get_domain_string( $url );
	$data['blog_card'] = true;
	return $data;
}
/**
 * ブログカード表示用 ドメイン取得
 *
 * @param string $url url.
 * @return string 表示用ドメイン
 */
function ys_blog_card_get_domain_string( $url ) {
	if ( false !== strpos( $url, home_url() ) ) {
		return rtrim( preg_replace( '/https?:\/\//i', '', home_url() ), '/' );
	}
	return parse_url( $url, PHP_URL_HOST );
}
/**
 * コンテンツ部分の抽出
 *
 * @param [type] $response HTTPレスポンス.
 * @return string レスポンスbody
 */
function ys_blog_card_get_site_content( $response ) {
	return $response['body'];
}
/**
 * サイトタイトル取得
 *
 * @param single $content コンテンツ.
 * @return single ページタイトル
 */
function ys_blog_card_get_site_title( $content ) {
	if ( 1 === preg_match( '/<title>(.+?)<\/title>/is', $content, $matches ) ) {
		return $matches[1];
	}
	if ( 1 === preg_match( '/<meta.+?property=["\']og:title["\'][^\/>]*?content=["\']([^"\']+?)["\'].*?\/?>/is', $content, $matches ) ) {
		return $matches[1];
	}
	return '';
}
/**
 * サイトdescription
 *
 * @param single $content コンテンツ.
 * @return single description
 */
function ys_blog_card_get_site_description( $content ) {
	if ( 1 === preg_match( '/<meta.+?name=["\']description["\'][^\/>]*?content=["\']([^"\']+?)["\'].*?\/?>/is', $content, $matches ) ) {
		return $matches[1];
	}
	if ( 1 === preg_match( '/<meta.+?property=["\']og:description["\'][^\/>]*?content=["\']([^"\']+?)["\'].*?\/?>/is', $content, $matches ) ) {
		return $matches[1];
	}
	return '';
}