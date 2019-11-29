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
	YS_Utility::deprecated_comment( 'ys_is_active_entry_footer_widget', 'v3.0.0' );

	return ys_is_active_after_content_widget();
}

/**
 * テーマ内で使用する設定の取得
 *
 * @return array
 * @deprecated v3.0.0
 */
function ys_get_options() {
	YS_Utility::deprecated_comment( 'ys_get_options', 'v3.0.0' );

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
 * @deprecated v3.1.0
 */
function ys_get_author_avatar( $user_id = false, $size = 96, $class = array() ) {
	YS_Utility::deprecated_comment( 'ys_get_author_avatar', 'v3.1.0' );

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
 *
 * @deprecated v3.1.0
 */
function ys_the_author_avatar( $user_id = false, $size = 96, $class = array() ) {
	YS_Utility::deprecated_comment( 'ys_the_author_avatar', 'v3.1.0' );
	echo ys_get_author_avatar( $user_id, $size, $class );
}

/**
 * Twitter用JavaScript URL取得
 *
 * @return string
 * @deprecated v3.6.0
 */
function ys_get_twitter_widgets_js() {
	YS_Utility::deprecated_comment( 'ys_get_twitter_widgets_js', 'v3.6.0' );

	return YS_Utility::get_twitter_widgets_js();
}

/**
 * Facebook用JavaScript URL取得
 *
 * @return string
 * @deprecated v3.6.0
 */
function ys_get_facebook_sdk_js() {
	YS_Utility::deprecated_comment( 'ys_get_facebook_sdk_js', 'v3.6.0' );

	return YS_Utility::get_facebook_sdk_js();
}

/**
 * 読み込むCSSファイルのURLを取得する
 *
 * @return string
 * @deprecated v3.6.0
 */
function ys_get_enqueue_css_file_uri() {
	YS_Utility::deprecated_comment( 'ys_get_enqueue_css_file_uri', 'v3.6.0' );

	return YS_Scripts::get_enqueue_css_file_uri();
}

/**
 * 読み込むCSSファイルのパスを取得する
 *
 * @return string
 * @deprecated v3.6.0
 */
function ys_get_enqueue_css_file_path() {
	YS_Utility::deprecated_comment( 'ys_get_enqueue_css_file_path', 'v3.6.0' );

	return YS_Scripts::get_enqueue_css_file_path();
}

/**
 * 読み込むCSSファイルの名前を取得する
 *
 * @return string
 * @deprecated v3.6.0
 */
function ys_get_enqueue_css_file_name() {
	YS_Utility::deprecated_comment( 'ys_get_enqueue_css_file_name', 'v3.6.0' );

	return YS_Scripts::get_enqueue_css_file_name();
}

/**
 * いろいろ削除
 *
 * @deprecated v3.6.0
 */
function ys_remove_action() {
	YS_Utility::deprecated_comment( 'ys_remove_action', 'v3.6.0' );
}


/**
 * 絵文字無効化
 *
 * @deprecated v3.6.0
 */
function ys_remove_emoji() {
	YS_Utility::deprecated_comment( 'ys_remove_emoji', 'v3.6.0' );
}

/**
 * Embed無効化
 *
 * @deprecated v3.6.0
 */
function ys_remove_oembed() {
	YS_Utility::deprecated_comment( 'ys_remove_oembed', 'v3.6.0' );
}

/**
 * タクソノミー説明の処理カスタマイズ
 *
 * @deprecated v3.6.0
 */
function ys_tax_dscr_allowed_option() {
	YS_Utility::deprecated_comment( 'ys_tax_dscr_allowed_option', 'v3.6.0' );
}

/**
 * ファイル内容の取得
 *
 * @param string $file ファイルパス.
 *
 * @return string
 * @deprecated v3.6.0
 */
function ys_file_get_contents( $file ) {
	YS_Utility::deprecated_comment( 'ys_file_get_contents', 'v3.6.0' );

	return YS_Utility::file_get_contents( $file );
}


/**
 * CSSのセット
 *
 * @param string $handle Handle.
 * @param string $src    CSSのURL.
 * @param bool   $inline インライン読み込みするか.
 * @param array  $deps   deps.
 * @param bool   $ver    クエリストリング.
 * @param string $media  media.
 * @param bool   $minify Minifyするか.
 *
 * @return void
 * @deprecated v3.6.0
 */
function ys_enqueue_css( $handle, $src, $inline = true, $deps = array(), $ver = false, $media = 'all', $minify = true ) {
	YS_Utility::deprecated_comment( 'ys_enqueue_css', 'v3.6.0' );

	$scripts = ys_scripts();
	$scripts->set_enqueue_style( $handle, $src, $inline, $deps, $ver, $media, $minify );
}

/**
 * インラインCSSのセット
 *
 * @param string $style  CSS.
 * @param bool   $minify Minifyするか.
 *
 * @return void
 * @deprecated v3.6.0
 */
function ys_enqueue_inline_css( $style, $minify = true ) {
	YS_Utility::deprecated_comment( 'ys_enqueue_inline_css', 'v3.6.0' );

	$scripts = ys_scripts();
	$scripts->set_inline_style( $style, $minify );
}


/**
 * カスタマイザー設定のCSSにメディアクエリを追加
 *
 * @param string $css        Styles.
 * @param string $breakpoint Breakpoint.
 * @param string $type       Breakpoint type(min or max).
 *
 * @return string
 * @deprecated v3.6.0
 */
function ys_customizer_add_media_query( $css, $breakpoint, $type = 'min' ) {
	YS_Utility::deprecated_comment( 'ys_customizer_add_media_query', 'v3.6.0' );

	/**
	 * ブレークポイント
	 */
	$breakpoints = array(
		'md' => 600,
		'lg' => 1025,
	);
	/**
	 * 切り替え位置取得
	 */
	if ( isset( $breakpoints[ $breakpoint ] ) ) {
		$breakpoint = $breakpoints[ $breakpoint ];
		if ( 'max' === $type ) {
			$breakpoint = $breakpoint - 0.1;
		}
	}
	/**
	 * 以上・以下判断
	 */
	if ( 'min' !== $type && 'max' !== $type ) {
		return $css;
	}

	return sprintf(
		'@media screen and (%s-width: %spx) {%s}',
		$type,
		$breakpoint,
		$css
	);
}

/**
 * テーマカスタマイザーでの色指定 CSS取得
 *
 * @return string
 * @deprecated v3.6.0
 */
function ys_get_customizer_inline_css_color() {
	YS_Utility::deprecated_comment( 'ys_get_customizer_inline_css_color', 'v3.6.0' );

	if ( ys_get_option( 'ys_desabled_color_customizeser' ) ) {
		return '';
	}
	$inline_css = new YS_Inline_Css();
	/**
	 * 設定取得
	 */
	$css = '';
	/**
	 * CSS
	 */
	$css .= $inline_css->get_site_css();
	$css .= $inline_css->get_header_css();
	$css .= $inline_css->get_nav_css();
	$css .= $inline_css->get_footer_css();

	return apply_filters( 'ys_get_customizer_inline_css_color', $css );
}


/**
 * テーマカスタマイザーでのCSS設定 カスタムヘッダー
 *
 * @return string
 * @deprecated v3.6.0
 */
function ys_get_customizer_inline_css_custom_header() {
	YS_Utility::deprecated_comment( 'ys_get_customizer_inline_css_custom_header', 'v3.6.0' );
	$inline_css = new YS_Inline_Css();

	return $inline_css->get_custom_header_css();
}


/**
 * モバイルフッター設定によって追加するCSS
 *
 * @deprecated v3.6.0
 */
function ys_get_inline_css_mobile_css() {
	YS_Utility::deprecated_comment( 'ys_get_inline_css_mobile_css', 'v3.6.0' );

	$inline_css = new YS_Inline_Css();

	return $inline_css->get_mobile_footer_css();
}


/**
 * セレクタとプロパティをくっつけてCSS作る
 *
 * @param array $selectors  セレクタ.
 * @param array $properties プロパティ.
 *
 * @return string
 * @deprecated v3.6.0
 */
function ys_customizer_create_inline_css( $selectors, $properties ) {
	YS_Utility::deprecated_comment( 'ys_customizer_create_inline_css', 'v3.6.0' );
	$property = '';
	foreach ( $properties as $key => $value ) {
		$property .= $key . ':' . $value . ';';
	}

	return implode( ',', $selectors ) . '{' . $property . '}';
}

/**
 * テーマカスタマイザー/設定関連で変更する CSS取得
 *
 * @return string
 * @deprecated v3.6.0
 */
function ys_get_customizer_inline_css() {
	YS_Utility::deprecated_comment( 'ys_get_customizer_inline_css', 'v3.6.0' );

	$css = '';
	/**
	 * カスタマイザー色指定
	 */
	$css .= ys_get_customizer_inline_css_color();

	/**
	 * カスタムヘッダー
	 */
	$css .= ys_get_customizer_inline_css_custom_header();

	/**
	 * モバイルフッター
	 */
	$css .= ys_get_inline_css_mobile_css();

	return apply_filters( 'ys_get_customizer_inline_css', $css );
}


/**
 * カスタマイザー用画像アセットURL取得
 *
 * @deprecated v3.7.0
 */
function ys_get_template_customizer_assets_img_dir_uri() {
	YS_Utility::deprecated_comment( 'ys_get_template_customizer_assets_img_dir_uri', 'v3.7.0' );

	return get_template_directory_uri() . '/assets/images/customizer';
}

/**
 * チェックボックスのサニタイズ
 *
 * @param mixed $value 値.
 *
 * @return bool
 * @deprecated v3.7.0
 */
function ys_sanitize_bool( $value ) {
	YS_Utility::deprecated_comment( 'ys_get_template_customizer_assets_img_dir_uri', 'v3.7.0' );

	return ys_to_bool( $value );
}