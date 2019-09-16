<?php
/**
 * そのう消える予定の関数群
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 記事下ウィジェットを表示するか
 *
 * @deprecated ys_is_active_after_content_widgetを使う.
 */
function ys_is_active_entry_footer_widget() {
	ys_deprecated( 'ys_is_active_entry_footer_widget', 'v3.0.0' );

	return ys_is_active_after_content_widget();
}

/**
 * テーマ内で使用する設定の取得
 *
 * @return array
 */
function ys_get_options() {
	ys_deprecated( 'ys_get_options', 'v3.0.0' );

	return apply_filters( 'ys_get_options', array() );
}


/**
 * 投稿者画像取得
 *
 * @param boolean $user_id user id.
 * @param integer $size    image size.
 * @param array   $class   class.
 *
 * @return string
 */
function ys_get_author_avatar( $user_id = false, $size = 96, $class = array() ) {
	ys_deprecated( 'ys_get_author_avatar', 'v3.1.0' );

	if ( ! get_option( 'show_avatars', true ) ) {
		return '';
	}
	$user_id   = ys_check_user_id( $user_id );
	$author_id = $user_id;
	if ( ! $user_id ) {
		$author_id = get_the_author_meta( 'ID' );
	}
	$alt         = esc_attr( ys_get_author_display_name() );
	$user_avatar = get_avatar( $author_id, $size, '', $alt, array( 'class' => 'author__img' ) );
	/**
	 * カスタムアバター取得
	 */
	$custom_avatar = apply_filters(
		'ys_get_author_custom_avatar_src',
		get_user_meta( $author_id, 'ys_custom_avatar', true ),
		$author_id,
		$size,
		$class
	);
	/**
	 * Imgタグ作成
	 */
	$img       = '';
	$img_class = array( 'author__img' );
	if ( ! empty( $class ) ) {
		$img_class = array_merge( $img_class, $class );
	}
	if ( '' !== $custom_avatar ) {
		$img = sprintf(
			'<img class="%s" src="%s" alt="%s" %s />',
			implode( ' ', $img_class ),
			$custom_avatar,
			$alt,
			image_hwstring( $size, $size )
		);
	}
	/**
	 * カスタムアバターが無ければ通常アバター
	 */
	if ( '' === $img ) {
		$img = $user_avatar;
	}
	$img = ys_amp_get_amp_image_tag( $img );

	return apply_filters( 'ys_get_author_avatar', $img, $author_id, $size );
}

/**
 * 投稿者画像出力
 *
 * @param boolean $user_id user id.
 * @param integer $size    image size.
 * @param array   $class   class.
 */
function ys_the_author_avatar( $user_id = false, $size = 96, $class = array() ) {
	ys_deprecated( 'ys_the_author_avatar', 'v3.1.0' );
	echo ys_get_author_avatar( $user_id, $size, $class );
}