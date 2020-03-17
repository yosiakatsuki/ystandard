<?php
/**
 * お知らせバー
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * Class YS_Info_Bar
 */
class YS_Info_Bar {

	/**
	 * YS_Info_Bar constructor.
	 */
	public function __construct() {
		add_action( 'get_header', array( $this, 'set_hooks' ) );
	}

	/**
	 * アクション・フィルターフックの瀬戸
	 */
	public function set_hooks() {
		if ( ! ys_is_has_header_media_full() ) {
			add_action( 'ys_after_site_header', array( $this, 'info_bar' ), 1 );
		}
		add_filter( 'ys_get_inline_css', array( $this, 'info_bar_css' ) );
	}

	/**
	 * お知らせバー表示
	 */
	public function info_bar() {
		if ( ! apply_filters( 'ys_show_info_bar', true ) ) {
			return;
		}

		if ( empty( ys_get_option( 'ys_info_bar_text', '' ) ) ) {
			return;
		}
		ob_start();
		get_template_part( 'template-parts/parts/info-bar' );

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
		$ys_inline_css = new YS_Inline_Css();
		$styles        = array();
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
		$styles[] = $ys_inline_css->add_media_query(
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
			.info-bar__text {
				font-weight:700;
			}';
		}

		/**
		 * ヘッダーメディアとの兼ね合い
		 */
		$styles[] = $this->add_margin();

		return $css . implode( ' ', $styles );
	}

	/**
	 * お知らせバー下の余白調整
	 *
	 * @return string
	 */
	private function add_margin() {
		$ys_inline_css = new YS_Inline_Css();
		if ( ! ys_is_active_custom_header() && ! ( ys_is_one_column() && 'full' === ys_get_option( 'ys_design_one_col_thumbnail_type', 'normal' ) ) ) {
			{
				return $ys_inline_css->add_media_query(
					'.info-bar {
						margin-bottom: 2rem;
					}',
					'md'
				);
			}
		}

		return '';
	}
}

new YS_Info_Bar();

