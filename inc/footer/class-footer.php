<?php
/**
 * フッター
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

use ystandard\helper\Style_Sheet;

defined( 'ABSPATH' ) || die();

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
		add_action( 'wp_footer', [ $this, 'back_to_top' ] );
		add_action( 'customize_register', [ $this, 'customize_register' ] );
		add_filter( Enqueue_Utility::FILTER_CSS_VARS, [ $this, 'add_css_var_footer_main' ] );
		add_filter( Enqueue_Utility::FILTER_CSS_VARS, [ $this, 'add_css_var_footer_sub' ] );
		add_filter( Enqueue_Utility::FILTER_CSS_VARS, [ $this, 'add_css_var_mobile_footer_menu' ] );
		add_filter( Enqueue_Utility::FILTER_INLINE_CSS, [ $this, 'add_sub_footer_css' ] );
		add_filter( Enqueue_Utility::FILTER_INLINE_CSS, [ $this, 'add_footer_mobile_nav_css' ] );
		add_filter( Enqueue_Utility::FILTER_INLINE_CSS, [ $this, 'add_back_to_top_css' ] );
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
	 * モバイルフッター表示判断
	 *
	 * @return bool
	 */
	public static function show_footer_mobile_nav() {
		$result = has_nav_menu( 'mobile-footer' );

		$result = Utility::to_bool( apply_filters( 'ys_show_footer_mobile_nav', $result ) );

		if ( Template::is_legacy_widget_preview() ) {
			$result = false;
		}

		return $result;
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
	 * ページ先頭へ戻るボタン
	 */
	public function back_to_top() {
		if ( AMP::is_amp() ) {
			return;
		}
		if ( Template::is_legacy_widget_preview() ) {
			return;
		}
		if ( ! Option::get_option_by_bool( 'ys_back_to_top_active', false ) ) {
			return;
		}
		$text = Option::get_option( 'ys_back_to_top_text', '[ys_icon name="arrow-up"]' );
		if ( '' === $text ) {
			return;
		}
		$button_class = '';
		if ( Option::get_option_by_bool( 'ys_back_to_top_square', true ) ) {
			$button_class = 'is-square';
		}
		$button_class = empty( $button_class ) ? '' : "class=\"${button_class}\"";
		echo sprintf(
			'<button id="back-to-top" %s type="button"><span class="back-to-top__content">%s</span></button>',
			$button_class,
			do_shortcode( $text )
		);
	}

	/**
	 * ページ先頭へ戻る CSS追加
	 *
	 * @param string $css CSS.
	 *
	 * @return string
	 */
	public function add_back_to_top_css( $css ) {
		if ( ! Option::get_option_by_bool( 'ys_back_to_top_active', false ) ) {
			return $css;
		}
		$bg     = Option::get_option( 'ys_back_to_top_bg_color', '#f1f1f3' );
		$color  = Option::get_option( 'ys_back_to_top_color', '#222222' );
		$radius = Option::get_option( 'ys_back_to_top_border_radius', 100 );
		// CSS.
		$css .= "
		#back-to-top {
			position: fixed;
			bottom: 24px;
			right: 24px;
			padding: 0;
			margin: 0;
			background: none;
			border: 0;
			outline: none;
			-webkit-appearance: none;
			 -moz-appearance: none;
			      appearance: none;
			z-index: var(--z-index-back-to-top);
			cursor: pointer;
		}
		#back-to-top:hover{
			-webkit-box-shadow: none;
			        box-shadow: none;
		}
		.back-to-top__content {
			display:block;
			padding:.75em;
			background-color:${bg};
			border-radius:${radius}px;
			color:${color};
			line-height:1;
			white-space:nowrap;
			-webkit-box-shadow: 0 0 4px rgba(0, 0, 0, 0.1);
				    box-shadow: 0 0 4px rgba(0, 0, 0, 0.1);
		}
		.is-square .back-to-top__content {
			display: -webkit-box;
			display: -ms-flexbox;
			display: flex;
			-webkit-box-pack: center;
			  -ms-flex-pack: center;
				  justify-content: center;
			-webkit-box-align: center;
			  -ms-flex-align: center;
				  align-items: center;
			width:100%;
			height:100%;
		}
		.back-to-top__content > * {
			margin:0;
		}
		";
		$css .= Style_Sheet::add_media_query(
			'#back-to-top {bottom:5vh;right:5vh;}',
			'md'
		);
		if ( has_nav_menu( 'mobile-footer' ) ) {
			$css .= Style_Sheet::add_media_query( '#back-to-top {display:none;}', '', 'md' );
		}

		return $css;
	}

	/**
	 * モバイルフッター用CSS
	 *
	 * @param string $css CSS.
	 *
	 * @return string
	 */
	public function add_footer_mobile_nav_css( $css ) {

		if ( ! self::show_footer_mobile_nav() ) {
			return $css;
		}

		$css .= '
		.footer-mobile-nav {
		  position: fixed;
		  bottom: 0;
		  left: 0;
		  z-index: var(--z-index-mobile-footer);
		  width: 100%;
		  background-color: var(--mobile-footer-bg);
		  text-align: center;
		  color: var(--mobile-footer-text);
		  -webkit-box-shadow: 0px -1px 2px rgba(0, 0, 0, 0.07);
		          box-shadow: 0px -1px 2px rgba(0, 0, 0, 0.07);
		  transition:transform .3s;}
		  .footer-mobile-nav.is-hide {
		    transform: translateY(100%); }
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
		  .footer-mobile-nav i {
		    font-size: 1.5em; }
		  .footer-mobile-nav svg {
		    width: 1.5em;
		    height: 1.5em; }

		.footer-mobile-nav__dscr {
		  display: block;
		  font-size: 0.7em;
		  line-height: 1.2; }

		.has-mobile-footer .site-footer {
		  padding-bottom: 4em; }

		@media (min-width: 769px) {
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
		if ( ! self::show_footer_mobile_nav() ) {
			return;
		}
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
		$bg       = Option::get_option( 'ys_color_sub_footer_bg', '#f1f1f3' );
		$bg       = '' === $bg ? 'transparent' : $bg;
		$bg       = Enqueue_Utility::get_css_var(
			'sub-footer-bg',
			$bg
		);
		$css_vars = array_merge(
			$css_vars,
			$bg
		);
		$color    = Option::get_option( 'ys_color_sub_footer_text', '#222222' );
		if ( '' !== $color ) {
			$color    = Enqueue_Utility::get_css_var(
				'sub-footer-text',
				$color
			);
			$css_vars = array_merge(
				$css_vars,
				$color
			);
		}

		$padding = Option::get_option( 'ys_color_sub_footer_padding', '' );
		if ( '' !== $padding ) {
			$padding  = empty( $padding ) ? 0 : "${padding}px";
			$padding  = Enqueue_Utility::get_css_var(
				'sub-footer-padding',
				$padding
			);
			$css_vars = array_merge(
				$css_vars,
				$padding
			);
		}

		return $css_vars;
	}

	/**
	 * モバイルフッターメニュー色
	 *
	 * @param array $css_vars CSS.
	 *
	 * @return array
	 */
	public function add_css_var_mobile_footer_menu( $css_vars ) {

		$bg_color = Style_Sheet::hex_2_rgb( Option::get_option( 'ys_color_mobile_footer_bg', '#ffffff' ) );
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
		$customizer->add_section_label(
			'フッターメインエリア設定',
			[
				'description' => Admin::manual_link( 'manual/footer-area' ),
			]
		);
		// フッター背景色.
		$customizer->add_color(
			[
				'id'      => 'ys_color_footer_bg',
				'default' => '#f1f1f3',
				'label'   => 'フッター背景色',
			]
		);
		// フッター文字色.
		$customizer->add_color(
			[
				'id'      => 'ys_color_footer_font',
				'default' => '#222222',
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
		$customizer->add_section_label(
			'サブフッター設定',
			[
				'description' => Admin::manual_link( 'manual/sub-footer' ),
			]
		);
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
		// サブフッター コンテンツ.
		$customizer->add_select(
			[
				'id'          => 'ys_footer_sub_content',
				'default'     => 0,
				'label'       => 'サブフッター コンテンツ',
				'description' => 'サブフッターに表示する内容を[ys]パーツから選択します。',
				'choices'     => Parts::get_parts_list( true ),
			]
		);
		$customizer->add_number(
			[
				'id'      => 'ys_color_sub_footer_padding',
				'default' => '',
				'label'   => 'サブフッター上下余白',
			]
		);
		/**
		 * ページ先頭へ戻るボタン
		 */
		$customizer->add_section_label(
			'ページ先頭へ戻るボタン',
			[
				'description' => Admin::manual_link( 'manual/back-to-top' ),
			]
		);
		$customizer->add_checkbox(
			[
				'id'      => 'ys_back_to_top_active',
				'default' => 0,
				'label'   => 'ページ先頭へ戻るボタンを表示する',
			]
		);
		// ページ先頭へ戻るボタンテキスト.
		$short_code_page = admin_url( 'admin.php?page=ystandard-icons' );
		$customizer->add_text(
			[
				'id'                => 'ys_back_to_top_text',
				'default'           => '[ys_icon name="arrow-up"]',
				'label'             => 'ページ先頭へ戻るボタンテキスト',
				'description'       => "<a href='${short_code_page}' target='_blank'>アイコンショートコード</a>と<code>img</code>タグが使用できます。",
				'sanitize_callback' => [ $this, 'sanitize_back_to_top_text' ],
			]
		);
		// ページ先頭へ戻るボタン背景色.
		$customizer->add_color(
			[
				'id'      => 'ys_back_to_top_bg_color',
				'default' => '#f1f1f3',
				'label'   => '先頭へ戻るボタン背景色',
			]
		);
		// ページ先頭へ戻るボタン文字色.
		$customizer->add_color(
			[
				'id'      => 'ys_back_to_top_color',
				'default' => '#222222',
				'label'   => '先頭へ戻るボタン文字色',
			]
		);
		$customizer->add_number(
			[
				'id'          => 'ys_back_to_top_border_radius',
				'default'     => 100,
				'label'       => '先頭へ戻るボタンの角丸',
				'input_attrs' => [
					'min' => 0,
					'max' => 100,
				],
			]
		);
		// 正方形.
		$customizer->add_label(
			[
				'id'          => 'ys_back_to_top_square_label',
				'label'       => 'ボタンの縦・横サイズをあわせる',
				'description' => '縦長・横長のボタンにする場合はチェックを外してください',
			]
		);
		$customizer->add_checkbox(
			[
				'id'      => 'ys_back_to_top_square',
				'default' => 1,
				'label'   => 'ボタンの縦・横サイズをあわせる',
			]
		);

		// モバイルフッター.
		$customizer->add_section_label(
			'モバイルフッターメニュー 色設定',
			[
				'description' => Admin::manual_link( 'manual/mobile-footer-menu' ),
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

	/**
	 * Topへ戻るボタンのサニタイズ
	 *
	 * @param string $value Text.
	 *
	 * @return string
	 */
	public function sanitize_back_to_top_text( $value ) {
		$allowed_html   = wp_kses_allowed_html( 'post' );
		$allowed_img    = isset( $allowed_html['img'] ) ? $allowed_html['img'] : [];
		$allowed_span   = isset( $allowed_html['span'] ) ? $allowed_html['span'] : [];
		$allowed_strong = isset( $allowed_html['strong'] ) ? $allowed_html['strong'] : [];

		return wp_kses(
			$value,
			[
				'img'    => $allowed_img,
				'span'   => $allowed_span,
				'strong' => $allowed_strong,
			]
		);
	}
}


new Footer();
