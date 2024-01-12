<?php
/**
 * ロゴ 設定
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */

namespace ystandard;

use ystandard\customizer\Customizer_Controls;
use ystandard\utils\Manual;

defined( 'ABSPATH' ) || die();

/**
 * Class Logo_Option
 */
class Logo_Option {

	const SECTION_NAME = 'ys_panel_logo';

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'customize_register', [ $this, 'customize_register_logo' ] );
	}

	/**
	 * ロゴ設定の追加
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 */
	public function customize_register_logo( $wp_customize ) {
		$customizer = new Customizer_Controls( $wp_customize );
		// セクション名.
		$section = self::SECTION_NAME;
		// セクション追加.
		$customizer->add_section(
			[
				'section'     => $section,
				'title'       => '[ys]' . _x( 'ロゴ・タイトル', 'customizer', 'ystandard' ),
				'description' => _x( 'ロゴ・タイトルの設定', 'customizer', 'ystandard' ) . Manual::manual_link( 'manual/site-logo' ),
			]
		);

		$customizer->add_section_label(
			_x( 'サイトタイトル・キャッチフレーズ', 'customizer', 'ystandard' ),
			[
				'priority' => 9,
			]
		);
		// サイトのタイトル.
		$customizer->set_section( 'blogname', $section );
		$customizer->set_priority( 'blogname', 10 );
		// キャッチフレーズ.
		$customizer->set_section( 'blogdescription', $section );
		$customizer->set_priority( 'blogdescription', 10 );

		$customizer->add_checkbox(
			[
				'id'          => 'ys_wp_hidden_blogdescription',
				'default'     => 0,
				'label'       => _x( 'キャッチフレーズを非表示にする', 'customizer', 'ystandard' ),
				'description' => _x( 'サイトタイトル・ロゴの下にキャッチフレーズを表示したくない場合はチェックを付けて下さい', 'customizer', 'ystandard' ),
				'section'     => $section,
			]
		);

		// ロゴ設定.
		$customizer->add_section_label(
			_x( 'ロゴ', 'customizer', 'ystandard' ),
		);
		// ロゴ.
		$customizer->set_section( 'custom_logo', $section );
		$customizer->set_priority( 'custom_logo', 11 );
		$customizer->set_refresh( 'custom_logo' );
		// ロゴ表示最大値・最小値
		$customizer->add_number(
			[
				'id'          => 'ys_logo_width_max',
				'default'     => '',
				'label'       => _x( '最大幅', 'customizer', 'ystandard' ),
				'description' => _x( 'ロゴ表示幅の最大値設定', 'customizer', 'ystandard' ),
				'input_attrs' => [
					'min' => 0,
					'max' => 9999,
				],
				'priority'    => 11,
			]
		);
		$customizer->add_number(
			[
				'id'          => 'ys_logo_width_min',
				'default'     => '',
				'label'       => _x( '最小幅', 'customizer', 'ystandard' ),
				'description' => _x( 'ロゴ表示幅の最小値設定', 'customizer', 'ystandard' ),
				'input_attrs' => [
					'min' => 0,
					'max' => 9999,
				],
				'priority'    => 11,
			]
		);
	}
}

new Logo_Option();
