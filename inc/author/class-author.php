<?php
/**
 * 投稿者関連
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

/**
 * Class Author
 *
 * @package ystandard
 */
class Author {

	/**
	 * Nonce Action.
	 */
	const NONCE_ACTION = 'ystandard_profile_update';
	/**
	 * Nonce Name.
	 */
	const NONCE_NAME = 'ystandard_profile_update_nonce';

	/**
	 * ショートコードパラメーター
	 */
	const SHORTCODE_ATTR = [
		'default_user_name' => '',
		'user_name'         => '',
	];

	/**
	 * アクション・フィルターの登録
	 */
	public function register() {
		add_filter( 'user_contactmethods', [ $this, 'user_contactmethods' ] );
		add_action( 'show_password_fields', [ $this, 'add_custom_option' ], 10, 2 );
		add_action( 'profile_update', [ $this, 'profile_update' ], 10, 2 );
		add_shortcode( 'ys_author', [ $this, 'do_shortcode' ] );
		add_filter( 'get_avatar', [ $this, '_get_avatar' ], 10, 6 );
		add_action(
			'ys_singular_footer',
			[ $this, 'post_author' ],
			Content::get_footer_priority( 'author' )
		);
	}

	public function post_author() {
		echo $this->do_shortcode( [] );
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
	public function _get_avatar( $avatar, $id_or_email, $size, $default, $alt, $args ) {

		/**
		 * ユーザー編集ページでは操作しない
		 */
		if ( preg_match( '/^(profile\.php|user-edit\.php)/', basename( $_SERVER['REQUEST_URI'] ) ) ) {
			return $avatar;
		}

		/**
		 * Alt取得
		 */
		if ( empty( $alt ) ) {
			$alt = get_the_author_meta( 'display_name', $id_or_email );
		}
		$avatar_url = get_user_meta( $id_or_email, 'ys_custom_avatar', true );
		if ( empty( $avatar_url ) ) {
			return $avatar;
		}
		$avatar_id = attachment_url_to_postid( $avatar_url );

		$custom_avatar = wp_get_attachment_image(
			$avatar_id,
			$size,
			false,
			array_merge( $args, [ 'alt' => $alt ] )
		);
		if ( $custom_avatar ) {
			return $custom_avatar;
		}

		return $avatar;
	}


	/**
	 * 著者情報表示するか
	 *
	 * @return bool
	 */
	public function is_active_author() {
		if ( is_singular() ) {
			/**
			 * 投稿個別設定
			 */
			if ( '1' === Content::get_post_meta( 'ys_hide_author' ) ) {
				return false;
			}
			/**
			 * 投稿ページ
			 */
			if ( is_single() && ! Option::get_option_by_bool( 'ys_show_post_author', true ) ) {
				return false;
			}
			/**
			 * 固定ページ
			 */
			if ( is_page() && ! Option::get_option_by_bool( 'ys_show_page_author', true ) ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * 投稿者ID取得
	 *
	 * @param array $atts ショートコードパラメーター.
	 *
	 * @return int|string
	 */
	public function get_author_id( $atts = [] ) {
		/**
		 * User_name指定
		 */
		if ( isset( $atts['user_name'] ) && ! empty( $atts['user_name'] ) ) {
			$user = get_user_by( 'login', $atts['user_name'] );
			if ( $user ) {
				return $user->ID;
			}
		}
		/**
		 * 詳細ページであれば作成者取得
		 */
		if ( is_singular() ) {
			return get_the_author_meta( 'ID' );
		}
		/**
		 * デフォルトユーザー指定
		 */
		if ( isset( $atts['default_user_name'] ) && ! empty( $atts['default_user_name'] ) ) {
			$user = get_user_by( 'login', $atts['default_user_name'] );
			if ( $user ) {
				return $user->ID;
			}
		}

		return 0;
	}

	/**
	 * アバター画像取得
	 *
	 * @param int $author_id 投稿者ID.
	 *
	 * @return bool|mixed|void
	 */
	public function get_avatar( $author_id ) {
		$avatar = get_avatar( $author_id, 96, '', '', [ 'class' => 'author-box__img' ] );

		return ! $avatar ? '' : $avatar;
	}

	/**
	 * SNSのリストを取得
	 *
	 * @param int $author_id 投稿者ID.
	 *
	 * @return array
	 */
	public function get_sns( $author_id ) {
		$list = [];
		$url  = get_the_author_meta( 'url', $author_id );
		if ( ! empty( $url ) ) {
			$list['url'] = [
				'type'  => 'url',
				'icon'  => Icon::get_icon( 'globe', 'sns-icon' ),
				'color' => 'globe',
				'title' => 'Web',
				'url'   => esc_url_raw( $url ),
			];
		}
		$sns = SNS::get_sns_icons();
		foreach ( $sns as $key => $val ) {
			$meta_key = 'ys_' . $key;
			$url      = get_the_author_meta( $meta_key, $author_id );
			if ( ! empty( $url ) ) {
				$list[ $key ] = [
					'type'  => $key,
					'icon'  => Icon::get_sns_icon( $val['icon'] ),
					'color' => esc_attr( $val['color'] ),
					'title' => esc_attr( $val['title'] ),
					'url'   => esc_url_raw( $url ),
				];
			}
		}

		return $list;
	}

	/**
	 * プロフィール文取得
	 *
	 * @param int $author_id 投稿者ID.
	 *
	 * @return string
	 */
	public function get_description( $author_id ) {
		$dscr = get_the_author_meta( 'description', $author_id );

		if ( empty( $dscr ) ) {
			return '';
		}

		return wpautop( str_replace( [ "\r\n", "\r", "\n" ], "\n\n", $dscr ) );
	}

	/**
	 * ショートコード実行
	 *
	 * @param array $atts パラメーター.
	 *
	 * @return false|string
	 */
	public function do_shortcode( $atts ) {
		$atts = shortcode_atts( self::SHORTCODE_ATTR, $atts );

		if ( ! $this->is_active_author() ) {
			return '';
		}
		$author_id = $this->get_author_id( $atts );
		if ( 0 === $author_id ) {
			return '';
		}
		/**
		 * 表示データ作成
		 */
		$data = [
			'avatar'      => $this->get_avatar( $author_id ),
			'name'        => get_the_author_meta( 'display_name', $author_id ),
			'position'    => get_the_author_meta( 'ys_author_position', $author_id ),
			'sns'         => $this->get_sns( $author_id ),
			'description' => $this->get_description( $author_id ),
		];

		ob_start();
		Template::get_template_part(
			'template-parts/parts/author',
			null,
			[ 'author_data' => $data ]
		);

		return ob_get_clean();
	}

	/**
	 * SNS関連の情報入力域追加
	 *
	 * @param array $wb wb.
	 *
	 * @return array
	 */
	public function user_contactmethods( $wb ) {

		$sns = SNS::get_sns_icons();
		foreach ( $sns as $key => $value ) {
			$wb[ 'ys_' . $key ] = $value['label'];
		}

		return $wb;
	}

	/**
	 * カスタムユーザー画像追加
	 *
	 * @param bool     $show        Whether to show the password fields. Default true.
	 * @param \WP_User $profileuser User object for the current user to edit.
	 *
	 * @return bool
	 */
	public function add_custom_option( $show, $profileuser ) {

		if ( ! preg_match( '/^(profile\.php|user-edit\.php)/', basename( $_SERVER['REQUEST_URI'] ) ) ) {
			return $show;
		}
		$custom_avatar = get_user_meta( $profileuser->ID, 'ys_custom_avatar', true );
		$position      = get_user_meta( $profileuser->ID, 'ys_author_position', true );
		wp_nonce_field( self::NONCE_ACTION, self::NONCE_NAME );
		?>
		<tr>
			<th><label for="ys_custom_avatar">オリジナルプロフィール画像</label></th>
			<td>
				<div class="ys-custom-avatar__select">
					<?php Admin::custom_uploader_control( 'ys_custom_avatar', $custom_avatar ); ?>
					<p class="description">96px×96pxの正方形で表示されます。正方形の画像を用意すると綺麗に表示されます。</p>
				</div>
			</td>
		</tr>
		<tr>
			<th><label for="ys_author_position">肩書</label></th>
			<td>
				<input
					type="text"
					id="ys_author_position"
					name="ys_author_position"
					class="regular-text"
					value="<?php echo esc_attr( $position ); ?>"
				/>
			</td>
		</tr>
		<?php

		return $show;
	}

	/**
	 * カスタムユーザー画像の保存
	 *
	 * @param int      $user_id       User ID.
	 * @param \WP_User $old_user_data Object containing user's data prior to update.
	 */
	public function profile_update( $user_id, $old_user_data ) {
		/**
		 * Nonceチェック.
		 */
		if ( ! Admin::verify_nonce( self::NONCE_NAME, self::NONCE_ACTION ) ) {
			return;
		}
		if ( isset( $_POST['ys_custom_avatar'] ) && ! empty( $_POST['ys_custom_avatar'] ) ) {
			update_user_meta(
				$user_id,
				'ys_custom_avatar',
				esc_url_raw( $_POST['ys_custom_avatar'] )
			);
		} else {
			delete_user_meta( $user_id, 'ys_custom_avatar' );
		}
		if ( isset( $_POST['ys_author_position'] ) && ! empty( $_POST['ys_author_position'] ) ) {
			update_user_meta(
				$user_id,
				'ys_author_position',
				esc_attr( $_POST['ys_author_position'] )
			);
		} else {
			delete_user_meta( $user_id, 'ys_author_position' );
		}
	}

}

$class_author = new Author();
$class_author->register();
