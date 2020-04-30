<?php
/**
 * Copyright
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

/**
 * Class Copyright
 *
 * @package ystandard
 */
class Copyright {

	/**
	 * Copyright constructor.
	 */
	public function __construct() {
		add_action( 'customize_register', [ $this, 'customize_register' ] );
	}

	/**
	 * Copyright 取得
	 */
	public static function get_copyright() {

		$copy = apply_filters(
			'ys_copyright',
			Option::get_option( 'ys_copyright', self::get_default() )
		);
		if ( $copy ) {
			$copy = sprintf(
				'<p id="footer-copy" class="copyright">%s</p>',
				$copy
			);
		}

		return $copy;
	}

	/**
	 * デフォルト表示
	 *
	 * @return string
	 */
	public static function get_default() {

		$year      = date_i18n( 'Y' );
		$url       = esc_url( home_url( '/' ) );
		$blog_name = get_bloginfo( 'name' );

		return sprintf(
			'&copy; %s <a href="%s" rel="home">%s</a>',
			esc_html( $year ),
			$url,
			$blog_name
		);
	}

	/**
	 * Powered By
	 *
	 * @return string
	 */
	public static function get_poweredby() {
		/**
		 * テーマの情報
		 */
		$theme = '<a href="https://wp-ystandard.com" target="_blank" rel="nofollow noopener noreferrer">yStandard Theme</a> by <a href="https://yosiakatsuki.net/blog/" target="_blank" rel="nofollow noopener noreferrer">yosiakatsuki</a> ';
		$theme = apply_filters( 'ys_poweredby_theme', $theme );
		/**
		 * WordPress
		 */
		$url      = __( 'https://wordpress.org/' );
		$powerdby = sprintf(
			'Powered by <a href="%s" target="_blank" rel="nofollow noopener noreferrer">WordPress</a>',
			$url
		);
		/**
		 * Powered By
		 */
		$html = sprintf(
			'<p id="footer-poweredby" class="footer-poweredby">%s%s</p>',
			$theme,
			$powerdby
		);

		return apply_filters( 'ys_poweredby', $html );
	}

	/**
	 * フッターコピーライト表示取得
	 *
	 * @return string
	 */
	public static function get_site_info() {

		return self::get_copyright() . self::get_poweredby();
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
				'section'     => 'ys_design_copyright',
				'title'       => 'Copyright',
				'description' => 'Copyrightの設定' . Admin::manual_link( 'copyright' ),
				'priority'    => 1010,
				'panel'       => Design::PANEL_NAME,
			]
		);
		/**
		 * Copyright
		 */
		$customizer->add_text(
			[
				'id'                => 'ys_copyright',
				'default'           => self::get_default(),
				'label'             => 'Copyright',
				'sanitize_callback' => [ $this, 'sanitize_copyright' ],
			]
		);
	}

	/**
	 * Copyrightのサニタイズ
	 *
	 * @param string $value Text.
	 *
	 * @return string
	 */
	public function sanitize_copyright( $value ) {
		$allowed_html   = wp_kses_allowed_html( 'post' );
		$allowed_a      = isset( $allowed_html['a'] ) ? $allowed_html['a'] : [];
		$allowed_span   = isset( $allowed_html['span'] ) ? $allowed_html['span'] : [];
		$allowed_strong = isset( $allowed_html['strong'] ) ? $allowed_html['strong'] : [];

		return wp_kses(
			$value,
			[
				'a'      => $allowed_a,
				'span'   => $allowed_span,
				'strong' => $allowed_strong,
			]
		);
	}
}

new Copyright();
