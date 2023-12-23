<?php
/**
 * ヘッダー設定
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */

namespace ystandard;

use ystandard\customizer\Customizer;
use ystandard\customizer\Customizer_Controls;
use ystandard\utils\Manual;

defined( 'ABSPATH' ) || die();

/**
 * Class Header_Customizer
 *
 * @package ystandard
 */
class Header_Option {

	/**
	 * Header_Customizer constructor.
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
		$section = 'ys_panel_header';
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

		$customizer->add_text(
			[
				'id'          => 'ys_title_separate',
				'default'     => '',
				'transport'   => 'postMessage',
				'label'       => _x( 'titleタグの区切り文字', 'customizer', 'ystandard' ),
				'description' => _x( '※区切り文字の前後に半角空白が自動で挿入されます', 'customizer', 'ystandard' ),
			]
		);

		$customizer->add_checkbox(
			[
				'id'          => 'ys_wp_hidden_blogdescription',
				'default'     => 0,
				'label'       => _x( 'キャッチフレーズを非表示にする', 'customizer', 'ystandard' ),
				'description' => _x( 'サイトタイトル・ロゴの下にキャッチフレーズを表示したくない場合はチェックを付けて下さい', 'customizer', 'ystandard' ),
				'section'     => 'title_tagline',
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

	/**
	 * サイト基本設定の追加
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 */
	public function register_title_tagline( $wp_customize ) {

		$customizer->set_section_description(
			'title_tagline',
			_x( 'サイトロゴやキャッチフレーズの設定', 'customizer', 'ystandard' ) . Manual::manual_link( 'manual/basic-settings' )
		);


		/**
		 * サイトアイコン・apple touch icon設定追加
		 */
		$customizer->add_section_label(
			'サイトアイコン',
			[
				'section'  => 'title_tagline',
				'priority' => 20,
			]
		);
		$wp_customize->get_control( 'site_icon' )->description = 'ファビコン用の画像を設定して下さい。縦横512px以上推奨です。';
		$wp_customize->add_setting(
			'ys_apple_touch_icon',
			[
				'type'       => 'option',
				'capability' => 'manage_options',
				'transport'  => 'postMessage',
			]
		);
		$wp_customize->add_control(
			new \WP_Customize_Site_Icon_Control(
				$wp_customize,
				'ys_apple_touch_icon',
				[
					'label'       => 'apple touch icon',
					'description' => 'apple touch icon用の画像を設定して下さい。縦横512spx以上推奨です。',
					'section'     => 'title_tagline',
					'priority'    => 61,
					'height'      => 512,
					'width'       => 512,
				]
			)
		);
	}

	/**
	 * サイトヘッダー設定
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 */
	public function register_header_design( $wp_customize ) {
		$customizer = new Customizer_Controls( $wp_customize );
		/**
		 * セクション追加
		 */
		$customizer->add_section(
			[
				'section'     => 'ys_customizer_section_header_design',
				'title'       => 'サイトヘッダー',
				'description' => 'サイトヘッダー部分のデザイン設定' . Manual::manual_link( 'manual/site-header' ),
				'priority'    => 50,
				'panel'       => Design::PANEL_NAME,
			]
		);
		$customizer->add_section_label( 'ヘッダータイプ' );
		/**
		 * ヘッダータイプ
		 */
		$assets_url = Customizer::get_assets_dir_uri();
		$row1       = $assets_url . '/design/header/1row.png';
		$center     = $assets_url . '/design/header/center.png';
		$row2       = $assets_url . '/design/header/2row.png';
		$img        = '<img src="%s" alt="" width="100" height="100" />';
		$customizer->add_image_label_radio(
			[
				'id'          => 'ys_design_header_type',
				'default'     => 'row1',
				'label'       => '表示タイプ',
				'description' => 'ヘッダーの表示タイプ',
				'section'     => 'ys_customizer_section_header_design',
				'choices'     => [
					'row1'   => sprintf( $img, $row1 ),
					'center' => sprintf( $img, $center ),
					'row2'   => sprintf( $img, $row2 ),
				],
			]
		);
		$customizer->add_section_label( 'ヘッダーデザイン' );
		// ヘッダー背景色.
		$customizer->add_color(
			[
				'id'      => 'ys_color_header_bg',
				'default' => '#ffffff',
				'label'   => '背景色',
			]
		);
		// サイトタイトル文字色.
		$customizer->add_color(
			[
				'id'      => 'ys_color_header_font',
				'default' => '#222222',
				'label'   => '文字色',
			]
		);
		// サイト概要の文字色.
		$customizer->add_color(
			[
				'id'      => 'ys_color_header_dscr_font',
				'default' => '#656565',
				'label'   => '概要文の文字色',
			]
		);
		// ボックスシャドウ.
		$customizer->add_select(
			[
				'id'      => 'ys_header_box_shadow',
				'default' => 'none',
				'label'   => 'ヘッダーに影をつける',
				'choices' => [
					'none'  => '影なし',
					'small' => '小さめ',
					'large' => '大きめ',
				],
			]
		);
		// 検索フォーム.
		$customizer->add_section_label( '検索フォーム' );
		$customizer->add_label(
			[
				'id'          => 'ys_show_header_search_form_label',
				'label'       => '検索フォーム表示',
				'description' => 'ヘッダーに検索フォームを表示します。<br>モバイルではスライドメニュー内にフォームが表示され、PCでは検索フォーム表示ボタンがヘッダーに追加されます。',
			]
		);
		// スライドメニューに検索フォームを出力する.
		$customizer->add_checkbox(
			[
				'id'      => 'ys_show_header_search_form',
				'default' => 1,
				'label'   => '検索フォームを表示する',
			]
		);

		/**
		 * ヘッダー固定表示
		 */
		$customizer->add_section_label(
			'ヘッダー固定表示',
			[
				'description' => Admin::manual_link( 'fixed-header' ),
			]
		);

		$customizer->add_checkbox(
			[
				'id'      => 'ys_header_fixed',
				'default' => 0,
				'label'   => 'ヘッダーを画面上部に固定する',
			]
		);
		/**
		 * ヘッダー固定表示
		 */
		$customizer->add_label(
			[
				'id'          => 'ys_header_fixed_height_label',
				'label'       => 'ヘッダー高さ',
				'description' => 'ヘッダーの固定表示をする場合、ヘッダー高さを指定すると表示のガタつきを抑えられます。<br><br>プレビュー画面左上に表示された「ヘッダー高さ」の数字を参考に以下の設定に入力してください。',

			]
		);
		/**
		 * ヘッダー高さ(PC)
		 */
		$customizer->add_number(
			[
				'id'      => 'ys_header_fixed_height_pc',
				'default' => 0,
				'label'   => '高さ(PC)',
			]
		);
		/**
		 * ヘッダー高さ(タブレット)
		 */
		$customizer->add_number(
			[
				'id'      => 'ys_header_fixed_height_tablet',
				'default' => 0,
				'label'   => '高さ(タブレット)',
			]
		);
		/**
		 * ヘッダー高さ(モバイル)
		 */
		$customizer->add_number(
			[
				'id'      => 'ys_header_fixed_height_mobile',
				'default' => 0,
				'label'   => '高さ(モバイル)',
			]
		);
	}
}

new Header_Option();
