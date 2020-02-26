<?php
/**
 * 投稿者一覧表示 ショートコード クラス
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * Class YS_Shortcode_Author_List
 */
class YS_Shortcode_Author_List extends YS_Shortcode_Base {

	/**
	 * ショートコードパラメーター
	 */
	const SHORTCODE_PARAM = array(
		'class_base'            => 'author__list',
		'exclude'               => '',
		'enable_archive_link'   => true,
		'enable_archive_button' => true,
		'archive_button_text'   => '記事一覧',
		'show_avatar'           => true,
		'layout'                => 'normal', // normal or 1col.
		'author_name_tag'       => '',
		'col'                   => 1,
		'col_sp'                => '',
		'col_tablet'            => '',
		'col_pc'                => '',
	);

	/**
	 * Constructor.
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
		/**
		 * ユーザー一覧
		 */
		$users      = $this->get_user_list();
		$sc_content = '';
		foreach ( $users as $user ) {
			$sc_args = array(
				'class'                 => $this->get_col_class(),
				'user_name'             => $user->data->user_login,
				'enable_archive_link'   => $this->get_param( 'enable_archive_link' ),
				'enable_archive_button' => $this->get_param( 'enable_archive_button' ),
				'archive_button_text'   => $this->get_param( 'archive_button_text' ),
				'show_avatar'           => $this->get_param( 'show_avatar' ),
				'mode'                  => 'shortcode',
				'layout'                => $this->get_param( 'layout' ), // normal or 1col.
				'author_name_tag'       => $this->get_param( 'author_name_tag' ),
			);
			/**
			 * ショートコード実行
			 */
			$sc_content .= ys_do_shortcode(
				'ys_author',
				$sc_args,
				null,
				false
			);
		}
		$content = sprintf(
			'<div class="flex flex--row">%s</div>',
			$sc_content
		);

		return parent::get_html( $content );
	}

	/**
	 * 除外ユーザーリストの作成
	 *
	 * @return array
	 */
	private function get_exclude_users() {
		$exclude_id   = array();
		$exclude_name = explode( ',', $this->get_param( 'exclude' ) );
		foreach ( $exclude_name as $value ) {
			$user = get_user_by( 'slug', $value );
			if ( $user ) {
				$exclude_id[] = $user->ID;
			}
		}

		return $exclude_id;
	}

	/**
	 * ユーザー一覧取得
	 */
	private function get_user_list() {
		/**
		 * ユーザーデータ取得パラメータ
		 */
		$user_args = array(
			'orderby' => 'ID',
			'order'   => 'ASC',
		);
		/**
		 * 除外
		 */
		$exclude = $this->get_exclude_users();
		if ( ! empty( $exclude ) ) {
			$user_args = wp_parse_args(
				array( 'exclude' => $exclude ),
				$user_args
			);
		}

		return get_users( $user_args );
	}

	/**
	 * 列クラス取得
	 *
	 * @return string
	 */
	private function get_col_class() {
		/**
		 * カラムチェック
		 */
		$this->check_col();
		/**
		 * クラス作成
		 */
		$class   = array();
		$class[] = sprintf(
			'flex__col--%s flex__col--md-%s flex__col--lg-%s',
			$this->get_param( 'col_sp' ),
			$this->get_param( 'col_tablet' ),
			$this->get_param( 'col_pc' )
		);

		return trim( implode( ' ', $class ) );
	}
}