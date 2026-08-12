<?php
/**
 * サイト全体の文字に関する設定や処理のクラス
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

/**
 * Class Typography
 *
 * @package ystandard
 */
class Typography {

	/**
	 * パネル名
	 *
	 * @var string
	 */
	const PANEL_NAME = 'ys_site_typography';

	/**
	 * インスタンスを保持する変数
	 *
	 * @var Typography|null
	 */
	private static $instance = null;

	/**
	 * コンストラクタをプライベートにして外部からのインスタンス生成を防ぐ
	 */
	private function __construct() {
		// 初期化処理.
		add_filter( 'ys_get_css_custom_properties_args', [ $this, 'add_css_vars' ] );
		add_filter( 'customize_value_ys_design_font_type', [ $this, 'convert_legacy_font_type' ] );
		add_action( 'customize_register', [ $this, 'customize_register' ] );
	}

	/**
	 * クローンを禁止
	 */
	private function __clone() {}

	/**
	 * アンシリアライズを禁止
	 *
	 * @throws \Exception アンシリアライズ時に例外を投げる.
	 */
	public function __wakeup() {
		throw new \Exception( 'Cannot unserialize singleton' );
	}

	/**
	 * インスタンスを取得する
	 *
	 * @return Typography
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * フォントCSS
	 *
	 * @param array $css_vars CSS.
	 *
	 * @return array
	 */
	public function add_css_vars( $css_vars ) {
		$font_family = 'sans-serif';
		$font        = self::get_usable_fonts();

		// フォント.
		$option = self::convert_legacy_font_type( Option::get_option( 'ys_design_font_type', '' ) );
		if ( ! empty( $option ) && isset( $font[ $option ] ) ) {
			$font_family = $font[ $option ]['family'];

			$css_vars = array_merge(
				$css_vars,
				Enqueue_Utility::get_css_var( '--ystd--font-family', $font_family )
			);
		}

		// 文字色.
		$site_text = Option::get_option( 'ys_color_site_text', '' );
		if ( ! empty( $site_text ) ) {
			$css_vars = array_merge(
				$css_vars,
				Enqueue_Utility::get_css_var(
					'--ystd--text-color',
					$site_text
				)
			);
		}

		// グレー文字色.
		$site_gray = Option::get_option( 'ys_color_site_gray', '' );
		if ( ! empty( $site_gray ) ) {
			$css_vars = array_merge(
				$css_vars,
				Enqueue_Utility::get_css_var(
					'--ystd--text-color--gray',
					$site_gray
				)
			);
		}

		// リンク色.
		$link = Option::get_option( 'ys_color_link', '' );
		if ( ! empty( $link ) ) {
			$css_vars = array_merge(
				$css_vars,
				Enqueue_Utility::get_css_var(
					'--ystd--link--text-color',
					$link
				)
			);
		}

		// リンクホバー色.
		$link_hover = Option::get_option( 'ys_color_link_hover', '' );
		if ( ! empty( $link_hover ) ) {
			$css_vars = array_merge(
				$css_vars,
				Enqueue_Utility::get_css_var(
					'--ystd--link--text-color--hover',
					$link_hover
				)
			);
		}

		return $css_vars;
	}

	/**
	 * カスタマイザー追加
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 */
	public function customize_register( $wp_customize ) {
		$customizer = new Customize_Control( $wp_customize );
		// セクション追加.
		$customizer->add_section(
			[
				'section'     => 'ys_section_font',
				'title'       => __( '[ys]フォント・文字色', 'ystandard' ),
				'description' => __( 'サイト全体にフォント・文字色の設定', 'ystandard' ) . Admin::manual_link( 'manual/font' ),
				'priority'    => Customizer::get_priority( self::PANEL_NAME ),
			]
		);
		$customizer->add_section_label( __( 'フォント', 'ystandard' ) );
		// フォント種類.
		$customizer->add_radio(
			[
				'id'          => 'ys_design_font_type',
				'default'     => 'font-library-ystd-gothic',
				'label'       => __( 'フォントタイプ', 'ystandard' ),
				'description' => __( '文字のフォントを変更できます', 'ystandard' ),
				'choices'     => self::get_font_choices(),
			]
		);
		$customizer->add_section_label( __( '文字色', 'ystandard' ) );
		// 文字色.
		$customizer->add_color(
			[
				'id'      => 'ys_color_site_text',
				'default' => '',
				'label'   => __( '文字色', 'ystandard' ),
			]
		);
		/**
		 * グレー文字色
		 */
		$customizer->add_color(
			[
				'id'          => 'ys_color_site_gray',
				'default'     => '',
				'label'       => 'グレー文字色',
				'description' => '少し薄めの色で表示される部分の色設定',
			]
		);
		$customizer->add_section_label( __( 'リンク色', 'ystandard' ) );
		// リンク色.
		$customizer->add_color(
			[
				'id'      => 'ys_color_link',
				'default' => '',
				'label'   => 'リンク色',
			]
		);
		// ホバー色.
		$customizer->add_color(
			[
				'id'      => 'ys_color_link_hover',
				'default' => '',
				'label'   => 'リンク色(マウスホバー)',
			]
		);
	}

	/**
	 * 選べるフォントのリスト取得
	 *
	 * @return array
	 */
	public static function get_usable_fonts() {
		return apply_filters(
			'ys_usable_fonts',
			self::get_font_library_fonts()
		);
	}

	/**
	 * カスタマイザーに表示するフォント選択肢を取得
	 *
	 * @return array
	 */
	private static function get_font_choices() {
		$choices = [];
		$fonts   = self::get_usable_fonts();
		foreach ( $fonts as $key => $font ) {
			if ( empty( $font['label'] ) ) {
				continue;
			}
			$choices[ $key ] = $font['label'];
		}

		return $choices;
	}

	/**
	 * Font Libraryで選択可能なフォントを取得
	 *
	 * @return array
	 */
	private static function get_font_library_fonts() {
		if ( ! function_exists( 'wp_get_global_settings' ) ) {
			return [];
		}

		$font_families = wp_get_global_settings( [ 'typography', 'fontFamilies' ] );
		if ( empty( $font_families ) || ! is_array( $font_families ) ) {
			return [];
		}

		$result = [];
		foreach ( self::normalize_font_families( $font_families ) as $font_family ) {
			if (
				! is_array( $font_family ) ||
				empty( $font_family['slug'] ) ||
				empty( $font_family['fontFamily'] )
			) {
				continue;
			}

			$key = 'font-library-' . sanitize_key( $font_family['slug'] );
			if ( 'font-library-' === $key || isset( $result[ $key ] ) ) {
				continue;
			}

			$result[ $key ] = [
				'family' => sanitize_text_field( $font_family['fontFamily'] ),
				'label'  => self::get_font_library_font_label( $font_family ),
			];
		}

		return $result;
	}

	/**
	 * Font Libraryのフォント一覧を1次元配列に整形
	 *
	 * @param array $font_families フォント一覧.
	 *
	 * @return array
	 */
	private static function normalize_font_families( $font_families ) {
		if ( isset( $font_families['slug'] ) ) {
			return [ $font_families ];
		}

		$result = [];
		foreach ( [ 'custom', 'theme', 'default' ] as $origin ) {
			if ( empty( $font_families[ $origin ] ) || ! is_array( $font_families[ $origin ] ) ) {
				continue;
			}
			$result = array_merge( $result, $font_families[ $origin ] );
		}

		if ( empty( $result ) && isset( $font_families[0] ) ) {
			$result = $font_families;
		}

		return $result;
	}

	/**
	 * 旧フォント設定値をtheme.jsonのフォントキーに変換
	 *
	 * @param string $font_type フォント設定値.
	 *
	 * @return string
	 */
	public static function convert_legacy_font_type( $font_type ) {
		$legacy_font_types = [
			'meihiragino' => 'font-library-ystd-gothic',
			'yugo'        => 'font-library-ystd-yu-gothic',
			'serif'       => 'font-library-ystd-serif',
		];

		return $legacy_font_types[ $font_type ] ?? $font_type;
	}

	/**
	 * Font Libraryのフォント名を取得
	 *
	 * @param array $font_family フォント情報.
	 *
	 * @return string
	 */
	private static function get_font_library_font_label( $font_family ) {
		if ( ! empty( $font_family['name'] ) ) {
			return sanitize_text_field( $font_family['name'] );
		}

		return sanitize_text_field( $font_family['slug'] );
	}
}

Typography::get_instance();
