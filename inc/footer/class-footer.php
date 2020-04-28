<?php
/**
 * フッター
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

/**
 * Class Footer
 *
 * @package ystandard
 */
class Footer {

	/**
	 * Footer constructor.
	 */
	public function __construct() {
		add_action( 'widgets_init', [ $this, 'widget_init' ] );
		add_action( 'wp_footer', [ $this, 'footer_mobile_nav' ], 1 );
		add_action( 'customize_register', [ $this, 'customize_register' ] );
		add_filter( Enqueue_Utility::FILTER_CSS_VARS, [ $this, 'add_css_var_footer_main' ] );
		add_filter( Enqueue_Utility::FILTER_CSS_VARS, [ $this, 'add_css_var_footer_sub' ] );
		add_filter( Enqueue_Utility::FILTER_CSS_VARS, [ $this, 'add_css_var_mobile_footer_menu' ] );
		add_filter( Enqueue_Utility::FILTER_INLINE_CSS, [ $this, 'add_sub_footer_css' ] );
		add_filter( Enqueue_Utility::FILTER_INLINE_CSS, [ $this, 'add_footer_mobile_nav_css' ] );
	}

	/**
	 * フッターウィジェットが有効か
	 *
	 * @return bool
	 */
	public static function is_active_widget() {
		$result = true;
		if ( ! is_active_sidebar( 'footer-left' ) && ! is_active_sidebar( 'footer-center' ) && ! is_active_sidebar( 'footer-right' ) ) {
			$result = false;
		}

		return apply_filters( 'ys_is_active_footer_widgets', $result );
	}

	/**
	 * サブフッター用コンテンツ取得
	 *
	 * @return string
	 */
	public static function get_footer_sub_contents() {
		$parts_id = Option::get_option_by_int( 'ys_footer_sub_content', 0 );
		if ( 0 === $parts_id ) {
			return '';
		}
		$parts = new Parts();

		return $parts->do_shortcode(
			[
				'parts_id'          => $parts_id,
				'use_entry_content' => true,
			]
		);
	}

	/**
	 * モバイルフッター用CSS
	 *
	 * @param string $css CSS.
	 *
	 * @return string
	 */
	public function add_footer_mobile_nav_css( $css ) {

		if ( ! has_nav_menu( 'mobile-footer' ) ) {
			return $css;
		}

		$css .= '
		.footer-mobile-nav {
		  position: fixed;
		  bottom: 0;
		  left: 0;
		  z-index: 8;
		  width: 100%;
		  background-color: var(--mobile-footer-bg);
		  text-align: center;
		  color: var(--mobile-footer-text);
		  -webkit-box-shadow: 0px -1px 2px rgba(0, 0, 0, 0.07);
		          box-shadow: 0px -1px 2px rgba(0, 0, 0, 0.07); }
		  .footer-mobile-nav ul {
		    display: -webkit-box;
		    display: -ms-flexbox;
		    display: flex;
		    -webkit-box-pack: justify;
		        -ms-flex-pack: justify;
		            justify-content: space-between;
		    margin: 0;
		    padding: 0.75em 0;
		    list-style: none; }
		  .footer-mobile-nav li:nth-child(n+5) {
		    display: none; }
		  .footer-mobile-nav a {
		    display: block;
		    color: currentColor;
		    text-decoration: none; }
		  .footer-mobile-nav svg,
		  .footer-mobile-nav i {
		    font-size: 1.5em; }

		.footer-mobile-nav__dscr {
		  display: block;
		  font-size: 0.7em;
		  line-height: 1.2; }

		.has-mobile-footer .site-footer {
		  padding-bottom: 4em; }

		@media (min-width: 1025px) {
		    .footer-mobile-nav {
		      display: none; }
		    .has-mobile-footer .site-footer {
		      padding-bottom: 0; } }

		@media (min-width: 600px) {
		      .footer-mobile-nav li:nth-child(n+5) {
		        display: block; } }';

		return $css;
	}

	/**
	 * モバイルフッターナビゲーションの表示
	 */
	public function footer_mobile_nav() {
		Template::get_template_part( 'template-parts/footer/footer-mobile-nav' );
	}

	/**
	 * サブフッターのインラインCSS
	 *
	 * @param string $css CSS.
	 *
	 * @return string
	 */
	public function add_sub_footer_css( $css ) {

		if ( 'vertical' === Option::get_option( 'ys_footer_sub_type', 'horizon' ) ) {
			$css .= '.footer-sub__widget {flex-direction: column;}';
		}

		return $css;
	}

	/**
	 * フッターメイン
	 *
	 * @param array $css_vars CSS.
	 *
	 * @return array
	 */
	public function add_css_var_footer_main( $css_vars ) {

		$bg    = Enqueue_Utility::get_css_var(
			'footer-bg',
			Option::get_option( 'ys_color_footer_bg', '#f1f1f3' )
		);
		$color = Enqueue_Utility::get_css_var(
			'footer-text',
			Option::get_option( 'ys_color_footer_font', '#222222' )
		);
		$gray  = Enqueue_Utility::get_css_var(
			'footer-text-gray',
			Option::get_option( 'ys_color_footer_text_gray', '#a7a7a7' )
		);

		return array_merge(
			$css_vars,
			$bg,
			$gray,
			$color
		);
	}

	/**
	 * サブフッター色
	 *
	 * @param array $css_vars CSS.
	 *
	 * @return array
	 */
	public function add_css_var_footer_sub( $css_vars ) {

		$bg    = Enqueue_Utility::get_css_var(
			'sub-footer-bg',
			Option::get_option( 'ys_color_sub_footer_bg', '#f1f1f3' )
		);
		$color = Enqueue_Utility::get_css_var(
			'sub-footer-text',
			Option::get_option( 'ys_color_sub_footer_text', '#222222' )
		);

		return array_merge(
			$css_vars,
			$bg,
			$color
		);
	}

	/**
	 * モバイルフッターメニュー色
	 *
	 * @param array $css_vars CSS.
	 *
	 * @return array
	 */
	public function add_css_var_mobile_footer_menu( $css_vars ) {

		$bg_color = Utility::hex_2_rgb( Option::get_option( 'ys_color_mobile_footer_bg', '#ffffff' ) );
		$bg       = Enqueue_Utility::get_css_var(
			'mobile-footer-bg',
			sprintf(
				'rgb(%s,%s,%s,0.95)',
				$bg_color[0],
				$bg_color[1],
				$bg_color[2]
			)
		);
		$color    = Enqueue_Utility::get_css_var(
			'mobile-footer-text',
			Option::get_option( 'ys_color_mobile_footer_text', '#222222' )
		);

		return array_merge(
			$css_vars,
			$bg,
			$color
		);
	}

	/**
	 * ウィジェット登録
	 */
	public function widget_init() {
		register_sidebar(
			[
				'name'          => 'フッター左',
				'id'            => 'footer-left',
				'description'   => 'フッターエリア左側',
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			]
		);
		register_sidebar(
			[
				'name'          => 'フッター中央',
				'id'            => 'footer-center',
				'description'   => 'フッターエリア中央',
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			]
		);
		register_sidebar(
			[
				'name'          => 'フッター右',
				'id'            => 'footer-right',
				'description'   => 'フッターエリア右側',
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			]
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
				'section'     => 'ys_design_footer',
				'title'       => 'フッター',
				'description' => 'フッターのデザイン設定',
				'priority'    => 1000,
				'panel'       => Design::PANEL_NAME,
			]
		);
		$customizer->add_section_label( 'フッターメインエリア設定' );
		// フッター背景色.
		$customizer->add_color(
			[
				'id'      => 'ys_color_footer_bg',
				'default' => '#222222',
				'label'   => 'フッター背景色',
			]
		);
		// フッター文字色.
		$customizer->add_color(
			[
				'id'      => 'ys_color_footer_font',
				'default' => '#ffffff',
				'label'   => 'フッター文字色',
			]
		);
		// フッター文字色(グレー).
		$customizer->add_color(
			[
				'id'      => 'ys_color_footer_text_gray',
				'default' => '#a7a7a7',
				'label'   => 'フッター文字色(グレー)',
			]
		);
		$customizer->add_section_label( 'サブフッター設定' );
		// フッター背景色.
		$customizer->add_color(
			[
				'id'      => 'ys_color_sub_footer_bg',
				'default' => '#f1f1f3',
				'label'   => 'サブフッター背景色',
			]
		);
		// フッター文字色.
		$customizer->add_color(
			[
				'id'      => 'ys_color_sub_footer_text',
				'default' => '#222222',
				'label'   => 'サブフッター文字色',
			]
		);
		/**
		 * サブフッター コンテンツ
		 */
		$customizer->add_select(
			[
				'id'          => 'ys_footer_sub_content',
				'default'     => 0,
				'label'       => 'サブフッター コンテンツ',
				'description' => 'サブフッターに表示する内容を[ys]パーツから選択します。',
				'choices'     => Parts::get_parts_list( true ),
			]
		);
		$customizer->add_section_label(
			'モバイルフッターメニュー 色設定',
			[
				'description' => Admin::manual_link( 'https://wp-ystandard.com/mobile-footer-menu#color' ),
			]
		);
		// モバイルフッター背景色.
		$customizer->add_color(
			[
				'id'      => 'ys_color_mobile_footer_bg',
				'default' => '#ffffff',
				'label'   => 'モバイルフッター背景色',
			]
		);
		// モバイルフッター文字色.
		$customizer->add_color(
			[
				'id'      => 'ys_color_mobile_footer_text',
				'default' => '#222222',
				'label'   => 'モバイルフッター文字色',
			]
		);
	}
}


new Footer();
