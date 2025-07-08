<?php
/**
 * フッターモバイルメニュー
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

use ystandard\utils\Convert;

defined( 'ABSPATH' ) || die();

/**
 * Class Footer
 *
 * @package ystandard
 */
class Footer_Mobile_Nav {
	/**
	 * Footer constructor.
	 */
	public function __construct() {
		add_action( 'after_setup_theme', [ $this, 'register_nav_menus' ], 30 );
		add_action( 'wp_footer', [ $this, 'footer_mobile_nav' ], 1 );
		add_filter( 'ys_get_inline_css', [ $this, 'add_footer_mobile_nav_css' ] );
	}

	/*
	 * ナビゲーションメニューの登録
	 */
	public function register_nav_menus() {
		register_nav_menus(
			[
				'mobile-footer'        => 'モバイルフッター',
				'mobile-footer-tablet' => 'モバイルフッター(タブレットサイズ用)',
			]
		);
		// メニュー不整合チェック.
		if ( ! has_nav_menu( 'mobile-footer' ) && has_nav_menu( 'mobile-footer-tablet' ) ) {
			add_action(
				'admin_notices',
				function () {
						Admin_Notice::warning( '「モバイルフッター(タブレットサイズ用)」を利用するためには「モバイルフッター」を設定する必要があります。' );
				}
			);
		}
	}

	public static function the_mobile_footer_menu() {
		$result = wp_nav_menu(
			[
				'theme_location' => 'mobile-footer',
				'menu_class'     => 'mobile-footer-nav__list',
				'container'      => false,
				'fallback_cb'    => '',
				'depth'          => 1,
				'walker'         => new \YS_Walker_Mobile_Footer_Menu(),
				'echo'           => false,
			]
		);
		// タブレット用メニューがある場合.
		if ( has_nav_menu( 'mobile-footer-tablet' ) ) {
			$result .= wp_nav_menu(
				[
					'theme_location' => 'mobile-footer-tablet',
					'menu_class'     => 'mobile-footer-nav__list is-tablet',
					'container'      => false,
					'fallback_cb'    => '',
					'depth'          => 1,
					'walker'         => new \YS_Walker_Mobile_Footer_Menu(),
					'echo'           => false,
				]
			);
		}

		return apply_filters( 'ys_the_mobile_footer_menu', $result );
	}

	/**
	 * モバイルフッター表示判断
	 *
	 * @return bool
	 */
	public static function show_footer_mobile_nav() {
		$result = has_nav_menu( 'mobile-footer' );

		$result = Convert::to_bool( apply_filters( 'ys_show_footer_mobile_nav', $result ) );

		// ウォジェットプレビューの場合は表示しない.
		if ( Widget::is_legacy_widget_preview() ) {
			$result = false;
		}

		return $result;
	}


	/**
	 * モバイルフッターメニュー用CSSクラス取得.
	 *
	 * @return string
	 */
	public static function the_mobile_footer_classes() {
		$tablet  = has_nav_menu( 'mobile-footer-tablet' );
		$classes = [ 'footer-mobile-nav' ];

		if ( $tablet ) {
			$classes[] = 'has-footer-mobile-nav--tablet';
		}

		$classes = apply_filters( 'ys_the_mobile_footer_classes', implode( ' ', $classes ) );

		return esc_attr( $classes );
	}

	/**
	 * モバイルフッター用CSS
	 *
	 * @param string $css CSS.
	 *
	 * @return string
	 */
	public function add_footer_mobile_nav_css( $css ) {

		if ( ! self::show_footer_mobile_nav() ) {
			return $css;
		}

		$css .= '';

		return $css;
	}

	/**
	 * モバイルフッターナビゲーションの表示
	 */
	public function footer_mobile_nav() {
		if ( ! self::show_footer_mobile_nav() ) {
			return;
		}
		Template::get_template_part( 'template-parts/footer/footer-mobile-nav' );
	}
}

new Footer_Mobile_Nav();
