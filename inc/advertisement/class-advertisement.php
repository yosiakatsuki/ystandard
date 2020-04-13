<?php
/**
 * 広告
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

/**
 * Class Advertisement
 *
 * @package ystandard
 */
class Advertisement {
	/**
	 * ショートコードパラメーター
	 */
	const SHORTCODE_ATTR = [
		'title' => 'スポンサーリンク',
	];

	/**
	 * フックやショートコードの登録
	 */
	public function register() {
		add_action( 'ys_archive_after_content', [ $this, 'archive_infeed' ] );
		add_action( 'ys_singular_before_title', [ $this, 'before_title' ] );
		add_action( 'ys_singular_after_title', [ $this, 'after_title' ] );
		add_action( 'after_setup_theme', [ $this, 'set_singular_content' ] );
		add_filter( 'ys_more_content', [ $this, 'more_ad' ] );

		/**
		 * 設定追加
		 */
		add_action( 'customize_register', [ $this, 'customize_register' ] );
//		add_action( 'customize_preview_init', [ $this, 'fix_preview_error' ] );
		/**
		 * ショートコード
		 */
		if ( ! shortcode_exists( 'ys_ad_block' ) ) {
			add_shortcode( 'ys_ad_block', [ $this, 'do_shortcode' ] );
		}
		/**
		 * ウィジェット
		 */
		add_action( 'widgets_init', [ $this, 'register_widget' ] );
	}

	/**
	 * ヘッダー・フッター コンテンツのセット
	 */
	public function set_singular_content() {
		add_action(
			'ys_singular_header',
			[ $this, 'header_ad' ],
			Content::get_header_priority( 'ad' )
		);
		add_action(
			'ys_singular_footer',
			[ $this, 'footer_ad' ],
			Content::get_footer_priority( 'ad' )
		);
	}

	/**
	 * タイトル前広告
	 */
	public function before_title() {
		$key = 'ys_advertisement_before_title';
		if ( AMP::is_amp() ) {
			$key = 'ys_amp_advertisement_before_title';
		} else {
			if ( Template::is_mobile() ) {
				$key = 'ys_advertisement_before_title_sp';
			}
		}
		$ad = empty( $key ) ? '' : Option::get_option( $key, '' );

		echo self::format_advertisement( $ad, '', 'is-before-title' );
	}

	/**
	 * タイトル後広告
	 */
	public function after_title() {
		$key = 'ys_advertisement_after_title';
		if ( AMP::is_amp() ) {
			$key = 'ys_amp_advertisement_after_title';
		} else {
			if ( Template::is_mobile() ) {
				$key = 'ys_advertisement_after_title_sp';
			}
		}
		$ad = empty( $key ) ? '' : Option::get_option( $key, '' );

		echo self::format_advertisement( $ad, '', 'is-after-title' );
	}

	/**
	 * 記事上広告
	 */
	public function header_ad() {

		$key = 'ys_advertisement_before_content';
		if ( AMP::is_amp() ) {
			$key = 'ys_amp_advertisement_before_content';
		} else {
			if ( Template::is_mobile() ) {
				$key = 'ys_advertisement_before_content_sp';
			}
		}
		$ad = empty( $key ) ? '' : Option::get_option( $key, '' );

		echo self::format_advertisement( $ad, self::get_ads_label() );
	}

	/**
	 * Moreタグ部分への広告表示
	 *
	 * @param string $content More Content.
	 *
	 * @return string
	 */
	public function more_ad( $content ) {

		$key = 'ys_advertisement_replace_more';
		if ( AMP::is_amp() ) {
			$key = 'ys_amp_advertisement_replace_more';
		} else {
			if ( Template::is_mobile() ) {
				$key = 'ys_advertisement_replace_more_sp';
			}
		}
		$ad = empty( $key ) ? '' : Option::get_option( $key, '' );

		if ( $ad ) {
			$content = self::format_advertisement( $ad, self::get_ads_label() ) . $content;
		}

		return $content;
	}

	/**
	 * 記事下広告
	 */
	public function footer_ad() {
		$ad        = '';
		$key_left  = 'ys_advertisement_under_content_left';
		$key_right = 'ys_advertisement_under_content_right';

		if ( AMP::is_amp() ) {
			$key_left  = 'ys_amp_advertisement_under_content';
			$key_right = '';
		} else {
			if ( Template::is_mobile() ) {
				$key_left  = 'ys_advertisement_under_content_sp';
				$key_right = '';
			}
		}
		$ad_left  = Option::get_option( $key_left, '' );
		$ad_right = empty( $key_right ) ? '' : Option::get_option( $key_right, '' );
		if ( '' !== $ad_left && '' !== $ad_right ) {
			$ad = sprintf(
				'<div class="ys-ad__double">
					<div class="ys-ad__left">%s</div>
					<div class="ys-ad__right">%s</div>
				</div>',
				$ad_left,
				$ad_right
			);
		} else {
			$ad = ! empty( $ad_left ) ? $ad_left : $ad_right;
		}

		echo self::format_advertisement( $ad, self::get_ads_label() );
	}

	/**
	 * インフィード広告
	 */
	public function archive_infeed() {
		if ( Template::is_mobile() ) {
			$step  = Option::get_option_by_int( 'ys_advertisement_infeed_sp_step', 3 );
			$limit = Option::get_option_by_int( 'ys_advertisement_infeed_sp_limit', 3 );
		} else {
			$step  = Option::get_option_by_int( 'ys_advertisement_infeed_pc_step', 3 );
			$limit = Option::get_option_by_int( 'ys_advertisement_infeed_pc_limit', 3 );
		}
		global $wp_query;
		$num = $wp_query->current_post + 1;
		if ( 0 === ( $num % $step ) && $limit >= ( $num / $step ) ) {
			if ( '' !== self::get_infeed() ) {
				ys_get_template_part( 'template-parts/archive/infeed' );
			}
		}
	}

	/**
	 * インフィード広告取得
	 *
	 * @return string
	 */
	public static function get_infeed() {
		if ( Template::is_mobile() ) {
			$ad = Option::get_option( 'ys_advertisement_infeed_sp', '' );
		} else {
			$ad = Option::get_option( 'ys_advertisement_infeed_pc', '' );
		}

		return $ad;
	}

	/**
	 * 広告表示用HTML取得
	 *
	 * @param string $content Advertisement.
	 * @param string $title   Title.
	 * @param string $class   Css class.
	 *
	 * @return string
	 */
	public static function format_advertisement( $content, $title = '', $class = '' ) {
		/**
		 * 表示チェック
		 */
		if ( ! self::is_active_advertisement() ) {
			return '';
		}

		if ( $title ) {
			$title = sprintf( '<div class="ys-ad__title">%s</div>', $title );
		}

		$content = is_null( $content ) ? '' : do_shortcode( $content );

		if ( empty( $content ) ) {
			return '';
		}

		/**
		 * 特殊文字変換
		 */
		$content = strtr(
			$content,
			[
				'&#x5b;' => '[',
				'&#x5d;' => ']',
			]
		);

		/**
		 * クラス
		 */
		$wrap_class = array_merge(
			[ 'ys-ad-content' ],
			[ $class ]
		);

		return sprintf(
			'<div class="%s">%s%s</div>',
			trim( implode( ' ', $wrap_class ) ),
			$title,
			$content
		);
	}

	/**
	 * 広告ラベルを取得
	 *
	 * @return string
	 */
	public static function get_ads_label() {
		return Option::get_option( 'ys_advertisement_ads_label', 'スポンサーリンク' );
	}

	/**
	 * 広告表示チェック
	 *
	 * @return bool
	 */
	public static function is_active_advertisement() {
		if ( is_404() ) {
			return false;
		}
		/**
		 * 検索結果なし
		 */
		global $wp_query;
		if ( is_search() && 0 === $wp_query->found_posts ) {
			return false;
		}
		/**
		 * 非表示設定
		 */
		if ( is_singular() ) {
			if ( Utility::to_bool( Content::get_post_meta( 'ys_hide_ad' ) ) ) {
				return false;
			}
		}

		return true;
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

		$atts = shortcode_atts( self::SHORTCODE_ATTR, $atts );

		return self::format_advertisement( $content, $atts['title'] );
	}

	/**
	 * プレビュー画面での Adsense エラー対処
	 */
	public function fix_preview_error() {
		wp_enqueue_script(
			'google-ads-preview',
			'//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js',
			[],
			null,
			true
		);
	}

	/**
	 * ウィジェット登録
	 */
	public function register_widget() {
		\YS_Loader::require_file( __DIR__ . '/class-ys-widget-advertisement.php' );
		register_widget( 'YS_Widget_Advertisement' );
	}

	/**
	 * 設定追加
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 */
	public function customize_register( $wp_customize ) {
		$customizer = new Customize_Control( $wp_customize );

		$customizer->add_panel(
			[
				'panel' => 'ys_advertisement',
				'title' => '[ys]広告',
			]
		);
		$customizer->add_section(
			[
				'section' => 'ys_ads_common',
				'title'   => '広告共通設定',
			]
		);
		/**
		 * 広告ラベル
		 */
		$customizer->add_text(
			[
				'id'          => 'ys_advertisement_ads_label',
				'default'     => 'スポンサーリンク',
				'label'       => '広告ラベル',
				'description' => '広告上に表示するラベルテキストを設定します',
			]
		);
		/**
		 * PC用広告
		 */
		$customizer->add_section(
			[
				'section' => 'ys_customizer_section_ads_pc',
				'title'   => 'PC広告設定',
			]
		);
		/**
		 * 記事タイトル上
		 */
		$customizer->add_textarea(
			[
				'id'      => 'ys_advertisement_before_title',
				'default' => '',
				'label'   => '記事タイトル上',
			]
		);
		/**
		 * 記事タイトル下
		 */
		$customizer->add_textarea(
			[
				'id'      => 'ys_advertisement_after_title',
				'default' => '',
				'label'   => '記事タイトル下',
			]
		);
		/**
		 * 記事本文上
		 */
		$customizer->add_textarea(
			[
				'id'      => 'ys_advertisement_before_content',
				'default' => '',
				'label'   => '記事本文上',
			]
		);
		/**
		 * Moreタグ部分
		 */
		$customizer->add_textarea(
			[
				'id'      => 'ys_advertisement_replace_more',
				'default' => '',
				'label'   => 'moreタグ部分',
			]
		);
		/**
		 * 記事本文下（左）
		 */
		$customizer->add_textarea(
			[
				'id'      => 'ys_advertisement_under_content_left',
				'default' => '',
				'label'   => '記事本文下（左）',
			]
		);
		/**
		 * 記事本文下（右）
		 */
		$customizer->add_textarea(
			[
				'id'      => 'ys_advertisement_under_content_right',
				'default' => '',
				'label'   => '記事本文下（右）',
			]
		);
		/**
		 * モバイル用広告
		 */
		$customizer->add_section(
			[
				'section' => 'ys_customizer_section_ads_sp',
				'title'   => 'モバイル広告設定',
			]
		);
		/**
		 * 記事タイトル上
		 */
		$customizer->add_textarea(
			[
				'id'      => 'ys_advertisement_before_title_sp',
				'default' => '',
				'label'   => '記事タイトル上',
			]
		);
		/**
		 * 記事タイトル下
		 */
		$customizer->add_textarea(
			[
				'id'      => 'ys_advertisement_after_title_sp',
				'default' => '',
				'label'   => '記事タイトル下',
			]
		);
		/**
		 * 記事本文上
		 */
		$customizer->add_textarea(
			[
				'id'      => 'ys_advertisement_before_content_sp',
				'default' => '',
				'label'   => '記事本文上',
			]
		);
		/**
		 * Moreタグ部分
		 */
		$customizer->add_textarea(
			[
				'id'      => 'ys_advertisement_replace_more_sp',
				'default' => '',
				'label'   => 'moreタグ部分',
			]
		);
		/**
		 * 記事本文下（SP）
		 */
		$customizer->add_textarea(
			[
				'id'    => 'ys_advertisement_under_content_sp',
				'label' => '記事本文下',
			]
		);
		/**
		 * インフィード広告
		 */
		$customizer->add_section(
			[
				'section' => 'ys_customizer_section_infeed',
				'title'   => 'インフィード広告設定',
			]
		);
		/**
		 * PC用広告
		 */
		$customizer->add_textarea(
			[
				'id'      => 'ys_advertisement_infeed_pc',
				'default' => '',
				'label'   => 'PC用広告',
			]
		);
		/**
		 * 広告を表示する間隔
		 */
		$customizer->add_number(
			[
				'id'          => 'ys_advertisement_infeed_pc_step',
				'default'     => 3,
				'label'       => '広告を表示する間隔(PC)',
				'input_attrs' => [
					'min' => 1,
					'max' => 100,
				],
			]
		);
		/**
		 * 表示する広告の最大数
		 */
		$customizer->add_number(
			[
				'id'          => 'ys_advertisement_infeed_pc_limit',
				'default'     => 3,
				'label'       => '表示する広告の最大数(PC)',
				'input_attrs' => [
					'min' => 1,
					'max' => 100,
				],
			]
		);
		/**
		 * SP用広告
		 */
		$customizer->add_textarea(
			[
				'id'      => 'ys_advertisement_infeed_sp',
				'default' => '',
				'label'   => 'モバイル用広告',
			]
		);
		/**
		 * 広告を表示する間隔
		 */
		$customizer->add_number(
			[
				'id'          => 'ys_advertisement_infeed_sp_step',
				'default'     => 3,
				'label'       => '広告を表示する間隔(モバイル)',
				'input_attrs' => [
					'min' => 1,
					'max' => 100,
				],
			]
		);
		/**
		 * 表示する広告の最大数
		 */
		$customizer->add_number(
			[
				'id'          => 'ys_advertisement_infeed_sp_limit',
				'default'     => 3,
				'label'       => '表示する広告の最大数(モバイル)',
				'input_attrs' => [
					'min' => 1,
					'max' => 100,
				],
			]
		);

		$customizer->add_section(
			[
				'section'         => 'ys_customizer_section_amp_ads',
				'title'           => 'AMP広告設定',
				'active_callback' => function () {
					return Option::get_option_by_bool( 'ys_amp_enable_amp_plugin_integration', false );
				},
			]
		);
		/**
		 * 記事タイトル上
		 */
		$customizer->add_textarea(
			[
				'id'        => 'ys_amp_advertisement_before_title',
				'default'   => '',
				'transport' => 'postMessage',
				'label'     => '記事タイトル上',
			]
		);
		/**
		 * 記事タイトル下
		 */
		$customizer->add_textarea(
			[
				'id'        => 'ys_amp_advertisement_after_title',
				'default'   => '',
				'transport' => 'postMessage',
				'label'     => '記事タイトル下',
			]
		);
		/**
		 * 記事本文上
		 */
		$customizer->add_textarea(
			[
				'id'        => 'ys_amp_advertisement_before_content',
				'default'   => '',
				'transport' => 'postMessage',
				'label'     => '記事本文上',
			]
		);
		/**
		 * Moreタグ部分
		 */
		$customizer->add_textarea(
			[
				'id'        => 'ys_amp_advertisement_replace_more',
				'default'   => '',
				'transport' => 'postMessage',
				'label'     => 'moreタグ部分',
			]
		);
		/**
		 * 記事下
		 */
		$customizer->add_textarea(
			[
				'id'        => 'ys_amp_advertisement_under_content',
				'default'   => '',
				'transport' => 'postMessage',
				'label'     => '記事本文下',
			]
		);
	}
}

$class_ad = new Advertisement();
$class_ad->register();
