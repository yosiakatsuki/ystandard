<?php
/**
 * シェアボタン ショートコード クラス
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * Class YS_Shortcode_Share_Button
 */
class YS_Shortcode_Share_Button extends YS_Shortcode_Base {
	/**
	 * ショートコードパラメーター
	 */
	const SHORTCODE_PARAM = array(
		'class_base'           => 'share-btn',
		'twitter'              => true,
		'facebook'             => true,
		'hatenabookmark'       => true,
		'pocket'               => true,
		'line'                 => true,
		'feedly'               => true,
		'rss'                  => true,
		'col_sp'               => 3,
		'col_tablet'           => 3,
		'col_pc'               => 6,
		'twitter_via_user'     => '',
		'twitter_related_user' => '',
	);
	/**
	 * URL
	 *
	 * @var string
	 */
	private $share_url = '';
	/**
	 * シェアタイトル
	 *
	 * @var string
	 */
	private $share_title = '';


	/**
	 * YS_Shortcode_Share_Button constructor.
	 *
	 * @param array $args ユーザー指定パラメーター.
	 */
	public function __construct( $args = array() ) {
		parent::__construct( $args, self::SHORTCODE_PARAM );
	}

	/**
	 * HTML取得
	 *
	 * @param string $template_type シェアボタンタイプ.
	 *
	 * @return string
	 */
	public function get_html( $template_type = '' ) {
		global $ys_sns_share_btn_data;
		$ys_sns_share_btn_data = array(
			'title' => $this->get_param( 'title' ),
			'col'   => $this->get_share_btn_col(),
			'data'  => $this->get_share_button_data(),
		);
		/**
		 * [get_html]でのタイトル表示を消す
		 */
		$this->set_title( '' );
		/**
		 * シェアボタンテンプレート拡張
		 */
		$template_type = apply_filters( 'ys_share_btn_template', $template_type );
		/**
		 * ボタン部分のHTML作成
		 */
		ob_start();
		ys_get_template_part( 'template-parts/parts/sns-share-button', $template_type );
		$content = ob_get_clean();

		return parent::get_html( $content );
	}

	/**
	 * シェアボタン表示列数指定クラスの取得
	 *
	 * @return string
	 */
	private function get_share_btn_col() {
		$class = sprintf(
			'flex__col--%s flex__col--md-%s flex__col--lg-%s',
			$this->get_param( 'col_sp' ),
			$this->get_param( 'col_tablet' ),
			$this->get_param( 'col_pc' )
		);

		return apply_filters( 'ys_share_btn_col', $class );
	}

	/**
	 * シェアボタン用データを取得
	 */
	public function get_share_button_data() {
		$data = array();
		/**
		 * URLとタイトルを取得
		 */
		$this->set_share_data();

		/**
		 * Twitter
		 */
		if ( $this->is_active_sns_type( 'twitter' ) ) {
			$data['twitter'] = $this->get_twitter_data();
		}
		/**
		 * Facebook
		 */
		if ( $this->is_active_sns_type( 'facebook' ) ) {
			$data['facebook'] = $this->get_button_data(
				'facebook',
				'fab fa-facebook-f',
				'https://www.facebook.com/sharer.php?src=bm&u=' . $this->share_url . '&t =' . $this->share_title,
				'Facebook'
			);
		}
		/**
		 * Hatenabookmark
		 */
		if ( $this->is_active_sns_type( 'hatenabookmark' ) ) {
			$data['hatenabookmark'] = $this->get_button_data(
				'hatenabookmark',
				'icon-hatenabookmark',
				'https://b.hatena.ne.jp/add?mode=confirm&url=' . $this->share_url,
				'はてブ'
			);
		}
		/**
		 * Pocket
		 */
		if ( $this->is_active_sns_type( 'pocket' ) ) {
			$data['pocket'] = $this->get_button_data(
				'pocket',
				'fab fa-get-pocket',
				'https://getpocket.com/edit?url=' . $this->share_url . '&title=' . $this->share_title,
				'Pocket'
			);
		}
		/**
		 * LINE
		 */
		if ( $this->is_active_sns_type( 'line' ) ) {
			$data['line'] = $this->get_button_data(
				'line',
				'fab fa-line',
				'https://social-plugins.line.me/lineit/share?url=' . $this->share_url,
				'LINE'
			);
		}
		/**
		 * Feedly
		 */
		if ( $this->is_active_sns_type( 'feedly' ) ) {
			$data['feedly'] = $this->get_button_data(
				'feedly',
				'fas fa-rss',
				ys_get_feedly_subscribe_url(),
				'Feedly'
			);
		}
		/**
		 * RSS
		 */
		if ( $this->is_active_sns_type( 'rss' ) ) {
			$data['rss'] = $this->get_button_data(
				'rss',
				'fas fa-rss',
				get_feed_link(),
				'フィード'
			);
		}

		return $data;
	}

	/**
	 * URLとタイトルのセット
	 */
	private function set_share_data() {
		$url   = apply_filters( 'ys_share_btn_url', ys_get_page_url() );
		$title = apply_filters( 'ys_share_btn_title', str_replace( ' &#8211; ', ' - ', wp_get_document_title() ) );
		/**
		 * 変数にセット
		 */
		$this->share_url   = urlencode( $url );
		$this->share_title = urlencode( $title );

	}

	/**
	 * SNSボタンの有効化判断
	 *
	 * @param string $type SNSタイプ.
	 *
	 * @return boolean
	 */
	private function is_active_sns_type( $type ) {
		return apply_filters(
			'ys_share_btn_active_' . $type,
			$this->get_param( $type, 'bool' )
		);
	}

	/**
	 * シェアボタンデータ用配列取得
	 *
	 * @param string $type     タイプ.
	 * @param string $icon     アイコンクラス.
	 * @param string $url      URL.
	 * @param string $btn_text ボタンテキスト.
	 *
	 * @return array
	 */
	public function get_button_data_array( $type, $icon, $url, $btn_text ) {
		return array(
			'type'        => esc_attr( $type ),
			'icon'        => esc_attr( $icon ),
			'url'         => esc_url_raw( $url ),
			'button-text' => esc_html( $btn_text ),
		);
	}

	/**
	 * Twitterボタンデータ
	 */
	public function get_twitter_data() {
		/**
		 * 投稿者アカウント
		 */
		$via         = '';
		$via_account = apply_filters(
			'ys_share_tweet_via_account',
			$this->get_param( 'twitter_via_user' )
		);
		if ( '' !== $via_account ) {
			$via = '&via=' . $via_account;
		}
		/**
		 * おすすめアカウント
		 */
		$related         = '';
		$related_account = apply_filters(
			'ys_share_tweet_related_account',
			$this->get_param( 'twitter_via_user' )
		);
		if ( '' !== $related_account ) {
			$related = '&related=' . $related_account;
		}
		/**
		 * シェアタイトル、URL、ボタンテキストの編集
		 */
		$share_text  = apply_filters( 'ys_share_twitter_text', $this->share_title );
		$share_url   = apply_filters( 'ys_share_twitter_url', $this->share_url );
		$button_text = apply_filters( 'ys_share_twitter_btn_text', 'Twitter' );
		$icon        = apply_filters( 'ys_share_twitter_btn_icon', 'fab fa-twitter' );

		/**
		 * ボタンURLを作成
		 */
		$share_url = sprintf(
			'http://twitter.com/share?text=%s&url=%s%s',
			$share_text,
			$share_url,
			$via . $related
		);

		/**
		 * データ作成
		 */
		return $this->get_button_data_array(
			'twitter',
			$icon,
			$share_url,
			$button_text
		);

	}

	/**
	 * 汎用シェアボタンデータ取得
	 *
	 * @param string $type     タイプ.
	 * @param string $icon     アイコンクラス.
	 * @param string $url      URL.
	 * @param string $btn_text ボタンテキスト.
	 *
	 * @return array
	 */
	public function get_button_data( $type, $icon, $url, $btn_text ) {
		$share_url   = apply_filters( 'ys_share_' . $type . '_url', $url );
		$button_text = apply_filters( 'ys_share_' . $type . '_btn_text', $btn_text );
		$icon        = apply_filters( 'ys_share_' . $type . '_btn_icon', $icon );

		return $this->get_button_data_array(
			$type,
			$icon,
			$share_url,
			$button_text
		);

	}
}
