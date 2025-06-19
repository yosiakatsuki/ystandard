<?php
/**
 * グローバルナビゲーション
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

use ystandard\utils\Convert;
use ystandard\utils\CSS;

defined( 'ABSPATH' ) || die();

/**
 * Class Global_Nav
 *
 * @package ystandard
 */
class Global_Nav {

	/**
	 * Global_Nav constructor.
	 */
	public function __construct() {
		add_action( 'after_setup_theme', [ $this, 'register_nav_menus' ] );
		add_action( 'customize_register', [ $this, 'customize_register' ] );
		add_filter( 'ys_get_css_custom_properties_args', [ $this, 'css_vars' ] );
		add_action( 'wp_footer', [ $this, 'global_nav_search' ] );
	}

	/**
	 * ナビゲーションメニューの登録
	 *
	 * @return void
	 */
	public function register_nav_menus() {
		register_nav_menus(
			[
				'global' => 'グローバルナビゲーション',
			]
		);
	}

	/**
	 * グローバルナビゲーションクラス
	 *
	 * @param string $class class.
	 *
	 * @return string
	 */
	public static function get_global_nav_class( $class ) {
		$class = [ $class ];
		if ( Body::has_background() ) {
			$class[] = 'has-background';
		}
		if ( ! has_nav_menu( 'global' ) ) {
			$class[] = 'is-no-global-nav';
		}

		return trim( implode( ' ', $class ) );
	}

	/**
	 * グローバルナビゲーションを表示するか
	 *
	 * @return boolean
	 */
	public static function has_global_nav() {
		$result = has_nav_menu( 'global' );

		return Convert::to_bool( apply_filters( 'ys_has_global_nav', $result ) );
	}

	/**
	 * グローバルナビゲーションワーカー
	 *
	 * @return \Walker_Nav_Menu
	 */
	public static function global_nav_walker() {
		$result = new \YS_Walker_Global_Nav_Menu();

		return apply_filters( 'ys_global_nav_walker', $result );
	}

	/**
	 * グローバルナビゲーションの検索ボタンで表示する検索フォームのセット
	 *
	 * @return void
	 */
	public function global_nav_search() {
		ys_get_template_part( 'template-parts/navigation/global-nav-search-form' );
	}

	/**
	 * カスタムプロパティ指定.
	 *
	 * @param array $css_vars カスタムプロパティリスト.
	 *
	 * @return array
	 */
	public function css_vars( $css_vars ) {

		$bold   = Enqueue_Utility::get_css_var(
			'--ystd--global-nav--font-weight',
			Option::get_option( 'ys_global_nav_bold', 'normal' )
		);
		$margin = Enqueue_Utility::get_css_var(
			'--ystd--global-nav--gap',
			Option::get_option( 'ys_header_nav_margin', 1.5 ) . 'em'
		);

		$css_vars = array_merge(
			$css_vars,
			$bold,
			$margin
		);

		// メニュー文字サイズ.
		$font_size = Option::get_option( 'ys_global_nav_font_size', '' );
		if ( ! empty( $font_size ) ) {

			$font_size = Enqueue_Utility::get_css_var(
				'--ystd--global-nav--font-size',
				CSS::check_and_add_unit( $font_size, 'px' )
			);

			$css_vars = array_merge(
				$css_vars,
				$font_size
			);
		}

		return $css_vars;
	}

	/**
	 * メニュー設定
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 */
	public function customize_register( $wp_customize ) {
		$customizer = new Customize_Control( $wp_customize );
		/**
		 * セクション追加
		 */
		$customizer->add_section(
			[
				'section'     => 'ys_customizer_section_global_nav',
				'title'       => 'グローバルナビゲーション',
				'description' => 'グローバルナビゲーション設定' . Admin::manual_link( 'manual/global-nav' ),
				'priority'    => 60,
				'panel'       => Design::PANEL_NAME,
			]
		);
		$customizer->add_section_label( 'グローバルメニューデザイン' );
		$customizer->add_text(
			[
				'id'          => 'ys_global_nav_font_size',
				'default'     => '',
				'label'       => 'メニュー文字サイズ',
				'description' => __( 'メニュー文字サイズ設定。数値のみ(単位なし)の場合、自動でpxが単位として追加されます。', 'ystandard' ),
			]
		);
		$customizer->add_select(
			[
				'id'          => 'ys_global_nav_bold',
				'default'     => 'normal',
				'label'       => 'メニュー文字太さ',
				'description' => 'トップレベルのメニュー文字太さ設定。',
				'choices'     => [
					'normal' => __( '通常(normal)', 'ystandard' ),
					'bold'   => __( '太字(bold)', 'ystandard' ),
					'100'    => __( '100', 'ystandard' ),
					'200'    => __( '200', 'ystandard' ),
					'300'    => __( '300', 'ystandard' ),
					'400'    => __( '400', 'ystandard' ),
					'500'    => __( '500', 'ystandard' ),
					'600'    => __( '600', 'ystandard' ),
					'700'    => __( '700', 'ystandard' ),
					'800'    => __( '800', 'ystandard' ),
					'900'    => __( '900', 'ystandard' ),
				],
			]
		);
		$customizer->add_number(
			[
				'id'          => 'ys_header_nav_margin',
				'default'     => 1.5,
				'label'       => 'メニュー項目の間隔',
				'description' => '単位はemです。大きくすればメニュー間の余白が大きくなります。',
				'input_attrs' => [
					'min'  => 0.1,
					'max'  => 10.0,
					'step' => 0.1,
				],
			]
		);
	}
}

new Global_Nav();
