<?php
/**
 * フォローボックス ショートコード クラス
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * Class YS_Shortcode_Follow_Box
 */
class YS_Shortcode_Follow_Box extends YS_Shortcode_Base {

	/**
	 * ショートコードパラメーター
	 */
	const SHORTCODE_PARAM = array(
		'class_base'     => 'follow-box',
		'message_top'    => 'この記事が気に入ったらフォロー！',
		'message_bottom' => '',
		'twitter'        => '',
		'facebook'       => '',
		'feedly'         => '',
		'col_sp'         => 1,
		'col_tablet'     => 2,
		'col_pc'         => 2,
		'layout'         => 'normal', // normal or 1col
	);

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
		if ( ! ys_is_active_follow_box() ) {
			return '';
		}
		global $follow_box;
		/**
		 * 展開用データ作成
		 */
		$follow_box = array(
			'image'          => $this->get_image(),
			'message_top'    => $this->get_param( 'message_top' ),
			'message_bottom' => $this->get_param( 'message_bottom' ),
			'follow_list'    => $this->get_follow_list(),
			'class_row'      => $this->get_row_class(),
			'class_col'      => $this->get_col_class(),
		);
		/**
		 * テンプレート拡張
		 */
		$template_type = apply_filters( 'ys_follow_box_template', $template_type );
		/**
		 * ボタン部分のHTML作成
		 */
		ob_start();
		get_template_part( 'template-parts/parts/sns-follow-box', $template_type );
		$content = ob_get_clean();

		return parent::get_html( $content );
	}

	/**
	 * フォローボックスデータ
	 *
	 * @return array
	 */
	private function get_follow_list() {
		$data = array();
		/**
		 * Twitter
		 */
		if ( $this->get_param( 'twitter' ) ) {
			$data[] = array(
				'text'  => 'Twitter',
				'url'   => $this->get_param( 'twitter' ),
				'class' => 'sns-bg--twitter sns-border--twitter',
				'icon'  => '<i class="fab fa-twitter icon-l"></i>',
			);
		}
		/**
		 * Facebook
		 */
		if ( $this->get_param( 'facebook' ) ) {
			$data[] = array(
				'text'  => 'Facebook',
				'url'   => $this->get_param( 'facebook' ),
				'class' => 'sns-bg--facebook sns-border--facebook',
				'icon'  => '<i class="fab fa-facebook-f icon-l"></i>',
			);
		}
		/**
		 * Feedly
		 */
		if ( $this->get_param( 'feedly' ) ) {
			$data[] = array(
				'text'  => 'Feedly',
				'url'   => $this->get_param( 'feedly' ),
				'class' => 'sns-bg--feedly sns-border--feedly',
				'icon'  => '<i class="fas fa-rss icon-l"></i>',
			);
		}

		return apply_filters( 'ys_follow_box_list', $data );
	}

	/**
	 * 画像取得
	 *
	 * @return string;
	 */
	private function get_image() {
		$image = '';
		if ( is_singular() ) {
			$image = get_the_post_thumbnail();
		}
		/**
		 * 無い場合
		 */
		if ( ! $image ) {
			$image = ys_get_ogp_default_image_object();
			if ( $image ) {
				$image = '<img src="' . $image[0] . '" ' . image_hwstring( $image[1], $image[2] ) . '>';
				$image = ys_amp_get_amp_image_tag( $image );
			}
		}

		return $image;
	}

	/**
	 * 行クラス取得
	 *
	 * @return string
	 */
	private function get_row_class() {
		$class = 'flex flex--row flex--a-center -no-gutter -all';

		if ( $this->is_one_col() ) {
			$class .= ' flex--nowrap';
		}

		return $class;
	}

	/**
	 * 列クラス取得
	 *
	 * @return string
	 */
	private function get_col_class() {
		$class = 'text--center flex__col--1';

		if ( ! $this->is_one_col() ) {
			$class .= ' flex__col--md-2';
		}

		return $class;
	}

	/**
	 * 1列表示か判定
	 *
	 * @return bool
	 */
	private function is_one_col() {
		if ( '1col' == $this->get_param( 'layout' ) ) {
			return true;
		}

		return false;
	}


}