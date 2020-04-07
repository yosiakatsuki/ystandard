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

		$copy = apply_filters( 'ys_copyright', self::get_default() );
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
		$year = ys_get_option( 'ys_copyright_year', date_i18n( 'Y' ) );
		if ( '' === $year ) {
			$year = date_i18n( 'Y' );
		}
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
				'description' => 'Copyrightの設定',
				'priority'    => 1010,
				'panel'       => Design::PANEL_NAME,
			]
		);
		/**
		 * 発行年数
		 */
		$customizer->add_number(
			[
				'id'          => 'ys_copyright_year',
				'default'     => date_i18n( 'Y' ),
				'label'       => '発行年(Copyright)',
				'section'     => 'ys_design_copyright',
				'input_attrs' => [
					'min' => 1900,
					'max' => 2100,
				],
			]
		);
	}
}

new Copyright();
