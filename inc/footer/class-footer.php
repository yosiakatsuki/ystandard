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
		add_action( 'customize_register', [ $this, 'customize_register' ] );
		add_filter( 'ys_css_vars', [ $this, 'add_css_var_footer_main' ] );
		add_filter( 'ys_css_vars', [ $this, 'add_css_var_footer_sub' ] );
		add_filter( Enqueue_Styles::INLINE_CSS_HOOK, [ $this, 'add_inline_css_sub' ] );
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
	 * サブフッターのインラインCSS
	 *
	 * @param string $css CSS.
	 *
	 * @return string
	 */
	public function add_inline_css_sub( $css ) {

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

		$bg    = Css_Vars::get_css_var(
			'footer-bg-color',
			Option::get_option( 'ys_color_footer_bg', '#f1f1f3' )
		);
		$color = Css_Vars::get_css_var(
			'footer-text-color',
			Option::get_option( 'ys_color_footer_font', '#222222' )
		);

		return array_merge(
			$css_vars,
			$bg,
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

		$bg    = Css_Vars::get_css_var(
			'footer-sub-bg-color',
			Option::get_option( 'ys_color_footer_sub_bg', '#f1f1f3' )
		);
		$color = Css_Vars::get_css_var(
			'footer-sub-text-color',
			Option::get_option( 'ys_color_footer_sub_font', '#222222' )
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
				'priority'    => 100,
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
		$customizer->add_section_label( 'フッターサブエリア設定' );
		// フッター背景色.
		$customizer->add_color(
			[
				'id'      => 'ys_color_footer_sub_bg',
				'default' => '#f1f1f3',
				'label'   => 'サブフッター背景色',
			]
		);
		// フッター文字色.
		$customizer->add_color(
			[
				'id'      => 'ys_color_footer_sub_font',
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
	}
}


new Footer();
