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
 * @param boolean $user_id ユーザーID.
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
 * @param boolean $user_id ユーザーID.
 *
 * @return void
 */
function ys_the_author_link( $user_id = false ) {
	echo ys_get_author_link( $user_id );
}

/**
 * 投稿者名取得
 *
 * @param boolean $user_id ユーザーID.
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
 * @param boolean     $user_id ユーザーID.
 * @param string|bool $link    投稿者一覧ページURL.
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
 * @param boolean $user_id ユーザーID.
 * @param boolean $vcard   vcardでマークアップするか.
 *
 * @return void
 */
function ys_the_author_name( $user_id = false, $vcard = true ) {
	echo ys_get_author_name( $user_id, $vcard );
}

/**
 * 投稿者 SNS一覧取得
 *
 * @param boolean $user_id ユーザーID.
 *
 * @return array
 */
function ys_get_author_sns_list( $user_id = false ) {
	$user_id = ys_check_user_id( $user_id );
	$list    = array();
	/**
	 * URLを1番に作成
	 */
	$list['url'] = ys_get_author_sns_item(
		'url',
		array(
			'icon'  => 'fas fa-globe-asia',
			'color' => 'globe',
			'title' => 'Web',
		),
		$user_id
	);
	/**
	 * SNSのアイコン作成
	 */
	$sns = ys_get_sns_icons();
	foreach ( $sns as $key => $val ) {
		$key = 'ys_' . $key;
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
 * @param boolean $user_id ユーザーID.
 * @param boolean $wpautop wpautopを効かせるか.
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
		'class' => 'singular-footer__block',
	);
	/**
	 * ショートコード実行
	 */
	ys_do_shortcode( 'ys_author', $param );
}

/**
 * オリジナル画像変換
 *
 * @param string     $avatar      アバター画像.
 * @param string|int $id_or_email ID/メールアドレス.
 * @param int        $size        サイズ.
 * @param string     $default     デフォルト画像種類.
 * @param string     $alt         alt.
 * @param array      $args        args.
 *
 * @return string
 */
function ys_get_avatar( $avatar, $id_or_email, $size, $default, $alt, $args ) {

	/**
	 * Alt取得
	 */
	if ( empty( $alt ) ) {
		$alt = get_the_author_meta( 'display_name', $id_or_email );
	}
	$class = array( 'avatar', 'avatar-' . (int) $args['size'], 'photo' );
	if ( isset( $args['class'] ) ) {
		$class[] = $args['class'];
	}
	/**
	 * オリジナル画像の取得と変換
	 */
	$url = get_user_meta( $id_or_email, 'ys_custom_avatar', true );
	if ( ! empty( $url ) ) {
		$avatar = sprintf(
			"<img alt='%s' src='%s' srcset='%s' class='%s' height='%d' width='%d' %s/>",
			esc_attr( $alt ),
			esc_url( $url ),
			esc_url( $url ) . ' 2x',
			esc_attr( implode( ' ', $class ) ),
			(int) $size,
			(int) $size,
			$args['extra_attr']
		);
	}

	return $avatar;
}

add_filter( 'get_avatar', 'ys_get_avatar', 10, 6 );