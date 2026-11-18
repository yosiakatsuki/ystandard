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
		// カスタマイザー.
		add_action( 'customize_register', [ $this, 'customize_register' ] );
		// CSS変数.
		add_filter( 'ys_get_css_custom_properties_args', [ $this, 'add_css_var_footer_nav' ] );
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
	 * フッターテンプレートパーツを表示するか
	 *
	 * @return bool
	 */
	public static function is_active_template_part() {
		// 非表示を選んだ場合は、保存済みパーツの取得・出力を省く.
		if ( 'show' !== Option::get_option( 'ys_footer_template_part_display', 'show' ) ) {
			return false;
		}

		$template_part = get_block_template( get_stylesheet() . '//footer', 'wp_template_part' );

		return $template_part && '' !== trim( $template_part->content );
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
					'footer--nav--gap--column',
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
					'footer--nav--gap--row',
					CSS::check_and_add_unit( $gap_y )
				)
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
				'description' => __( 'フッターパーツの表示とナビゲーションの設定', 'ystandard' ),
				'priority'    => Customizer::get_priority( 'ys_site_footer' ),
			]
		);
		$customizer->add_toggle_group(
			[
				'id'          => 'ys_footer_template_part_display',
				'label'       => __( 'フッターテンプレートパーツの出力', 'ystandard' ),
				'description' => __( 'フッターの内容・色・余白は、フッターテンプレートパーツで設定してください。', 'ystandard' ),
				'default'     => 'show',
				'choices'     => [
					'show' => __( '出力する', 'ystandard' ),
					'hide' => __( '出力しない', 'ystandard' ),
				],
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
