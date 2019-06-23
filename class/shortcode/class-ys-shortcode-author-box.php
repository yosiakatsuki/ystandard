<?php
/**
 * 投稿者表示 ショートコード クラス
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * Class YS_Shortcode_Author
 */
class YS_Shortcode_Author_Box extends YS_Shortcode_Base {

	/**
	 * ショートコードパラメーター
	 */
	const SHORTCODE_PARAM = array(
		'class_base'            => 'author__box',
		'default_user_name'     => false,
		'user_name'             => false,
		'enable_archive_link'   => true,
		'enable_archive_button' => true,
		'archive_button_text'   => '記事一覧',
		'show_avatar'           => true,
		'mode'                  => 'shortcode',
		'layout'                => 'normal', // normal or 1col.
		'author_name_tag'       => '',
	);
	/**
	 * 投稿者一覧ページURL
	 *
	 * @var string 投稿者一覧ページURL.
	 */
	private $author_archive_url = '';

	/**
	 * constructor.
	 *
	 * @param array $args ユーザー指定パラメーター.
	 */
	public function __construct( $args = array() ) {
		parent::__construct( $args, self::SHORTCODE_PARAM );
	}

	/**
	 * HTML取得
	 *
	 * @param string $template_type テンプレートタイプ.
	 *
	 * @return string
	 */
	public function get_html( $template_type = '' ) {
		global $ys_author_data;
		$ys_author_data = false;
		/**
		 * ショートコードモード以外では設定によって出し分けする
		 */
		if ( 'shortcode' !== $this->get_param( 'mode' ) && ! ys_is_display_author_data() ) {
			return '';
		}
		/**
		 * 投稿者IDを取得
		 */
		$user_id = $this->get_user_id();
		/**
		 * 投稿者一覧ページURL取得
		 */
		$this->author_archive_url = $this->get_author_archive_url( $user_id );
		/**
		 * 画像取得
		 */
		$avatar = $this->get_avatar( $user_id );
		/**
		 * 展開用データ作成
		 */
		$ys_author_data = array(
			'avatar'           => $avatar,
			'name'             => $this->get_name( $user_id ),
			'sns'              => $this->get_sns( $user_id ),
			'profile'          => $this->get_profile( $user_id ),
			'class_row'        => $this->get_row_class(),
			'class_avatar_col' => $this->get_avatar_col(),
			'class_text_col'   => $this->get_text_col(),
			'archive_url'      => $this->author_archive_url,
			'archive_button'   => $this->get_archive_button(),
		);
		/**
		 * テンプレート拡張
		 */
		$template_type = apply_filters( 'ys_author_box_template', $template_type );
		/**
		 * ボタン部分のHTML作成
		 */
		ob_start();
		get_template_part( 'template-parts/parts/author-box', $template_type );
		$content = ob_get_clean();

		return parent::get_html( $content );
	}


	/**
	 * 投稿者IDを取得
	 *
	 * @return int|string
	 */
	private function get_user_id() {
		$user_id = '';
		/**
		 * 個別投稿であれば投稿者を取得
		 */
		if ( is_singular() ) {
			$user_id = get_the_author_meta( 'ID' );
		}
		/**
		 * User_nameが設定されていればユーザー名からauthor_idを取得
		 */
		$user_name = $this->get_param( 'user_name' );
		if ( false !== $user_name ) {
			$user = get_user_by( 'slug', $user_name );
			if ( $user ) {
				$user_id = $user->ID;
			}
		}
		/**
		 * デフォルトユーザーが指定されていれば表示する
		 */
		$default_user_name = $this->get_param( 'default_user_name' );
		if ( '' === $user_id && false !== $default_user_name ) {
			$user = get_user_by( 'slug', $default_user_name );
			if ( $user ) {
				$user_id = $user->ID;
			}
		}

		return $user_id;
	}

	/**
	 * 投稿者一覧ページURL取得
	 *
	 * @param int $user_id Author ID.
	 *
	 * @return string
	 */
	private function get_author_archive_url( $user_id ) {
		return ys_get_author_link( $user_id );
	}

	/**
	 * アバターの取得
	 *
	 * @param int $user_id Author ID.
	 *
	 * @return string
	 */
	private function get_avatar( $user_id ) {
		$avatar = '';
		if ( $this->get_param( 'show_avatar' ) ) {
			$avatar = ys_get_author_avatar( $user_id, 96, array( 'author-box__img' ) );
		}
		/**
		 * 一覧ページへのリンクを付ける
		 */
		if ( ys_sanitize_bool( $this->get_param( 'enable_archive_link' ) ) && $avatar ) {
			$avatar = sprintf(
				'<a href="%s" rel="author">%s</a>',
				$this->author_archive_url,
				$avatar
			);
		}

		return $avatar;
	}

	/**
	 * ユーザー名の取得
	 *
	 * @param int $user_id Author ID.
	 *
	 * @return string
	 */
	private function get_name( $user_id ) {
		$link = $this->author_archive_url;
		if ( ! ys_sanitize_bool( $this->get_param( 'enable_archive_link' ) ) ) {
			$link = false;
		}
		$name = ys_get_author_name( $user_id, $link );

		/**
		 * HTMLタグの決定
		 */
		$author_name_tag = $this->get_param( 'author_name_tag' );
		if ( '' === $author_name_tag ) {
			/**
			 * 未指定かつタイトルなしの場合はh2
			 */
			if ( '' === $this->get_param( 'title' ) ) {
				$author_name_tag = 'h2';
			} else {
				$author_name_tag = 'h3';
			}
		}
		/**
		 * HTML作成
		 */
		$name = sprintf(
			'<%s class="author-box__name clear-h text--b">%s</%s>',
			$author_name_tag,
			$name,
			$author_name_tag
		);

		return $name;
	}

	/**
	 * SNSの取得
	 *
	 * @param int $user_id Author ID.
	 *
	 * @return array
	 */
	private function get_sns( $user_id ) {
		return ys_get_author_sns_list( $user_id );
	}

	/**
	 * プロフィールの取得
	 *
	 * @param int $user_id Author ID.
	 *
	 * @return string
	 */
	private function get_profile( $user_id ) {
		return ys_get_author_description( $user_id );
	}

	/**
	 * レイアウト判断
	 *
	 * @return string
	 */
	private function is_one_col() {
		if ( '1col' === $this->get_param( 'layout' ) ) {
			return false;
		}

		return true;
	}

	/**
	 * アバター表示域のクラス取得
	 *
	 * @return string
	 */
	private function get_row_class() {
		$class = 'flex flex--row';

		if ( $this->is_one_col() ) {
			$class .= ' flex--md-nowrap';
		}

		return $class;
	}

	/**
	 * アバター表示域のクラス取得
	 *
	 * @return string
	 */
	private function get_avatar_col() {
		$class = 'text--center flex__col--1';

		if ( $this->is_one_col() ) {
			$class .= ' flex__col--md-auto';
		}

		return $class;
	}

	/**
	 * アバター表示域のクラス取得
	 *
	 * @return string
	 */
	private function get_text_col() {
		$class = 'text--center flex__col';

		if ( $this->is_one_col() ) {
			$class .= ' text--md-left';
		}

		return $class;
	}

	/**
	 * ボタン表示有無・テキスト取得
	 *
	 * @return string|bool
	 */
	private function get_archive_button() {

		if ( ! ys_sanitize_bool( $this->get_param( 'enable_archive_button' ) ) ) {
			return false;
		}
		if ( is_author() ) {
			return false;
		}

		return $this->get_param( 'archive_button_text' );
	}

}