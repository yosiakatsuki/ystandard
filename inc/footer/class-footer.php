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
		add_filter( 'ys_get_css_custom_properties_args', [ $this, 'add_css_var_footer_widget' ] );
		add_filter( 'ys_get_css_custom_properties_args', [ $this, 'add_css_var_footer_nav' ] );
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
				'footer' => __( 'フッターメニュー', 'ystandard' ),
			]
		);
	}

	/**
	 * フッターメインコンテンツが有効か.
	 *
	 * @return bool
	 */
	public static function is_active_main_contents() {
		$has_footer_nav    = self::is_active_footer_nav();
		$has_footer_widget = self::is_active_widget();

		return $has_footer_nav || $has_footer_widget;
	}

	/**
	 * フッターメニューが有効か.
	 *
	 * @return bool
	 */
	public static function is_active_footer_nav() {
		// フッターメニューが有効か.
		if ( has_nav_menu( 'footer' ) ) {
			return true;
		}

		return false;
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
		// フッター背景色.
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
		// フッター文字色.
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
		// フッター文字色(グレー).
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
		// フッターメイン 上部padding.
		$padding_top = Option::get_option( 'ys_footer_main_padding_top', '' );
		if ( '' !== $padding_top ) {
			$css_vars = array_merge(
				$css_vars,
				Enqueue_Utility::get_css_var(
					'footer--padding-top',
					CSS::check_and_add_unit( $padding_top )
				)
			);
		}
		// フッターメイン 下部padding.
		$padding_bottom = Option::get_option( 'ys_footer_main_padding_bottom', '' );
		if ( '' !== $padding_bottom ) {
			$css_vars = array_merge(
				$css_vars,
				Enqueue_Utility::get_css_var(
					'footer--padding-bottom',
					CSS::check_and_add_unit( $padding_bottom )
				)
			);
		}
		// フッターメイン コンテンツ間の余白.
		$footer_content_gap = Option::get_option( 'ys_footer_main_content_gap', '' );
		if ( '' !== $footer_content_gap ) {
			$css_vars = array_merge(
				$css_vars,
				Enqueue_Utility::get_css_var(
					'footer--content--gap',
					CSS::check_and_add_unit( $footer_content_gap )
				)
			);
		}

		return $css_vars;
	}

	/**
	 * フッターナビゲーションのCSS変数を追加
	 *
	 * @param array $css_vars CSS変数.
	 *
	 * @return array|mixed
	 */
	public function add_css_var_footer_widget( $css_vars ) {
		// フッターウィジェット間の余白.
		$footer_widget_gap = Option::get_option( 'ys_footer_widget_column_gap', '' );
		if ( '' !== $footer_widget_gap ) {
			$css_vars = array_merge(
				$css_vars,
				Enqueue_Utility::get_css_var(
					'footer--widget--column-gap',
					CSS::check_and_add_unit( $footer_widget_gap )
				)
			);
		}

		return $css_vars;
	}

	/**
	 * フッターナビゲーションのCSS変数を追加
	 *
	 * @param array $css_vars CSS変数.
	 *
	 * @return array|mixed|string[]
	 */
	public function add_css_var_footer_nav( $css_vars ) {
		// フッターナビゲーション文字サイズ.
		$font_size = Option::get_option( 'ys_footer_nav_font_size', '' );
		if ( '' !== $font_size ) {
			$css_vars = array_merge(
				$css_vars,
				Enqueue_Utility::get_css_var(
					'footer--nav--font-size',
					CSS::check_and_add_unit( $font_size )
				)
			);
		}
		// フッターナビゲーション 間隔（横）.
		$gap_x = Option::get_option( 'ys_footer_nav_gap_x', '' );
		if ( '' !== $gap_x ) {
			$css_vars = array_merge(
				$css_vars,
				Enqueue_Utility::get_css_var(
					'footer--nav--gap-column',
					CSS::check_and_add_unit( $gap_x )
				)
			);
		}
		// フッターナビゲーション 間隔（縦）.
		$gap_y = Option::get_option( 'ys_footer_nav_gap_y', '' );
		if ( '' !== $gap_y ) {
			$css_vars = array_merge(
				$css_vars,
				Enqueue_Utility::get_css_var(
					'footer--nav--gap-row',
					CSS::check_and_add_unit( $gap_y )
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
			$padding  = Enqueue_Utility::get_css_var(
				'sub-footer--padding',
				CSS::check_and_add_unit( $padding )
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
				'name'          => __( 'フッター左', 'ystandard' ),
				'id'            => 'footer-left',
				'description'   => __( 'フッターエリア左側', 'ystandard' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			]
		);
		register_sidebar(
			[
				'name'          => __( 'フッター中央', 'ystandard' ),
				'id'            => 'footer-center',
				'description'   => __( 'フッターエリア中央', 'ystandard' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			]
		);
		register_sidebar(
			[
				'name'          => __( 'フッター右', 'ystandard' ),
				'id'            => 'footer-right',
				'description'   => __( 'フッターエリア右側', 'ystandard' ),
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
				'description' => __( 'フッターのデザイン設定', 'ystandard' ),
				'priority'    => Customizer::get_priority( 'ys_site_footer' ),
			]
		);
		$customizer->add_section_label(
			__( 'フッターメインエリア　色設定', 'ystandard' ),
			[
				'description' => Admin::manual_link( 'manual/footer-area' ) . '<br>' . __( 'コピーライトの設定は「[ys]Copyright設定」から設定してください。', 'ystandard' ),
			]
		);
		// フッター背景色.
		$customizer->add_color(
			[
				'id'      => 'ys_color_footer_bg',
				'default' => '',
				'label'   => __( 'フッター背景色', 'ystandard' ),
			]
		);
		// フッター文字色.
		$customizer->add_color(
			[
				'id'      => 'ys_color_footer_font',
				'default' => '',
				'label'   => __( 'フッター文字色', 'ystandard' ),
			]
		);
		// フッター文字色(グレー).
		$customizer->add_color(
			[
				'id'      => 'ys_color_footer_text_gray',
				'default' => '',
				'label'   => __( 'フッター文字色(グレー)', 'ystandard' ),
			]
		);
		/**
		 * フッターメイン余白.
		 */
		$customizer->add_section_label(
			__( 'フッターメインエリア　余白設定', 'ystandard' ),
			[
				'description' => Admin::manual_link( 'manual/footer-area' ) . '<br>' . __( '単位付きで入力してください。数値のみを入力した場合は単位はpxになります。', 'ystandard' ),
			]
		);
		// フッターメイン 上部padding.
		$customizer->add_text(
			[
				'id'      => 'ys_footer_main_padding_top',
				'default' => '',
				'label'   => __( 'フッター(メイン)上部 余白', 'ystandard' ),
			]
		);
		// フッターメイン 下部padding.
		$customizer->add_text(
			[
				'id'      => 'ys_footer_main_padding_bottom',
				'default' => '',
				'label'   => __( 'フッター(メイン)下部 余白', 'ystandard' ),
			]
		);
		// フッターメイン コンテンツ間の余白.
		$customizer->add_text(
			[
				'id'      => 'ys_footer_main_content_gap',
				'default' => '',
				'label'   => __( 'フッターウィジェットとフッターナビゲーション間の余白', 'ystandard' ),
			]
		);
		/**
		 * フッターウィジェット.
		 */
		$customizer->add_section_label(
			__( 'フッターウィジェット', 'ystandard' ),
			[
				'description' => __( '単位付きで入力してください。数値のみを入力した場合は単位はpxになります。', 'ystandard' ),
			]
		);
		// フッターウィジェット間の余白.
		$customizer->add_text(
			[
				'id'          => 'ys_footer_widget_column_gap',
				'default'     => '',
				'label'       => __( 'フッターウィジェット間の余白', 'ystandard' ),
				'description' => __( 'フッターウィジェットエリアを2種類以上使用する場合、ウィジェットエリア間の余白を変更できます。', 'ystandard' ),
			]
		);

		/**
		 * フッターナビゲーション.
		 */
		$customizer->add_section_label(
			__( 'フッターナビゲーション', 'ystandard' ),
			[
				'description' => __( '単位付きで入力してください。数値のみを入力した場合は単位はpxになります。', 'ystandard' ),
			]
		);
		// フッターナビゲーション文字サイズ.
		$customizer->add_text(
			[
				'id'      => 'ys_footer_nav_font_size',
				'default' => '',
				'label'   => __( 'フッターナビゲーション文字サイズ', 'ystandard' ),
			]
		);
		// フッターナビゲーション 間隔（横）.
		$customizer->add_text(
			[
				'id'          => 'ys_footer_nav_gap_x',
				'default'     => '',
				'label'       => __( 'メニュー間の余白（横）', 'ystandard' ),
				'description' => __( '横方向の余白の設定です。', 'ystandard' ),
			]
		);
		// フッターナビゲーション 間隔（縦）.
		$customizer->add_text(
			[
				'id'          => 'ys_footer_nav_gap_y',
				'default'     => '',
				'label'       => __( 'メニュー間の余白（縦）', 'ystandard' ),
				'description' => __( '縦方向の余白の設定です。メニュー数が多くなった場合や画面サイズが小さい場合にメニューが折り返し表示になった場合等に縦方向の余白が必要になります。', 'ystandard' ),
			]
		);

		/**
		 * サブフッター
		 */
		$customizer->add_section_label(
			__( 'サブフッター設定', 'ystandard' ),
			[
				'description' => Admin::manual_link( 'manual/sub-footer' ),
			]
		);
		// フッター背景色.
		$customizer->add_color(
			[
				'id'      => 'ys_color_sub_footer_bg',
				'default' => '',
				'label'   => __( 'サブフッター背景色', 'ystandard' ),
			]
		);
		// フッター文字色.
		$customizer->add_color(
			[
				'id'      => 'ys_color_sub_footer_text',
				'default' => '',
				'label'   => __( 'サブフッター文字色', 'ystandard' ),
			]
		);
		// サブフッター コンテンツ.
		$customizer->add_select(
			[
				'id'          => 'ys_footer_sub_content',
				'default'     => 0,
				'label'       => __( 'サブフッター コンテンツ', 'ystandard' ),
				'description' => __( 'サブフッターに表示する内容を[ys]パーツから選択します。', 'ystandard' ),
				'choices'     => Parts::get_parts_list( true ),
			]
		);
		$customizer->add_text(
			[
				'id'          => 'ys_sub_footer_padding',
				'default'     => '',
				'label'       => __( 'サブフッター上下余白', 'ystandard' ),
				'description' => __( '単位付きで入力してください。数値のみを入力した場合は単位はpxになります。', 'ystandard' ),
			]
		);
		/**
		 * ページ先頭へ戻るボタン
		 */
		$customizer->add_section_label(
			__( 'ページ先頭へ戻るボタン', 'ystandard' ),
			[
				'description' => Admin::manual_link( 'manual/back-to-top' ),
			]
		);
		$customizer->add_checkbox(
			[
				'id'      => 'ys_back_to_top_active',
				'default' => 1,
				'label'   => __( 'ページ先頭へ戻るボタンを表示する', 'ystandard' ),
			]
		);
		// ページ先頭へ戻るボタンテキスト.
		$short_code_page = admin_url( 'admin.php?page=ystandard-icons' );
		$customizer->add_text(
			[
				'id'                => 'ys_back_to_top_text',
				'default'           => '[ys_icon name="arrow-up"]',
				'label'             => __( 'ページ先頭へ戻るボタンテキスト', 'ystandard' ),
				// translators: %s: アイコンショートコードの管理画面URL.
				'description'       => sprintf( __( '<a href="%s" target="_blank">アイコンショートコード</a>と<code>img</code>タグが使用できます。', 'ystandard' ), $short_code_page ),
				'sanitize_callback' => [ $this, 'sanitize_back_to_top_text' ],
			]
		);
		// ページ先頭へ戻るボタン背景色.
		$customizer->add_color(
			[
				'id'      => 'ys_back_to_top_bg_color',
				'default' => '',
				'label'   => __( '先頭へ戻るボタン背景色', 'ystandard' ),
			]
		);
		// ページ先頭へ戻るボタン文字色.
		$customizer->add_color(
			[
				'id'      => 'ys_back_to_top_color',
				'default' => '',
				'label'   => __( '先頭へ戻るボタン文字色', 'ystandard' ),
			]
		);
		$customizer->add_text(
			[
				'id'          => 'ys_back_to_top_border_radius',
				'default'     => '',
				'label'       => __( '先頭へ戻るボタンの角丸', 'ystandard' ),
				'description' => __( '単位付きで入力してください。数値のみを入力した場合は単位はpxになります。', 'ystandard' ),
			]
		);
		// 正方形.
		$customizer->add_label(
			[
				'id'          => 'ys_back_to_top_square_label',
				'label'       => __( 'ボタンの縦・横サイズをあわせる', 'ystandard' ),
				'description' => __( '縦長・横長のボタンにする場合はチェックを外してください', 'ystandard' ),
			]
		);
		$customizer->add_checkbox(
			[
				'id'      => 'ys_back_to_top_square',
				'default' => 1,
				'label'   => __( 'ボタンの縦・横サイズをあわせる', 'ystandard' ),
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
