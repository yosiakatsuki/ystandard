<?php
/**
 * シェアボタン
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

/**
 * Class Share_Button
 *
 * @package ystandard
 */
class Share_Button {

	/**
	 * ショートコード
	 */
	const SHORT_CODE = 'ys_share_button';

	/**
	 * シェアボタンタイプ
	 */
	const TYPE = [
		'circle'   => '円',
		'square'   => '四角',
		'icon'     => 'アイコンのみ',
		'official' => '公式',
		'none'     => '表示しない',
	];

	/**
	 * ショートコードパラメーター
	 */
	const SHORTCODE_ATTR = [
		'type'                 => 'circle',
		'x'                    => true,
		'twitter'              => false,
		'bluesky'              => false,
		'facebook'             => true,
		'hatenabookmark'       => true,
		'pocket'               => true,
		'line'                 => true,
		'twitter_via_user'     => '',
		'twitter_related_user' => '',
		'twitter_hash_tags'    => '',
		'use-option'           => false,
		'before'               => '',
		'after'                => '',
	];

	/**
	 * ショートコードパラメーター
	 *
	 * @var array
	 */
	private $shortcode_atts = [];

	/**
	 * シェアボタン表示用データ
	 *
	 * @var array
	 */
	private $data = [];

	/**
	 * URL
	 *
	 * @var string
	 */
	private $share_url = '';

	/**
	 * Title
	 *
	 * @var string
	 */
	private $share_title = '';

	/**
	 * フックやショートコードの登録
	 */
	public function register() {
		add_action( 'customize_register', [ $this, 'customize_register' ] );
		add_action( 'ys_set_singular_content', [ $this, 'set_singular_content' ] );
		if ( ! shortcode_exists( self::SHORT_CODE ) ) {
			add_shortcode( self::SHORT_CODE, [ $this, 'do_shortcode' ] );
		}
	}

	/**
	 * ヘッダー・フッター コンテンツのセット
	 */
	public function set_singular_content() {
		Content::set_singular_header(
			'sns-share',
			[ __CLASS__, 'header_share_button' ]
		);
		Content::set_singular_footer(
			'sns-share',
			[ __CLASS__, 'footer_share_button' ]
		);
	}

	/**
	 * シェアボタン表示判断
	 *
	 * @return bool
	 */
	private static function is_active_share_buttons() {
		$post_type = Content::get_post_type();
		$filter    = apply_filters( "ys_{$post_type}_active_share_buttons", null );
		if ( ! is_null( $filter ) ) {
			return Utility::to_bool( $filter );
		}
		if ( is_singular() ) {
			return ! Utility::to_bool( Content::get_post_meta( 'ys_hide_share' ) );
		}

		return true;
	}

	/**
	 * ヘッダー側シェアボタン
	 */
	public static function header_share_button() {
		if ( ! self::is_active_share_buttons() ) {
			return;
		}
		echo Utility::do_shortcode(
			self::SHORT_CODE,
			self::get_share_button_settings( 'header', 'none' )
		);
	}

	/**
	 * フッター側シェアボタン
	 */
	public static function footer_share_button() {
		if ( ! self::is_active_share_buttons() ) {
			return;
		}
		echo Utility::do_shortcode(
			self::SHORT_CODE,
			self::get_share_button_settings( 'footer', 'circle' )
		);
	}


	/**
	 * コンテンツ ヘッダー・フッター表示用 設定取得
	 *
	 * @param string $position header,footer.
	 * @param string $default  Default.
	 *
	 * @return array
	 */
	private static function get_share_button_settings( $position, $default ) {

		$post_type = Content::get_post_type();
		$type      = apply_filters(
			"ys_{$post_type}_share_button_type_{$position}",
			Option::get_option( "ys_share_button_type_{$position}", $default )
		);

		$settings = [
			'type'       => $type,
			'use-option' => true,
		];

		if ( 'footer' === $position ) {
			$settings['before'] = Option::get_option( 'ys_share_button_before', '' );
			$settings['after']  = Option::get_option( 'ys_share_button_after', '' );
		}

		return $settings;
	}

	/**
	 * ショートコード実行
	 *
	 * @param array $atts    Attributes.
	 * @param null  $content Content.
	 *
	 * @return string
	 */
	public function do_shortcode( $atts, $content = null ) {
		$this->shortcode_atts = shortcode_atts( self::SHORTCODE_ATTR, $atts );

		if ( 'none' === $this->shortcode_atts['type'] ) {
			return '';
		}
		if ( ! array_key_exists( $this->shortcode_atts['type'], self::TYPE ) ) {
			return '';
		}

		$this->data = [];

		$this->set_page();
		$this->set_x();
		$this->set_twitter();
		$this->set_bluesky();
		$this->set_facebook();
		$this->set_hatenabookmark();
		$this->set_pocket();
		$this->set_line();
		$this->set_text();
		$this->enqueue_script();

		if ( ! isset( $this->data['sns'] ) || empty( $this->data['sns'] ) ) {
			return '';
		}

		ob_start();
		Template::get_template_part(
			'template-parts/parts/share-button',
			$this->shortcode_atts['type'],
			[ 'share_button' => $this->data ]
		);

		return ob_get_clean();
	}

	/**
	 * 公式ボタン用スクリプト読み込み
	 */
	private function enqueue_script() {
		if ( 'official' !== $this->shortcode_atts['type'] ) {
			return;
		}
		if ( $this->is_active_button( 'twitter' ) || $this->is_active_button( 'x' ) ) {
			wp_enqueue_script(
				'share-button-twitter',
				'https://platform.twitter.com/widgets.js',
				[],
				null,
				true
			);
			Enqueue_Utility::add_async( 'share-button-twitter' );
		}
		if ( $this->is_active_button( 'facebook' ) ) {
			$sdk_ver = SNS::FACEBOOK_API_VERSION;
			wp_enqueue_script(
				'share-button-facebook',
				"https://connect.facebook.net/ja_JP/sdk.js#xfbml=1&version={$sdk_ver}",
				[],
				null,
				true
			);
			Enqueue_Utility::add_async( 'share-button-facebook' );
			Enqueue_Utility::add_defer( 'share-button-facebook' );
			Enqueue_Utility::add_crossorigin( 'share-button-facebook', 'anonymous' );
		}
		if ( $this->is_active_button( 'hatenabookmark' ) ) {
			wp_enqueue_script(
				'share-button-hatenabookmark',
				'https://b.st-hatena.com/js/bookmark_button.js',
				[],
				null,
				true
			);
			Enqueue_Utility::add_async( 'share-button-hatenabookmark' );
		}
		if ( $this->is_active_button( 'pocket' ) ) {
			wp_enqueue_script(
				'share-button-pocket',
				'https://widgets.getpocket.com/v1/j/btn.js?v=1',
				[],
				null,
				true
			);
			Enqueue_Utility::add_async( 'share-button-pocket' );
		}
		if ( $this->is_active_button( 'line' ) ) {
			wp_enqueue_script(
				'share-button-line',
				'https://d.line-scdn.net/r/web/social-plugin/js/thirdparty/loader.min.js',
				[],
				null,
				true
			);
			Enqueue_Utility::add_async( 'share-button-line' );
			Enqueue_Utility::add_defer( 'share-button-line' );
		}

	}

	/**
	 * ページ情報セット
	 */
	private function set_page() {
		$url   = apply_filters( 'ys_share_btn_url', Utility::get_page_url() );
		$title = apply_filters( 'ys_share_btn_title', Utility::get_page_title() );
		// 変数にセット.
		$this->share_url   = rawurlencode( $url );
		$this->share_title = rawurlencode( html_entity_decode( $title ) );
		/**
		 * URLやTitleのセット
		 */
		$this->data['official']['url-encode']   = $this->share_url;
		$this->data['official']['title-encode'] = $this->share_title;
		$this->data['official']['url']          = $url;
		$this->data['official']['title']        = $title;
	}

	/**
	 * 前後のテキストセット
	 */
	private function set_text() {
		$this->data['text']['before'] = $this->shortcode_atts['before'];
		$this->data['text']['after']  = $this->shortcode_atts['after'];
	}

	/**
	 * LINE
	 */
	private function set_line() {
		if ( $this->is_use_option() ) {
			if ( ! $this->is_active_button( 'line' ) ) {
				return;
			}
		} else {
			if ( ! $this->shortcode_atts['line'] ) {
				return;
			}
		}
		// Url.
		$url = $this->share_url;
		/**
		 * URL作成
		 */
		$this->data['sns']['line'] = "https://social-plugins.line.me/lineit/share?url={$url}";
	}

	/**
	 * Pocket
	 */
	private function set_pocket() {
		if ( $this->is_use_option() ) {
			if ( ! $this->is_active_button( 'pocket' ) ) {
				return;
			}
		} else {
			if ( ! $this->shortcode_atts['pocket'] ) {
				return;
			}
		}
		// Title,Url.
		$title = $this->share_title;
		$url   = $this->share_url;
		/**
		 * URL作成
		 */
		$this->data['sns']['pocket'] = "https://getpocket.com/edit?url={$url}&title={$title}";
	}

	/**
	 * はてブ
	 */
	private function set_hatenabookmark() {
		if ( $this->is_use_option() ) {
			if ( ! $this->is_active_button( 'hatenabookmark' ) ) {
				return;
			}
		} else {
			if ( ! $this->shortcode_atts['hatenabookmark'] ) {
				return;
			}
		}
		// Url.
		$url = $this->share_url;
		/**
		 * URL作成
		 */
		$this->data['sns']['hatenabookmark'] = "https://b.hatena.ne.jp/add?mode=confirm&url={$url}";
	}

	/**
	 * Bluesky
	 */
	private function set_bluesky() {
		if ( $this->is_use_option() ) {
			if ( ! $this->is_active_button( 'bluesky', false ) ) {
				return;
			}
		} else {
			if ( ! $this->shortcode_atts['bluesky'] ) {
				return;
			}
		}
		// Title,Url.
		$title = $this->share_title;
		$url   = $this->share_url;
		/**
		 * URL作成
		 */
		$this->data['sns']['bluesky'] = "https://bsky.app/intent/compose?text\"={$title} {$url}\"";
	}

	/**
	 * Facebook
	 */
	private function set_facebook() {
		if ( $this->is_use_option() ) {
			if ( ! $this->is_active_button( 'facebook' ) ) {
				return;
			}
		} else {
			if ( ! $this->shortcode_atts['facebook'] ) {
				return;
			}
		}
		// Title,Url.
		$title = $this->share_title;
		$url   = $this->share_url;
		/**
		 * URL作成
		 */
		$this->data['sns']['facebook'] = "https://www.facebook.com/sharer.php?src=bm&u={$url}&t={$title}";
	}

	/**
	 * X
	 */
	private function set_x() {
		if ( $this->is_use_option() ) {
			$x_default = ! $this->is_active_button( 'twitter', false );
			if ( ! $this->is_active_button( 'x', $x_default ) ) {
				return;
			}
		} else {
			if ( ! $this->shortcode_atts['x'] ) {
				return;
			}
		}
		$this->set_x_share_url( 'x' );
	}

	/**
	 * Twitter
	 */
	private function set_twitter() {
		if ( $this->is_use_option() ) {
			if ( ! $this->is_active_button( 'twitter', false ) ) {
				return;
			}
		} else {
			if ( ! $this->shortcode_atts['twitter'] ) {
				return;
			}
		}
		$this->set_x_share_url( 'twitter' );
	}

	/**
	 * X,TwitterのURLをセット
	 *
	 * @param string $type Twitter/x.
	 *
	 * @return void
	 */
	private function set_x_share_url( $type = 'x' ) {
		if ( $this->is_use_option() ) {
			$via       = Option::get_option( 'ys_sns_share_tweet_via_account', '' );
			$related   = Option::get_option( 'ys_sns_share_tweet_related_account', '' );
			$hash_tags = '';
		} else {
			$via       = $this->shortcode_atts['twitter_via_user'];
			$related   = $this->shortcode_atts['twitter_related_user'];
			$hash_tags = $this->shortcode_atts['twitter_hash_tags'];
		}
		// オフィシャル用.
		$this->data['official']['twitter-via']     = $via;
		$this->data['official']['twitter-related'] = $related;
		// via.
		$via = empty( $via ) ? '' : "&via={$via}";
		// related.
		$related = empty( $related ) ? '' : "&related={$related}";
		// hash tags.
		$hash_tags = empty( $hash_tags ) ? '' : "&hashtags={$hash_tags}";
		// Title,Url.
		$title = $this->share_title;
		$url   = $this->share_url;
		/**
		 * URL作成
		 */
		$this->data['sns'][ $type ] = "https://twitter.com/intent/tweet?text={$title}&url={$url}{$via}{$related}{$hash_tags}";
	}

	/**
	 * 設定を使うか
	 *
	 * @return bool
	 */
	private function is_use_option() {
		return Utility::to_bool( $this->shortcode_atts['use-option'] );
	}

	/**
	 * SNSボタンを表示する設定になっているか
	 *
	 * @param string $sns     name.
	 * @param bool   $default default.
	 *
	 * @return bool
	 */
	private function is_active_button( $sns, $default = true ) {
		return Option::get_option_by_bool( "ys_sns_share_button_{$sns}", $default );
	}

	/**
	 * 設定追加
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 */
	public function customize_register( $wp_customize ) {
		$customizer = new Customize_Control( $wp_customize );

		$customizer->add_section(
			[
				'section'     => 'ys_share_button',
				'title'       => 'SNSシェアボタン',
				'priority'    => 100,
				'panel'       => SNS::PANEL_NAME,
				'description' => Admin::manual_link( 'manual/sns-share-button' ),
			]
		);
		$customizer->add_section_label( '表示するボタン' );
		// X.
		$customizer->add_checkbox(
			[
				'id'      => 'ys_sns_share_button_x',
				'default' => ! Option::get_option_by_bool( 'ys_sns_share_button_twitter' ),
				'label'   => 'X',
			]
		);
		// Twitter.
		$customizer->add_checkbox(
			[
				'id'      => 'ys_sns_share_button_twitter',
				'default' => 0,
				'label'   => '旧Twitter',
			]
		);
		// Bluesky.
		$customizer->add_checkbox(
			[
				'id'      => 'ys_sns_share_button_bluesky',
				'default' => 0,
				'label'   => 'Bluesky',
			]
		);
		// Facebook.
		$customizer->add_checkbox(
			[
				'id'      => 'ys_sns_share_button_facebook',
				'default' => 1,
				'label'   => 'Facebook',
			]
		);
		// はてブ.
		$customizer->add_checkbox(
			[
				'id'      => 'ys_sns_share_button_hatenabookmark',
				'default' => 1,
				'label'   => 'はてなブックマーク',
			]
		);
		// Pocket.
		$customizer->add_checkbox(
			[
				'id'      => 'ys_sns_share_button_pocket',
				'default' => 1,
				'label'   => 'Pocket',
			]
		);
		// LINE.
		$customizer->add_checkbox(
			[
				'id'      => 'ys_sns_share_button_line',
				'default' => 1,
				'label'   => 'LINE',
			]
		);

		$customizer->add_section_label( 'ボタン表示タイプ' );
		// 記事上表示タイプ.
		$customizer->add_select(
			[
				'id'      => 'ys_share_button_type_header',
				'default' => 'none',
				'label'   => '記事上のシェアボタン表示タイプ',
				'choices' => self::TYPE,
			]
		);
		// 記事下表示タイプ.
		$customizer->add_select(
			[
				'id'      => 'ys_share_button_type_footer',
				'default' => 'circle',
				'label'   => '記事下のシェアボタン表示タイプ',
				'choices' => self::TYPE,
			]
		);
		$customizer->add_section_label(
			'シェアボタン上下テキスト',
			[
				'description' => '記事下シェアボタンの上下に表示するテキストの設定',
			]
		);
		$customizer->add_text(
			[
				'id'      => 'ys_share_button_before',
				'default' => '',
				'label'   => 'シェアボタンの上に表示するテキスト',
			]
		);
		$customizer->add_text(
			[
				'id'      => 'ys_share_button_after',
				'default' => '',
				'label'   => 'シェアボタンの下に表示するテキスト',
			]
		);

		$customizer->add_section_label(
			'X(旧Twitter)シェアボタン詳細設定',
			[
				'description' => Admin::manual_link( 'manual/twitter-share-button' ),
			]
		);
		// Viaに設定するTwitterアカウント名.
		$customizer->add_text(
			[
				'id'          => 'ys_sns_share_tweet_via_account',
				'default'     => '',
				'transport'   => 'postMessage',
				'label'       => '投稿ユーザー（via）',
				'description' => '「@」なしのユーザー名を入力して下さい。',
				'input_attrs' => [
					'placeholder' => 'username',
				],
			]
		);
		/**
		 * ツイート後に表示するおすすめアカウント
		 */
		$customizer->add_text(
			[
				'id'          => 'ys_sns_share_tweet_related_account',
				'default'     => '',
				'transport'   => 'postMessage',
				'label'       => 'ツイート後に表示するおすすめアカウント',
				'description' => '「@」なしのユーザー名を入力して下さい。',
				'input_attrs' => [
					'placeholder' => 'username',
				],
			]
		);
	}
}

$class_share_button = new Share_Button();
$class_share_button->register();
