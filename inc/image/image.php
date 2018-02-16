<?php
/**
 * 画像関連の処理
 */

/**
 * アイキャッチ画像のAMP対応
 */
function ys_post_thumbnail_html( $html, $post_id, $post_thumbnail_id, $size, $attr ) {
	return ys_amp_convert_image( $html );
}
add_filter( 'post_thumbnail_html', 'ys_post_thumbnail_html', 10, 5 );

/**
 * アイキャッチの画像オブジェクト取得
 */
if ( ! function_exists( 'ys_get_the_post_thumbnail_object' ) ) {
	function ys_get_the_post_thumbnail_object( $size = 'full', $post_id = null ) {
		$thumbnail_id = get_post_thumbnail_id( $post_id );
		if( $thumbnail_id ) {
			$image = wp_get_attachment_image_src( $thumbnail_id, $size );
			if( $image )  {
				return $image;
			}
		}
		return false;
	}
}

/**
 * 画像オブジェクト取得
 */
if ( ! function_exists( 'ys_get_the_image_object' ) ) {
	function ys_get_the_image_object( $size = 'full', $post_id = null ) {
		/**
		* アイキャッチ画像
		*/
		$image = ys_get_the_post_thumbnail_object( $size, $post_id );
		if( $image )  {
			return apply_filters( 'ys_get_the_image_object', $image );
		}
		/**
		 * アイキャッチがない → 投稿先頭画像
		 */
		$image = ys_get_the_first_image_object( $post_id );
		if( $image )  {
			return apply_filters( 'ys_get_the_image_object', $image );
		}
		/**
		 * アイキャッチもない、先頭画像も取得できない → 外部URL？
		 */
		$url = ys_get_the_first_image_url( $post_id );
		if( false !== $url ) {
			return apply_filters( 'ys_get_the_image_object', array( $url, 0, 0 ) );
		}
		/**
		 * 投稿内にとにかく画像が無い → OGPデフォルト画像
		 */
		$image = ys_get_ogp_default_image_object();
		if( $image ) {
			return apply_filters( 'ys_get_the_image_object', $image );
		}
		/**
		 * OGPデフォルト画像も無い → ロゴ画像
		 */
		$image = ys_get_custom_logo_image_object();
		if( $image ) {
			return apply_filters( 'ys_get_the_image_object', $image );
		}
		return false;
	}
}
/**
 * 画像URLから画像IDを取得する
 */
if ( ! function_exists( 'get_attachment_id_from_src' ) ) {
	function get_attachment_id_from_src( $src ) {
		global $wpdb;
		$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid='%s';", $src ) );
		if( empty( $attachment ) ) {
			return null;
		}
		return $attachment[0];
	}
}
/**
 * 投稿1番目の画像を取得
 */
if ( ! function_exists( 'ys_get_the_first_image_url' ) ) {
	function ys_get_the_first_image_url( $post_id = 0 ) {
		if( 0 === $post_id ){
			$post_id = get_the_ID();
		}
		/**
		 * 記事内容取得
		 */
		$post_content = get_post( $post_id )->post_content;
		$pattern = '/<img.+src=[\'"]([^\'"]+?)[\'"].*?>/i';
		$output = preg_match_all( $pattern, $post_content, $matches );
		if( false === $output || 0 >= $output ) {
			return false;
		}
		return $matches[1][0];
	}
}
/**
 * 投稿1番目の画像を取得
 */
if ( ! function_exists( 'ys_get_the_first_image_object' ) ) {
	function ys_get_the_first_image_object( $post_id = 0 ) {
		$url = ys_get_the_first_image_url( $post_id );
		if( false === $url ){
			return false;
		}
		$attachment_id = get_attachment_id_from_src( $url );
		if( is_null( $attachment_id ) ){
			return false;
		}
		/**
		 * 画像オブジェクト取得
		 */
		$image = wp_get_attachment_image_src( $attachment_id, 'full' );
		return $image;
	}
}
/*
 *	ImageObject用meta
 */
if ( ! function_exists( 'ys_get_the_image_object_meta' ) ) {
	function ys_get_the_image_object_meta( $image = null ) {
		if( empty( $image ) || is_array( $image ) ) {
			 global $post;
			 $image = ys_get_the_image_object( 'full', $post->ID );
		}
		$meta = '';
		if( $image ) {
			$meta .= '<meta itemprop="url" content="' . $image[0] . '" />';
			$meta .= '<meta itemprop="width" content="' . $image[1] . '" />';
			$meta .= '<meta itemprop="height" content="' . $image[2] . '" />';
		}
		return $meta;
	}
}
/**
 * カスタムロゴオブジェクト取得
 */
if (!function_exists( 'ys_get_custom_logo_image_object')) {
	function ys_get_custom_logo_image_object( $blog_id = 0 ) {
		if ( is_multisite() && (int) $blog_id !== get_current_blog_id() ) {
				switch_to_blog( $blog_id );
		}
		$custom_logo_id = get_theme_mod( 'custom_logo' );
		$image = false;
		// We have a logo. Logo is go.
		if ( $custom_logo_id ) {
				$image = wp_get_attachment_image_src( $custom_logo_id, 'full' );
		}
		if ( is_multisite() && ms_is_switched() ) {
				restore_current_blog();
		}
		return $image;
	}
}
/**
 * OGPデフォルト画像のimageオブジェクト取得
 */
if( ! function_exists( 'ys_get_ogp_default_image_object') ) {
	function ys_get_ogp_default_image_object() {
		$image = ys_get_option( 'ys_ogp_default_image' );
		if( $image ) {
			$image = wp_get_attachment_image_src( get_attachment_id_from_src( $image ), 'full' );
			if( $image ) {
				return $image;
			}
		}
		return false;
	}
}
/**
 * パブリッシャー用画像取得
 */
if ( ! function_exists( 'ys_get_publisher_image' ) ) {
	function ys_get_publisher_image() {
		/**
		 * パブリッシャー画像の取得
		 */
		$image = ys_get_option( 'ys_option_structured_data_publisher_image' );
		if( $image ) {
			$image = wp_get_attachment_image_src( get_attachment_id_from_src( $image ), 'full' );
			if( $image ) {
				return $image;
			}
		}
		/**
		 * ロゴ設定の取得
		 */
		$image = ys_get_custom_logo_image_object();
		if( $image ) {
			return $image;
		}
		return false;
	}
}
/**
 *	画像サイズ取得
 */
if ( ! function_exists( 'ys_get_image_size' ) ) {
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
}