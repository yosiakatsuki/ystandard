<?php
/**
 * SEO - タイトル・メタディスクリプション
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */

namespace ystandard;

use ystandard\customizer\Customizer_Controls;
use ystandard\utils\Manual;

/**
 * Class SEO_Title_Description
 */
class SEO_Title_Description {

	const SECTION_NAME = 'ys_seo_title_description';

	/**
	 * constructor.
	 */
	public function __construct() {
		add_action( 'customize_register', [ $this, 'customize_register' ] );
		add_action( 'wp_head', [ $this, 'add_meta_description' ] );
	}

	/**
	 * メタディスクリプションタグ出力
	 *
	 * @return void
	 */
	public function add_meta_description() {
// @todo: 2024/01/11 設定まで。出力機能は別途進める.
	}

	/**
	 * パネル追加
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 */
	public function customize_register( $wp_customize ) {
		$customizer = new Customizer_Controls( $wp_customize );
		// セクション.
		$customizer->add_section(
			[
				'section' => self::SECTION_NAME,
				'title'   => _x( 'タイトル・メタデスクリプション', 'customizer', 'ystandard' ),
				'panel'   => SEO_Option::PANEL_NAME,
			]
		);

		// タイトル関連の設定.
		$customizer->add_section_label(
			_x( 'titleタグ関連の設定', 'customizer', 'ystandard' ),
			[
				'description' => Manual::manual_link( 'manual/seo-title' ),
			]
		);
		$customizer->add_text(
			[
				'id'          => 'ys_title_separate',
				'default'     => '',
				'transport'   => 'postMessage',
				'label'       => _x( 'titleタグの区切り文字', 'customizer', 'ystandard' ),
				'description' => _x( '※区切り文字の前後に半角空白が自動で挿入されます', 'customizer', 'ystandard' ),
			]
		);

		// メタデスクリプションの設定.
		$customizer->add_section_label(
			_x( 'メタデスクリプションの設定', 'customizer', 'ystandard' ),
			[
				'description' => Manual::manual_link( 'manual/meta-description' ),
			]
		);
		// 出力設定.
		$customizer->add_checkbox(
			[
				'id'          => 'ys_option_create_meta_description',
				'default'     => 1,
				'transport'   => 'postMessage',
				'label'       => _x( 'meta descriptionを本文から作成する', 'customizer', 'ystandard' ),
				'description' => _x( 'メタデスクリプションを投稿本文から自動で作成します。※SEO関連プラグインなどを利用する場合はこの設定をOFFにすることをおすすめします。', 'customizer', 'ystandard' ),
			]
		);
		// 抜粋文字数.
		$customizer->add_number(
			[
				'id'        => 'ys_option_meta_description_length',
				'default'   => 80,
				'transport' => 'postMessage',
				'label'     => _x( 'meta descriptionに使用する文字数', 'customizer', 'ystandard' ),
			]
		);
		// TOPページのメタデスクリプション.
		$customizer->add_section_label(
			_x( 'TOPページのメタデスクリプション', 'customizer', 'ystandard' ),
			[
				'description' => Manual::manual_link( 'manual/top-meta-description' ),
			]
		);
		$customizer->add_plain_textarea(
			[
				'id'          => 'ys_wp_site_description',
				'default'     => '',
				'transport'   => 'postMessage',
				'label'       => _x( 'TOPページのメタデスクリプション', 'customizer', 'ystandard' ),
				'description' => _x( '※HTMLタグ・改行は削除されます', 'customizer', 'ystandard' ),
			]
		);

	}
}

new SEO_Title_Description();
