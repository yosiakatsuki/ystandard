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
	}

	/**
	 * お知らせバー表示
	 */
	public function show_info_bar() {
		if ( ! apply_filters( 'ys_show_info_bar', true ) ) {
			return;
		}

		if ( empty( ys_get_option( 'ys_info_bar_text', '' ) ) ) {
			return;
		}
		$info_bar_class = [
			'info-bar',
		];
		if ( ys_get_option( 'ys_info_bar_url', '' ) ) {
			$info_bar_class[] = 'has-link';
		}
		/**
		 * 設定取得
		 */
		$data = [
			'text'   => ys_get_option( 'ys_info_bar_text', '' ),
			'url'    => ys_get_option( 'ys_info_bar_url', '' ),
			'target' => ys_get_option_by_bool( 'ys_info_bar_external', false ) ? '_blank' : '_self',
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
	 * お知らせバー用CSS追加
	 *
	 * @param string $css スタイルシート.
	 *
	 * @return string
	 */
	public function info_bar_css( $css ) {
		/**
		 * 設定取得
		 */
		$text_color = ys_get_option( 'ys_info_bar_text_color', '#222222' );
		$bg_color   = ys_get_option( 'ys_info_bar_bg_color', '#f1f1f3' );
		$text_bold  = ys_get_option_by_bool( 'ys_info_bar_text_bold', true );
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
			'md'
		);
		$styles[] = '
		.info-bar.has-link:hover {
			opacity:0.8;
		}';
		$styles[] = '
		.info-bar__link {
			display:block;
			text-decoration: none;
		}';
		/**
		 * 色
		 */
		$styles[] = ".info-bar {
			background-color:${bg_color};
		}";
		$styles[] = "
		.info-bar .info-bar__text,
		.info-bar .info-bar__text:hover {
			color:${text_color};
		}";
		/**
		 * 太字
		 */
		if ( $text_bold ) {
			$styles[] = '
			.info-bar__text{font-weight:700;}';
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
				'description' => 'ヘッダー下に表示されるお知らせバーの設定',
				'priority'    => Customizer::get_priority( 'ys_info_bar' ),
			]
		);

		// お知らせバーテキスト.
		$customizer->add_text(
			[
				'id'      => 'ys_info_bar_text',
				'default' => '',
				'label'   => 'お知らせテキスト',
			]
		);
		// お知らせURL.
		$customizer->add_url(
			[
				'id'      => 'ys_info_bar_url',
				'default' => '',
				'label'   => 'お知らせテキストリンク',
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
	}
}


$class_info_bar = new Info_Bar();
$class_info_bar->register();
