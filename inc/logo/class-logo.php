<?php
/**
 * ロゴ関連
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

/**
 * Class Logo
 *
 * @package ystandard
 */
class Logo {

	/**
	 * Logo constructor.
	 */
	public function __construct() {
		add_action( 'after_setup_theme', [ $this, 'register_custom_logo' ] );
	}

	/**
	 * カスタムロゴの登録
	 *
	 * @return void
	 */
	public function register_custom_logo() {
		// カスタムロゴ.
		add_theme_support(
			'custom-logo',
			apply_filters(
				'ys_custom_logo_param',
				[
					'height'      => 50,
					'width'       => 250,
					'flex-height' => true,
					'flex-width'  => true,
				]
			)
		);
	}
}

new Logo();
