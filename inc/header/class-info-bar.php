<?php
/**
 * お知らせバー
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

/**
 * Class Info_Bar
 */
class Info_Bar {

	/**
	 * フックやショートコードの登録
	 */
	public function register() {
		add_action( 'customize_register', [ $this, 'customize_register' ] );
		add_action( 'ys_after_site_header', [ $this, 'show_info_bar' ], 1 );
		add_filter( Enqueue_Utility::FILTER_INLINE_CSS, [ $this, 'info_bar_css' ] );
		add_filter( Enqueue_Utility::FILTER_CSS_VARS, [ $this, 'add_css_var_info_bar' ] );
	}

	/**
	 * お知らせバー表示
	 */
	public function show_info_bar() {
		if ( ! apply_filters( 'ys_show_info_bar', true ) ) {
			return;
		}

		if ( empty( Option::get_option( 'ys_info_bar_text', '' ) ) ) {
			return;
		}
		$info_bar_class = [
			'info-bar',
		];
		if ( Option::get_option( 'ys_info_bar_url', '' ) ) {
			$info_bar_class[] = 'has-link';
		}
		$text = Option::get_option( 'ys_info_bar_text', '' );
		$url  = Option::get_option( 'ys_info_bar_url', '' );
		if ( ! empty( $url ) ) {
			$text = wp_kses( $text, self::info_bar_kses_allowed_html( [ 'a' ] ) );
		}
		/**
		 * 設定取得
		 */
		$data = [
			'text'   => $text,
			'url'    => $url,
			'target' => Option::get_option_by_bool( 'ys_info_bar_external', false ) ? '_blank' : '_self',
			'class'  => implode( ' ', $info_bar_class ),
		];

		ob_start();
		ys_get_template_part(
			'template-parts/parts/info-bar',
			null,
			[ 'info_bar_data' => $data ]
		);

		echo wp_targeted_link_rel( ob_get_clean() );
	}

	/**
	 * お知らせバー 色
	 *
	 * @param array $css_vars CSS.
	 *
	 * @return array
	 */
	public function add_css_var_info_bar( $css_vars ) {
		$bg    = Enqueue_Utility::get_css_var(
			'info-bar-bg',
			Option::get_option( 'ys_info_bar_bg_color', '#f1f1f3' )
		);
		$color = Enqueue_Utility::get_css_var(
			'info-bar-text',
			Option::get_option( 'ys_info_bar_text_color', '#222222' )
		);

		return array_merge(
			$css_vars,
			$bg,
			$color
		);
	}

	/**
	 * お知らせバー用CSS追加
	 *
	 * @param string $css スタイルシート.
	 *
	 * @return string
	 */
	public function info_bar_css( $css ) {
		/**
		 * 基本系
		 */
		$styles[] = '
		.info-bar {
			padding:0.5em 0;
			text-align:center;
			line-height:1.3;
			font-size:0.8em;
		}';
		$styles[] = Enqueue_Styles::add_media_query(
			'.info-bar {
				font-size:1rem;
			}',
			'sm'
		);
		$styles[] = '
		.info-bar.has-link:hover {
			opacity:0.8;
		}';
		$styles[] = '
		.info-bar a {
			color:currentColor;
		}';
		$styles[] = '
		.info-bar__link {
			display:block;
			color:currentColor;
		}';
		/**
		 * 色
		 */
		$styles[] = '.info-bar {
			background-color:var(--info-bar-bg);
			color:var(--info-bar-text);
		}';
		/**
		 * 太字
		 */
		if ( Option::get_option_by_bool( 'ys_info_bar_text_bold', true ) ) {
			$styles[] = '
			.info-bar__text{font-weight:700;}';
		}
		/**
		 * 下線
		 */
		if ( Option::get_option( 'ys_info_bar_url', '' ) ) {
			$decoration = Option::get_option_by_bool( 'ys_info_bar_underline', true ) ? 'underline' : 'none';
			$styles[]   = ".info-bar__link{text-decoration: ${decoration};}";
		}

		return $css . implode( ' ', $styles );
	}

	/**
	 * 設定追加
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
				'section'     => 'ys_info_bar',
				'title'       => '[ys]お知らせバー',
				'description' => 'ヘッダー下に表示されるお知らせバーの設定' . Admin::manual_link( 'info-bar' ),
				'priority'    => Customizer::get_priority( 'ys_info_bar' ),
			]
		);

		$customizer->add_section_label( 'テキスト' );
		// お知らせバーテキスト.
		$customizer->add_text(
			[
				'id'                => 'ys_info_bar_text',
				'default'           => '',
				'label'             => 'お知らせテキスト',
				'description'       => 'span,br,strongタグが使えます。<br>「リンク先URL」が空白の場合に限りaタグも使えます。',
				'sanitize_callback' => [ $this, 'sanitize_info_bar_text' ],
			]
		);
		// テキストカラー.
		$customizer->add_color(
			[
				'id'      => 'ys_info_bar_text_color',
				'default' => '#222222',
				'label'   => 'お知らせバー文字色',
			]
		);
		// 背景色カラー.
		$customizer->add_color(
			[
				'id'      => 'ys_info_bar_bg_color',
				'default' => '#f1f1f3',
				'label'   => 'お知らせバー背景色',
			]
		);
		// お知らせテキストを太字にする.
		$customizer->add_checkbox(
			[
				'id'      => 'ys_info_bar_text_bold',
				'default' => 1,
				'label'   => 'お知らせテキストを太字にする',
			]
		);
		$customizer->add_section_label( 'リンク' );
		// お知らせURL.
		$customizer->add_url(
			[
				'id'      => 'ys_info_bar_url',
				'default' => '',
				'label'   => 'リンク先URL',
			]
		);
		// お知らせリンクを新しいタブで開く.
		$customizer->add_checkbox(
			[
				'id'      => 'ys_info_bar_external',
				'default' => 0,
				'label'   => 'お知らせリンクを新しいタブで開く',
			]
		);

		// お知らせリンクを新しいタブで開く.
		$customizer->add_checkbox(
			[
				'id'      => 'ys_info_bar_underline',
				'default' => 0,
				'label'   => 'お知らせテキストに下線を付ける',
			]
		);

	}

	/**
	 * お知らせバーで使えるHTML
	 *
	 * @param array $remove 除外するHTML.
	 *
	 * @return array
	 */
	public static function info_bar_kses_allowed_html( $remove = [] ) {
		$allowed_html     = wp_kses_allowed_html( 'post' );
		$new_allowed_html = [];
		if ( isset( $allowed_html['a'] ) ) {
			$new_allowed_html['a'] = $allowed_html['a'];
		}
		if ( isset( $allowed_html['span'] ) ) {
			$new_allowed_html['span'] = $allowed_html['span'];
		}
		if ( isset( $allowed_html['br'] ) ) {
			$new_allowed_html['br'] = $allowed_html['br'];
		}
		if ( isset( $allowed_html['strong'] ) ) {
			$new_allowed_html['strong'] = $allowed_html['strong'];
		}
		foreach ( $remove as $item ) {
			if ( array_key_exists( $item, $new_allowed_html ) ) {
				unset( $new_allowed_html[ $item ] );
			}
		}

		return apply_filters( 'info_bar_kses_allowed_html', $new_allowed_html );
	}

	/**
	 * お知らせバーテキストのサニタイズ
	 *
	 * @param string $text Text.
	 *
	 * @return string
	 */
	public function sanitize_info_bar_text( $text ) {
		$text = wp_encode_emoji( $text );
		$text = wp_kses( $text, self::info_bar_kses_allowed_html() );

		return $text;
	}
}


$class_info_bar = new Info_Bar();
$class_info_bar->register();
