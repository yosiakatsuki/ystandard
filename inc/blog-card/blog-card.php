<?php
/**
 * ブログカード用データ作成
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * ブログカード表示データのキャッシュ用キー
 */
define( 'YS_BLOG_CARD_CACHE', 'ys_blog_card_cache' );
/**
 * ブログカード表示データ更新までの日数
 */
define( 'YS_BLOG_CARD_CACHE_DAY', 7 );

/**
 * ブログカード形式に変換する命令追加
 */
function ys_blog_card_embed_register_handler() {
	/**
	 * 変換予約されているもの以外のURLを対象にする
	 */
	wp_embed_register_handler(
		'ys_blog_card',
		ys_blog_card_get_register_pattern(),
		'ys_blog_card_handler'
	);
}

/**
 * ブログカード化する条件パターンを取得
 */
function ys_blog_card_get_register_pattern() {
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
	return '#^(?!.*(' . implode( '|', $providers ) . '))https?://.*$#i';
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
	return '[ys_blog_card url="' . $url . '"]';
}
/**
 * ブログカード展開用ショートコード
 *
 * @param array $args パラメーター.
 * @return void
 */
function ys_blog_card_shortcode( $args ) {
	$pairs = array(
		'url'         => '',
		'title'       => '',
		'dscr'        => '',
		'description' => '',
		'domain'      => '',
		'thumbnail'   => '',
		'target'      => '',
	);
	$args  = shortcode_atts( $pairs, $args );
	if ( '' === $args['url'] ) {
		return;
	}
	$url = $args['url'];
	if ( ! wp_http_validate_url( $url ) ) {
		return ys_blog_card_create_a_tag( $url );
	}
	/**
	 * TitleとURLがセットされている場合はマニュアルでデータ作成
	 */
	if ( '' !== $args['title'] && '' !== $args['url'] ) {
		$data = ys_blog_card_create_data_by_param( $args, $data );
	} else {
		/**
		 * ブログカード用データ取得
		 */
		$data = ys_blog_card_get_data( $url );
	}
	/**
	 * データが取れていなければ中断
	 */
	if ( ! $data['blog_card'] ) {
		return ys_blog_card_create_a_tag( $url );
	}
	/**
	 * 整形
	 */
	if ( '' !== $data['thumbnail'] ) {
		$data['thumbnail'] = sprintf( '<figure class="ys-blog-card__thumb">%s</figure>', $data['thumbnail'] );
	}
	if ( '' !== $data['dscr'] ) {
		$dscr = mb_substr( $data['dscr'], 0, apply_filters( 'ys_blog_card_dscr_length', 50 ) );
		if ( $data['dscr'] !== $dscr ) {
			$dscr .= '...';
		}
		$data['dscr'] = sprintf( '<div class="ys-blog-card__dscr">%s</div>', $dscr );
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
add_shortcode( 'ys_blog_card', 'ys_blog_card_shortcode' );

/**
 * ブログカード用データ取得
 *
 * @param  string  $url url.
 * @param  boolean $cache_refresh cache refresh flag.
 */
function ys_blog_card_get_data( $url, $cache_refresh = false ) {
	/**
	 * キャッシュがあればそちらを返す
	 */
	$cache = ys_blog_card_get_cache_data( $url );
	if ( false !== $cache && ! $cache_refresh ) {
		return $cache;
	}
	/**
	 * HTMLの展開に必要な情報取得
	 */
	$post_id = ys_blog_card_get_post_id( $url );
	$data    = ys_blog_card_get_data_array();
	/**
	 * 初期データのセット
	 */
	$data['post_id'] = $post_id;
	$data['url']     = $url;
	/**
	 * データ取得
	 */
	if ( 0 !== $post_id ) {
		$data = ys_blog_card_get_post_data( $data );
	} else {
		$data = ys_blog_card_get_site_data( $data );
	}
	/**
	 * キャッシュ更新
	 */
	ys_blog_card_update_cache( $url, $data );
	return $data;
}
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
		$thumb_size        = apply_filters( 'ys_blog_card_thumbnail_size', 'thumbnail' );
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
	$url     = $data['url'];
	$content = ys_blog_card_get_site_content( $url );
	if ( false === $content ) {
		return $data;
	}
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
 * @param string $url url.
 * @return string レスポンスbody
 */
function ys_blog_card_get_site_content( $url ) {
	$response = wp_remote_get( $url );
	if ( ! is_array( $response ) || 200 !== $response['response']['code'] ) {
		return false;
	}
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

/**
 * ブログカードのキャッシュデータの取得
 */
function ys_blog_card_get_cache_list() {
	global $post;
	$cache = array();
	if ( isset( $post->ID ) && ! is_preview() ) {
		/**
		 * カスタムフィールドから取得
		 */
		$cache = get_post_meta( $post->ID, YS_BLOG_CARD_CACHE, true );
		$cache = json_decode( $cache, true );
		if ( ! is_array( $cache ) ) {
			return array();
		}
		array_walk_recursive( $cache, 'ys_blog_card_decode_cache' );
	}
	return $cache;
}
/**
 * ブログカード用キャッシュ取得
 *
 * @param mixed $item array value.
 * @param mixed $key  array key.
 */
function ys_blog_card_decode_cache( &$item, $key ) {
	/**
	 * いろいろ変換された部分を戻す
	 * バックスラッシュが消えるのでうまいこと戻す
	 */
	$item = str_replace( '&quot;', '"', $item );
	$item = str_replace( '\u003C', '<', $item );
	$item = str_replace( 'u003C', '<', $item );
	$item = str_replace( '\u003E', '>', $item );
	$item = str_replace( 'u003E', '>', $item );
	$item = str_replace( '\u0026', '&', $item );
	$item = str_replace( 'u0026', '&', $item );
	$item = str_replace( '\u0027', '\'', $item );
	$item = str_replace( 'u0027', '\'', $item );
	$item = str_replace( '\u0022', '"', $item );
	$item = str_replace( 'u0022', '"', $item );
}

/**
 * ブログカードのキャッシュデータを取得
 *
 * @param  string $url url.
 */
function ys_blog_card_get_cache_data( $url ) {
	$cache = ys_blog_card_get_cache_list();
	/**
	 * キャッシュデータが無ければfalse
	 */
	if ( ! array_key_exists( $url, $cache ) ) {
		return false;
	}
	$data = $cache[ $url ];
	/**
	 * キャッシュの有効期限確認
	 */
	$chach_date = new DateTime( $data['create_at'] );
	$chach_date->modify( '+' . YS_BLOG_CARD_CACHE_DAY . ' day' );
	$today = new DateTime( date_i18n( 'Y-m-d' ) );
	if ( $chach_date < $today ) {
		return false;
	}
	return $data;
}

/**
 * ブログカードのキャッシュ更新
 *
 * @param  string $url  url.
 * @param  array  $data ブログカード用データ.
 */
function ys_blog_card_update_cache( $url, $data ) {
	global $post;
	if ( isset( $post->ID ) && ! is_preview() ) {
		$cache         = ys_blog_card_get_cache_list();
		$cache[ $url ] = $data;
		$cache         = json_encode( $cache, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE );
		/**
		 * 配列が入らない問題
		 */
		update_post_meta( $post->ID, YS_BLOG_CARD_CACHE, $cache );
	}
}

/**
 * URLからaタグを作る(ブログカード展開できなかった時用)
 *
 * @param  string $url URL.
 */
function ys_blog_card_create_a_tag( $url ) {
	$url = sprintf(
		'<a href="%s" target="_blank">%s</a>',
		$url,
		$url
	);
	if ( has_filter( 'the_content', 'wpautop' ) ) {
		$url = wpautop( $url );
	} else {
		$url = '<br>' . $url;
	}
	return $url;
}

/**
 * ブログカード作成用データのガワの作成
 */
function ys_blog_card_get_data_array() {
	return array(
		'post_id'   => 0,
		'url'       => '',
		'target'    => '',
		'thumbnail' => '',
		'title'     => '',
		'dscr'      => '',
		'domain'    => '',
		'blog_card' => false,
		'create_at' => date_i18n( 'Y-m-d' ),
	);
}

/**
 * パラメータからブログカード用データを作成
 *
 * @param  array $args パラメータ.
 * @param  array $data ブログカード用データ.
 * @return array       ブログカード用データ.
 */
function ys_blog_card_create_data_by_param( $args, $data ) {
	$data              = ys_blog_card_get_data_array();
	$data['blog_card'] = true;
	$data['title']     = $args['title'];
	$data['url']       = $args['url'];
	if ( '' !== $args['dscr'] ) {
		$data['dscr'] = $args['dscr'];
	}
	if ( '' !== $args['description'] ) {
		$data['dscr'] = $args['description'];
	}
	if ( '' !== $args['thumbnail'] ) {
		$data['thumbnail'] = $args['thumbnail'];
	}
	if ( '' !== $args['domain'] ) {
		$data['domain'] = $args['domain'];
	}
	if ( false === strpos( $data['url'], home_url() ) ) {
		$data['target'] = ' target="_blank"';
	}
	if ( '' !== $args['target'] ) {
		$data['target'] = ' target="' . $args['target'] . '"';
	}
	return $data;
}

/**
 * 記事更新時にブログカード用データをキャッシュ
 *
 * @param  string $new_status new status.
 * @param  string $old_status old status.
 * @param  object $post       post object.
 */
function ys_blog_card_refresh_cache( $new_status, $old_status, $post ) {
	/**
	 * キャッシュを更新するタイミング
	 */
	$status = array(
		'publish',
	);
	if ( in_array( $new_status, $status ) ) {
		$pattern = '#^https?://[-_.!~*\'()a-zA-Z0-9;/?:@&=+$,%\#]+#im';
		if ( false !== preg_match_all( $pattern, $post->post_content, $matches ) ) {
			foreach ( $matches[0] as $url ) {
				/**
				 * キャッシュの強制更新
				 */
				ys_blog_card_get_data( $url, true );
			}
		}
	}
}
add_action( 'transition_post_status', 'ys_blog_card_refresh_cache', 10, 3 );