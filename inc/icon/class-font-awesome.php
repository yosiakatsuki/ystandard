<?php
/**
 * アイコン系の読み込みクラス
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

/**
 * Class Enqueue_Icons
 *
 * @package ystandard
 */
class Font_Awesome {

	/**
	 * FontAwesome Handle
	 */
	const FONTAWESOME_HANDLE = 'font-awesome';

	/**
	 * FontAwesomeのバージョン
	 */
	const FONTAWESOME_VER = '5.13.0';

	/**
	 * フックやショートコードの登録
	 */
	public function register() {
		if ( $this->is_use_option() ) {
			add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_font_awesome_amp' ] );
			add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_font_awesome' ] );
			add_action( 'customize_register', [ $this, 'customize_register' ] );
			Notice::set_notice(
				function () {
					Notice::warning(
						'<p>yStandardのFont Awesome関連の設定はまもなく廃止されます。<br>アイコン表示にFont Awesome以外を使うか別プラグインでFont Awesomeのアイコンフォントを読み込むようにしてください。<br>このメッセージを消すためには「外観」→「カスタマイザー」を開き、「[ys]デザイン」→「アイコンフォント設定」で以下のように設定を変更してください。</p><ul><li>
・アイコンフォント（Font Awesome）読み込み方式 → 読み込まない(デフォルト)</li><li>・Font Awesome Kits URL → 中身を削除（空白にする）</li></ul><p>このメッセージが表示されている間はyStandardのアップデートをしないようにしてください。</p>'
					);
				}
			);
		}
	}

	/**
	 * Font Awesome関連の設定を使っているか
	 *
	 * @return bool
	 */
	private function is_use_option() {
		if ( 'none' !== Option::get_option( 'ys_enqueue_icon_font_type', 'none' ) ) {
			return true;
		}
		if ( '' !== Option::get_option( 'ys_enqueue_icon_font_kit_url', '' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * FontAwesome CSS
	 *
	 * @return string
	 */
	public static function get_font_awesome_css_url() {
		return Template::get_theme_file_uri( '/library/fontawesome/css/all.css' );
	}

	/**
	 * FontAwesome Light CSS
	 *
	 * @return string
	 */
	public static function get_font_awesome_css_light_url() {
		return Template::get_theme_file_uri( '/css/fontawesome-light.css' );
	}

	/**
	 * FontAwesome CDN CSS
	 *
	 * @return string
	 */
	public static function get_font_awesome_cdn_css_url() {
		$version = apply_filters(
			'ys_get_font_awesome_css_version',
			self::FONTAWESOME_VER
		);

		return apply_filters(
			'ys_get_font_awesome_cdn_css_url',
			"https://use.fontawesome.com/releases/${version}/css/all.css",
			$version
		);
	}

	/**
	 * FontAwesome js
	 *
	 * @return string
	 */
	public static function get_font_awesome_js_url() {
		return Template::get_theme_file_uri( '/library/fontawesome/js/all.js' );
	}

	/**
	 * FontAwesome js light
	 *
	 * @return string
	 */
	public static function get_font_awesome_js_light_url() {

		return apply_filters(
			'ys_get_font_awesome_svg_light_url',
			Template::get_theme_file_uri( '/js/font-awesome-ystd.js' )
		);
	}

	/**
	 * FontAwesome CDN js
	 *
	 * @return string
	 */
	public static function get_font_awesome_cdn_js_url() {
		$version = apply_filters(
			'ys_get_font_awesome_cdn_svg_version',
			self::FONTAWESOME_VER
		);

		return apply_filters(
			'ys_get_font_awesome_cdn_svg_url',
			"https://use.fontawesome.com/releases/${version}/js/all.js",
			$version
		);
	}

	/**
	 * FontAwesomeの読み込み
	 */
	public function enqueue_font_awesome() {
		if ( AMP::is_amp() ) {
			return;
		}
		$type = Option::get_option( 'ys_enqueue_icon_font_type', 'none' );
		if ( 'none' === $type ) {
			return;
		}
		if ( 'css' === $type ) {
			wp_enqueue_style(
				self::FONTAWESOME_HANDLE,
				self::get_font_awesome_css_url(),
				[],
				Utility::get_ystandard_version()
			);
		} else {
			wp_enqueue_script(
				self::FONTAWESOME_HANDLE,
				$this->get_font_awesome_load_js_url(),
				[],
				Utility::get_ystandard_version(),
				true
			);
			wp_add_inline_script(
				'font-awesome',
				'FontAwesomeConfig = { searchPseudoElements: true };',
				'before'
			);
			Enqueue_Utility::add_defer( self::FONTAWESOME_HANDLE );
			if ( $this->is_enable_font_awesome_kit() ) {
				Enqueue_Utility::add_crossorigin(
					self::FONTAWESOME_HANDLE,
					'anonymous'
				);
			}
		}
	}

	/**
	 * FontAwesome 読み込むJSファイル
	 *
	 * @return string
	 */
	private function get_font_awesome_load_js_url() {
		$type = Option::get_option( 'ys_enqueue_icon_font_type', 'none' );
		if ( 'light' === $type ) {
			return self::get_font_awesome_js_light_url();
		}
		if ( $this->is_enable_font_awesome_kit() ) {
			return Option::get_option( 'ys_enqueue_icon_font_kit_url', '' );
		}

		return self::get_font_awesome_js_url();
	}

	/**
	 * FontAwesome Kitを使えるか
	 *
	 * @return bool
	 */
	private function is_enable_font_awesome_kit() {
		return ( 'kit' === Option::get_option( 'ys_enqueue_icon_font_type', 'none' ) && ! empty( Option::get_option( 'ys_enqueue_icon_font_kit_url', '' ) ) );
	}

	/**
	 * FontAwesome読み込み AMPページ用
	 */
	public function enqueue_font_awesome_amp() {
		if ( ! AMP::is_amp() ) {
			return;
		}
		$type = Option::get_option( 'ys_enqueue_icon_font_type', 'none' );
		if ( 'none' === $type ) {
			return;
		}
		$src = self::get_font_awesome_cdn_css_url();
		if ( 'light' === $type ) {
			$src = self::get_font_awesome_css_light_url();
		}
		wp_enqueue_style(
			self::FONTAWESOME_HANDLE,
			$src,
			[],
			self::FONTAWESOME_VER
		);
	}

	/**
	 * カスタマイザー追加
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 */
	public function customize_register( $wp_customize ) {
		$customizer = new Customize_Control( $wp_customize );
		$customizer->add_section(
			[
				'section'     => 'ys_icon_fonts',
				'title'       => 'アイコンフォント設定',
				'description' => 'アイコンフォントに関する設定' . Admin::manual_link( 'icon-font' ),
				'priority'    => 5100,
				'panel'       => Design::PANEL_NAME,
			]
		);
		/**
		 * アイコンフォント読み込み方式
		 */
		$customizer->add_radio(
			[
				'id'          => 'ys_enqueue_icon_font_type',
				'default'     => 'none',
				'transport'   => 'postMessage',
				'label'       => 'アイコンフォント（Font Awesome）読み込み方式',
				'description' => 'Font Awesome読み込み方式を設定できます。',
				'choices'     => [
					'none'  => '読み込まない(デフォルト)',
					'light' => '軽量版',
					'js'    => 'JavaScript',
					'css'   => 'CSS',
					'kit'   => 'Font Awesome Kits(「Font Awesome Kits URL」の入力必須)',
				],
			]
		);
		/**
		 * Font Awesome Kits設定
		 */
		$customizer->add_url(
			[
				'id'          => 'ys_enqueue_icon_font_kit_url',
				'default'     => '',
				'transport'   => 'postMessage',
				'label'       => 'Font Awesome Kits URL',
				'description' => 'Font Awesome Kitsを使う場合のURL設定' . Admin::manual_link( 'get-font-awesome-kits' ),
			]
		);
	}

}

$class_font_awesome = new Font_Awesome();
$class_font_awesome->register();
