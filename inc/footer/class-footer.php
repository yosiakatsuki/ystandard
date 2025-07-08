<?php
/**
 * フッター
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

use ystandard\utils\CSS;

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
		// フッターメニュー.
		add_action( 'after_setup_theme', [ $this, 'register_nav_menus' ], 20 );
		// フッターウィジェット.
		add_action( 'widgets_init', [ $this, 'widget_init' ] );
		// カスタマイザー.
		add_action( 'customize_register', [ $this, 'customize_register' ] );
		// CSS変数.
		add_filter( 'ys_get_css_custom_properties_args', [ $this, 'add_css_var_footer_main' ] );
		add_filter( 'ys_get_css_custom_properties_args', [ $this, 'add_css_var_footer_sub' ] );
		add_filter( 'ys_get_css_custom_properties_args', [ $this, 'add_css_var_back_to_top' ] );
		// TOPへ戻る.
		add_action( 'wp_footer', [ $this, 'back_to_top' ] );
		add_filter( 'ys_get_inline_css', [ $this, 'add_back_to_top_css' ] );
	}

	/**
	 * ナビゲーションメニューの登録
	 *
	 * @return void
	 */
	public function register_nav_menus() {
		register_nav_menus(
			[
				'footer' => 'フッターメニュー',
			]
		);
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

		return Parts::do_shortcode(
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
		// レガシープレビューでは表示しない.
		if ( Widget::is_legacy_widget_preview() ) {
			return;
		}
		// ページ先頭へ戻るボタンが有効か.
		if ( ! Option::get_option_by_bool( 'ys_back_to_top_active', false ) ) {
			return;
		}
		// ボタンのテキスト.
		$text = Option::get_option( 'ys_back_to_top_text', '[ys_icon name="arrow-up"]' );
		if ( '' === $text ) {
			return;
		}
		$button_class = '';
		if ( Option::get_option_by_bool( 'ys_back_to_top_square', true ) ) {
			$button_class = 'is-square';
		}
		$button_class = empty( $button_class ) ? '' : "class=\"{$button_class}\"";
		printf(
			'<button id="back-to-top" %s type="button"><span class="back-to-top__content">%s</span></button>',
			esc_attr( $button_class ),
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
		if ( has_nav_menu( 'mobile-footer' ) ) {
			// モバイルで非表示.
			$css .= CSS::add_media_query_mobile( '#back-to-top {display:none;}' );
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

		$footer_bg_color = Option::get_option( 'ys_color_footer_bg', '' );
		if ( $footer_bg_color ) {
			$css_vars = array_merge(
				$css_vars,
				Enqueue_Utility::get_css_var(
					'footer--background',
					$footer_bg_color
				)
			);
		}
		$footer_text_color = Option::get_option( 'ys_color_footer_font', '' );
		if ( $footer_text_color ) {
			$css_vars = array_merge(
				$css_vars,
				Enqueue_Utility::get_css_var(
					'footer--text-color',
					$footer_text_color
				)
			);
		}
		$footer_text_gray = Option::get_option( 'ys_color_footer_text_gray', '' );
		if ( $footer_text_gray ) {
			$css_vars = array_merge(
				$css_vars,
				Enqueue_Utility::get_css_var(
					'footer--text-color--gray',
					$footer_text_gray
				)
			);
		}

		return $css_vars;
	}

	/**
	 * サブフッター色
	 *
	 * @param array $css_vars CSS.
	 *
	 * @return array
	 */
	public function add_css_var_footer_sub( $css_vars ) {
		// サブフッター背景色.
		$bg = Option::get_option( 'ys_color_sub_footer_bg', '' );
		if ( $bg ) {
			$bg       = Enqueue_Utility::get_css_var(
				'sub-footer--background',
				$bg
			);
			$css_vars = array_merge(
				$css_vars,
				$bg
			);
		}
		// サブフッターテキスト色.
		$color = Option::get_option( 'ys_color_sub_footer_text', '' );
		if ( '' !== $color ) {
			$color    = Enqueue_Utility::get_css_var(
				'sub-footer--text-color',
				$color
			);
			$css_vars = array_merge(
				$css_vars,
				$color
			);
		}

		$padding = Option::get_option( 'ys_sub_footer_padding', '' );
		if ( '' !== $padding ) {
			$padding  = empty( $padding ) ? 0 : "{$padding}px";
			$padding  = Enqueue_Utility::get_css_var(
				'sub-footer--padding',
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
	 * ページ先頭へ戻るボタンのCSS変数を追加
	 *
	 * @param array $css_vars CSS変数.
	 */
	public function add_css_var_back_to_top( $css_vars ) {

		$color = Option::get_option( 'ys_back_to_top_color', '' );
		if ( $color ) {
			$color    = Enqueue_Utility::get_css_var(
				'back-to-top--text-color',
				$color
			);
			$css_vars = array_merge(
				$css_vars,
				$color
			);
		}
		$bg = Option::get_option( 'ys_back_to_top_bg_color', '' );
		if ( $bg ) {
			$bg       = Enqueue_Utility::get_css_var(
				'back-to-top--background-color',
				$bg
			);
			$css_vars = array_merge(
				$css_vars,
				$bg
			);
		}
		$radius = Option::get_option( 'ys_back_to_top_border_radius', 100 );
		if ( $radius ) {
			$radius   = Enqueue_Utility::get_css_var(
				'back-to-top--border-radius',
				CSS::check_and_add_unit( "{$radius}" )
			);
			$css_vars = array_merge(
				$css_vars,
				$radius
			);
		}

		return $css_vars;
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
				'section'     => 'ys_site_footer',
				'title'       => '[ys]' . _x( 'フッター', 'customizer', 'ystandard' ),
				'description' => 'フッターのデザイン設定',
				'priority'    => Customizer::get_priority( 'ys_site_footer' ),
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
				'default' => '',
				'label'   => 'フッター背景色',
			]
		);
		// フッター文字色.
		$customizer->add_color(
			[
				'id'      => 'ys_color_footer_font',
				'default' => '',
				'label'   => 'フッター文字色',
			]
		);
		// フッター文字色(グレー).
		$customizer->add_color(
			[
				'id'      => 'ys_color_footer_text_gray',
				'default' => '',
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
				'default' => '',
				'label'   => 'サブフッター背景色',
			]
		);
		// フッター文字色.
		$customizer->add_color(
			[
				'id'      => 'ys_color_sub_footer_text',
				'default' => '',
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
				'id'      => 'ys_sub_footer_padding',
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
				'default' => 1,
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
				'description'       => "<a href='{$short_code_page}' target='_blank'>アイコンショートコード</a>と<code>img</code>タグが使用できます。",
				'sanitize_callback' => [ $this, 'sanitize_back_to_top_text' ],
			]
		);
		// ページ先頭へ戻るボタン背景色.
		$customizer->add_color(
			[
				'id'      => 'ys_back_to_top_bg_color',
				'default' => '',
				'label'   => '先頭へ戻るボタン背景色',
			]
		);
		// ページ先頭へ戻るボタン文字色.
		$customizer->add_color(
			[
				'id'      => 'ys_back_to_top_color',
				'default' => '',
				'label'   => '先頭へ戻るボタン文字色',
			]
		);
		$customizer->add_text(
			[
				'id'          => 'ys_back_to_top_border_radius',
				'default'     => '',
				'label'       => '先頭へ戻るボタンの角丸',
				'description' => __( '単位付きで入力してください。数値のみを入力した場合は単位はpxになります。', 'ystandard' ),
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
