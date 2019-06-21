<?php
/**
 * 画像関連の処理
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * アイキャッチ画像のAMP対応
 *
 * @param  string $html              The post thumbnail HTML.
 * @param  int    $post_id           The post ID.
 * @param  string $post_thumbnail_id The post thumbnail ID.
 * @param  string $size              The post thumbnail size. Image size or array of width and height values (in that order). Default 'post-thumbnail'.
 * @param  string $attr              Query string of attributes.
 *
 * @return string
 */
function ys_post_thumbnail_html( $html, $post_id, $post_thumbnail_id, $size, $attr ) {
	return ys_amp_convert_image( $html );
}

add_filter( 'post_thumbnail_html', 'ys_post_thumbnail_html', 10, 5 );

/**
 * アイキャッチの画像オブジェクト取得
 *
 * @param string $size    thumbnail size.
 * @param int    $post_id post ID.
 *
 * @return mixed
 */
function ys_get_the_post_thumbnail_object( $size = 'full', $post_id = null ) {
	$thumbnail_id = get_post_thumbnail_id( $post_id );
	if ( $thumbnail_id ) {
		$image = wp_get_attachment_image_src( $thumbnail_id, $size );
		if ( $image ) {
			return $image;
		}
	}

	return false;
}

/**
 * 画像オブジェクト取得
 *
 * @param string $size    thumbnail size.
 * @param int    $post_id post ID.
 *
 * @return mixed
 */
function ys_get_the_image_object( $size = 'full', $post_id = null ) {
	/**
	 * アイキャッチ画像
	 */
	$image = ys_get_the_post_thumbnail_object( $size, $post_id );
	if ( $image ) {
		return apply_filters( 'ys_get_the_image_object', $image );
	}
	/**
	 * アイキャッチがない → 投稿先頭画像
	 */
	$image = ys_get_the_first_image_object( $post_id );
	if ( $image ) {
		return apply_filters( 'ys_get_the_image_object', $image );
	}
	/**
	 * アイキャッチもない、先頭画像も取得できない → 外部URL？
	 */
	$url = ys_get_the_first_image_url( $post_id );
	if ( false !== $url ) {
		return apply_filters( 'ys_get_the_image_object', array( $url, 0, 0 ) );
	}
	/**
	 * 投稿内にとにかく画像が無い → OGPデフォルト画像
	 */
	$image = ys_get_ogp_default_image_object();
	if ( $image ) {
		return apply_filters( 'ys_get_the_image_object', $image );
	}
	/**
	 * OGPデフォルト画像も無い → ロゴ画像
	 */
	$image = ys_get_custom_logo_image_object();
	if ( $image ) {
		return apply_filters( 'ys_get_the_image_object', $image );
	}

	return false;
}

/**
 * 画像URLから画像IDを取得する
 *
 * @param string $src image src.
 *
 * @return mixed
 */
function get_attachment_id_from_src( $src ) {
	global $wpdb;
	$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid=%s;", $src ) );
	if ( empty( $attachment ) ) {
		return null;
	}

	return $attachment[0];
}

/**
 * 投稿1番目の画像を取得
 *
 * @param int $post_id post ID.
 *
 * @return mixed
 */
function ys_get_the_first_image_url( $post_id = 0 ) {
	if ( 0 === $post_id ) {
		$post_id = get_the_ID();
	}
	/**
	 * 記事内容取得
	 */
	$post_content = get_post( $post_id )->post_content;
	$pattern      = '/<img.+src=[\'"]([^\'"]+?)[\'"].*?>/i';
	$output       = preg_match_all( $pattern, $post_content, $matches );
	if ( false === $output || 0 >= $output ) {
		return false;
	}

	return $matches[1][0];
}

/**
 * 投稿1番目の画像を取得
 *
 * @param int $post_id post ID.
 *
 * @return mixed
 */
function ys_get_the_first_image_object( $post_id = 0 ) {
	$url = ys_get_the_first_image_url( $post_id );
	if ( false === $url ) {
		return false;
	}
	$attachment_id = get_attachment_id_from_src( $url );
	if ( is_null( $attachment_id ) ) {
		return false;
	}
	/**
	 * 画像オブジェクト取得
	 */
	$image = wp_get_attachment_image_src( $attachment_id, 'full' );

	return $image;
}

/**
 * ImageObject用meta
 *
 * @param object $image image object.
 *
 * @return string
 */
function ys_get_the_image_object_meta( $image = null ) {
	if ( empty( $image ) || is_array( $image ) ) {
		global $post;
		$image = ys_get_the_image_object( 'full', $post->ID );
	}
	$meta = '';
	if ( $image ) {
		$meta .= '<meta itemprop="url" content="' . $image[0] . '" />';
		$meta .= '<meta itemprop="width" content="' . $image[1] . '" />';
		$meta .= '<meta itemprop="height" content="' . $image[2] . '" />';
	}

	return $meta;
}

/**
 * カスタムロゴ取得
 *
 * @param integer $blog_id ブログID.
 *
 * @return string
 */
function ys_get_custom_logo( $blog_id = 0 ) {
	$html          = '';
	$switched_blog = false;

	if ( is_multisite() && ! empty( $blog_id ) && get_current_blog_id() !== (int) $blog_id ) {
		switch_to_blog( $blog_id );
		$switched_blog = true;
	}

	$custom_logo_id = get_theme_mod( 'custom_logo' );

	if ( $custom_logo_id ) {
		$custom_logo_attr = array( 'class' => 'custom-logo' );

		$image_alt = get_post_meta( $custom_logo_id, '_wp_attachment_image_alt', true );
		if ( empty( $image_alt ) ) {
			$custom_logo_attr['alt'] = get_bloginfo( 'name', 'display' );
		}
		$html = wp_get_attachment_image( $custom_logo_id, 'full', false, $custom_logo_attr );
	}
	if ( $switched_blog ) {
		restore_current_blog();
	}

	return apply_filters( 'ys_get_custom_logo', $html, $blog_id );
}

/**
 * カスタムロゴオブジェクト取得
 *
 * @param int $blog_id blog id.
 *
 * @return mixed
 */
function ys_get_custom_logo_image_object( $blog_id = 0 ) {
	if ( is_multisite() && get_current_blog_id() !== (int) $blog_id ) {
		switch_to_blog( $blog_id );
	}
	$custom_logo_id = get_theme_mod( 'custom_logo' );
	$image          = false;
	// We have a logo. Logo is go.
	if ( $custom_logo_id ) {
		$image = wp_get_attachment_image_src( $custom_logo_id, 'full' );
	}
	if ( is_multisite() && ms_is_switched() ) {
		restore_current_blog();
	}

	return $image;
}

/**
 * OGPデフォルト画像のimageオブジェクト取得
 */
function ys_get_ogp_default_image_object() {
	$image = ys_get_option( 'ys_ogp_default_image' );
	if ( $image ) {
		$image = wp_get_attachment_image_src( get_attachment_id_from_src( $image ), 'full' );
		if ( $image ) {
			return $image;
		}
	}

	return false;
}

/**
 * パブリッシャー用画像取得
 */
function ys_get_publisher_image() {
	/**
	 * パブリッシャー画像の取得
	 */
	$image = ys_get_option( 'ys_option_structured_data_publisher_image' );
	if ( $image ) {
		$image = wp_get_attachment_image_src( get_attachment_id_from_src( $image ), 'full' );
		if ( $image ) {
			return $image;
		}
	}
	/**
	 * ロゴ設定の取得
	 */
	$image = ys_get_custom_logo_image_object();
	if ( $image ) {
		return $image;
	}

	return get_template_directory_uri() . '/assets/images/publisher-logo/default-publisher-logo.png';
}

/**
 * 画像サイズ取得
 *
 * @param string $img_path path.
 *
 * @return array
 */
function ys_get_image_size( $img_path ) {
	$size = false;
	if ( file_exists( $img_path ) ) {
		$size = getimagesize( $img_path );
	}
	if ( false === $size ) {
		$size = array( 0, 0 );
	}

	return $size;
}
