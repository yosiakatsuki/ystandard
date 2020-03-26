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
	 * YS_Info_Bar constructor.
	 */
	public function __construct() {
		add_action( 'get_header', [ $this, 'set_hooks' ] );
	}

	/**
	 * アクション・フィルターフックの瀬戸
	 */
	public function set_hooks() {
		add_action( 'ys_after_site_header', [ $this, 'show_info_bar' ], 1 );
		add_filter( Enqueue_Styles::INLINE_CSS_HOOK, [ $this, 'info_bar_css' ] );
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
}


new Info_Bar();
