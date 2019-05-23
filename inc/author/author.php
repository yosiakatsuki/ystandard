<?php
/**
 * Author
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 投稿者の記事一覧リンク取得
 *
 * @param  boolean $user_id ユーザーID.
 *
 * @return string
 */
function ys_get_author_link( $user_id = false ) {
	$user_id = ys_check_user_id( $user_id );

	return esc_url_raw( get_author_posts_url( get_the_author_meta( 'ID', $user_id ) ) );
}

/**
 * 投稿者記事一覧リンク表示
 *
 * @param  boolean $user_id ユーザーID.
 *
 * @return void
 */
function ys_the_author_link( $user_id = false ) {
	echo ys_get_author_link( $user_id );
}

/**
 * 投稿者名取得
 *
 * @param  boolean $user_id ユーザーID.
 *
 * @return string
 */
function ys_get_author_display_name( $user_id = false ) {
	$user_id = ys_check_user_id( $user_id );

	return get_the_author_meta( 'display_name', $user_id );
}

/**
 * ユーザー表示名の出力
 *
 * @param boolean $user_id ユーザーID.
 *
 * @return void
 */
function ys_the_author_display_name( $user_id = false ) {
	echo ys_get_author_display_name( $user_id );
}

/**
 * 投稿者名（html）取得
 *
 * @param  boolean     $user_id ユーザーID.
 * @param  string|bool $link    投稿者一覧ページURL.
 *
 * @return string
 */
function ys_get_author_name( $user_id = false, $link = '' ) {
	$user_id     = ys_check_user_id( $user_id );
	$author_name = esc_html( ys_get_author_display_name( $user_id ) );

	/**
	 * リンクの必要確認
	 */
	if ( false === $link ) {
		return $author_name;
	}
	/**
	 * URL取得
	 */
	if ( '' === $link ) {
		$link = ys_get_author_link( $user_id );
	}
	/**
	 * リンク作成
	 */
	$author_name = sprintf(
		'<span class="clear-a"><a href="%s" rel="author">%s</a></span>',
		$link,
		$author_name
	);

	return $author_name;
}

/**
 * 投稿者名（html）出力
 *
 * @param  boolean $user_id ユーザーID.
 * @param  boolean $vcard   vcardでマークアップするか.
 *
 * @return void
 */
function ys_the_author_name( $user_id = false, $vcard = true ) {
	echo ys_get_author_name( $user_id, $vcard );
}

/**
 * 投稿者 SNS一覧取得
 *
 * @param  boolean $user_id ユーザーID.
 *
 * @return array
 */
function ys_get_author_sns_list( $user_id = false ) {
	$user_id = ys_check_user_id( $user_id );
	/**
	 * 取得対象の meta key
	 * キーがmeta key,valueがfontawesomeのクラス
	 */
	$sns_key = array(
		'url'          => array(
			'icon'  => 'fas fa-globe-asia',
			'color' => 'globe',
			'title' => 'Web',
		),
		'ys_twitter'   => array(
			'icon'  => 'fab fa-twitter',
			'color' => 'twitter',
			'title' => 'Twitter',
		),
		'ys_facebook'  => array(
			'icon'  => 'fab fa-facebook-f',
			'color' => 'facebook',
			'title' => 'Facebook',
		),
		'ys_instagram' => array(
			'icon'  => 'fab fa-instagram',
			'color' => 'instagram',
			'title' => 'Instagram',
		),
		'ys_tumblr'    => array(
			'icon'  => 'fab fa-tumblr',
			'color' => 'tumblr',
			'title' => 'Tumblr',
		),
		'ys_youtube'   => array(
			'icon'  => 'fab fa-youtube',
			'color' => 'youtube-play',
			'title' => 'YouTube',
		),
		'ys_github'    => array(
			'icon'  => 'fab fa-github',
			'color' => 'github',
			'title' => 'GitHub',
		),
		'ys_pinterest' => array(
			'icon'  => 'fab fa-pinterest',
			'color' => 'pinterest',
			'title' => 'Pinterest',
		),
		'ys_linkedin'  => array(
			'icon'  => 'fab fa-linkedin',
			'color' => 'linkedin',
			'title' => 'LinkedIn',
		),
	);
	$list    = array();
	foreach ( $sns_key as $key => $val ) {
		$arg = ys_get_author_sns_item( $key, $val, $user_id );
		if ( false !== $arg ) {
			$list[ $key ] = $arg;
		}
	}

	return apply_filters( 'ys_get_author_sns_list', $list );
}

/**
 * 投稿者 SNS一覧用配列取得
 *
 * @param string $key     key.
 * @param array  $val     value.
 * @param int    $user_id user id.
 *
 * @return mixed
 */
function ys_get_author_sns_item( $key, $val, $user_id ) {
	$url = get_the_author_meta( $key, $user_id );
	if ( '' === $url ) {
		return false;
	}

	return array(
		'type'  => $key,
		'icon'  => esc_attr( $val['icon'] ),
		'color' => esc_attr( $val['color'] ),
		'title' => esc_attr( $val['title'] ),
		'url'   => esc_url_raw( $url ),
	);
}

/**
 * 投稿者 SNSリンク出力
 *
 * @return void
 */
function ys_the_author_sns() {
	$sns = ys_get_author_sns_list();
	if ( ! empty( $sns ) ) :
		?>
		<ul class="author__sns li-clear">
			<?php foreach ( $sns as $key => $value ) : ?>
				<li class="author__sns-item">
					<a class="sns__color--<?php echo $value['color']; ?> author__sns-link" href="<?php echo $value['url']; ?>" target="_blank" rel="nofollow"><i class="<?php echo $value['icon']; ?>" aria-hidden="true"></i></a>
				</li>
			<?php endforeach; ?>
		</ul><!-- .author__sns -->
	<?php
	endif;
}

/**
 * 投稿者 プロフィール説明文取得
 *
 * @param  boolean $user_id ユーザーID.
 * @param  boolean $wpautop wpautopを効かせるか.
 *
 * @return string
 */
function ys_get_author_description( $user_id = false, $wpautop = true ) {
	$user_id     = ys_check_user_id( $user_id );
	$author_dscr = get_the_author_meta( 'description', $user_id );
	if ( $wpautop ) {
		$author_dscr = wpautop( str_replace( array( "\r\n", "\r", "\n" ), "\n\n", $author_dscr ) );
	}

	return $author_dscr;
}

/**
 * 投稿者 プロフィール出力
 *
 * @param boolean $user_id ユーザーID.
 * @param boolean $wpautop pタグ追加有無.
 *
 * @return void
 */
function ys_the_author_description( $user_id = false, $wpautop = true ) {
	echo ys_get_author_description( $user_id, $wpautop );
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
	if ( ! get_option( 'show_avatars', true ) ) {
		return '';
	}
	$user_id   = ys_check_user_id( $user_id );
	$author_id = $user_id;
	if ( ! $user_id ) {
		$author_id = get_the_author_meta( 'ID' );
	}
	$alt           = esc_attr( ys_get_author_display_name() );
	$user_avatar   = get_avatar( $author_id, $size, '', $alt, array( 'class' => 'author__img' ) );
	$custom_avatar = get_user_meta( $author_id, 'ys_custom_avatar', true );
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
	echo ys_get_author_avatar( $user_id, $size, $class );
}

/**
 * 投稿者ID 上書きチェック
 * 主にショートコードでの上書きに使用
 *
 * @param int $user_id user id.
 *
 * @return string
 */
function ys_check_user_id( $user_id ) {
	global $ys_author;
	if ( false === $user_id && false !== $ys_author ) {
		$user_id = $ys_author;
	}

	return $user_id;
}

/**
 * 投稿者表示
 */
function ys_the_author_box() {
	/**
	 * パラメータ作成
	 */
	$param = array(
		'title' => 'この記事を書いた人',
	);
	/**
	 * ショートコード実行
	 */
	ys_do_shortcode( 'ys_author', $param );
}