<?php
/**
 * カスタマイザー : 色
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 色設定
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 * @return void
 */
function ys_customizer_color( $wp_customize ) {
	/**
	 * パネルの追加
	 */
	$wp_customize->add_panel(
		'ys_customizer_panel_color',
		array(
			'priority' => 40,
			'title'    => '色',
		)
	);

	/**
	 * サイト全体
	 */
	ys_customizer_add_site_color( $wp_customize );

	/**
	 * ヘッダー
	 */
	ys_customizer_add_header_color( $wp_customize );

	/**
	 * ナビゲーション
	 */
	ys_customizer_add_global_nav_color( $wp_customize );

	/**
	 * フッター
	 */
	ys_customizer_add_footer_color( $wp_customize );

	/**
	 * テーマカスタマイザーでの色変更機能を無効にする
	 */
	ys_customizer_add_disable_ys_color( $wp_customize );
}

/**
 * サイト全体の色
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_add_site_color( $wp_customize ) {
	/**
	 * サイト全体色
	 */
	$wp_customize->add_section(
		'ys_color_site',
		array(
			'title'    => 'サイト全体',
			'panel'    => 'ys_customizer_panel_color',
			'priority' => 0,
		)
	);
	/**
	 * サイト背景色
	 */
	ys_customizer_add_setting_color(
		$wp_customize,
		array(
			'section' => 'ys_color_site',
			'id'      => 'ys_color_site_bg',
			'default' => ys_customizer_get_default_color( 'ys_color_site_bg' ),
			'label'   => 'サイト背景色',
		)
	);

	/**
	 * サイト文字色(メイン)
	 */
	ys_customizer_add_setting_color(
		$wp_customize,
		array(
			'section' => 'ys_color_site',
			'id'      => 'ys_color_site_font',
			'default' => ys_customizer_get_default_color( 'ys_color_site_font' ),
			'label'   => 'サイト文字色（メイン）',
		)
	);
	/**
	 * サイト文字色（グレー）
	 */
	ys_customizer_add_setting_color(
		$wp_customize,
		array(
			'section' => 'ys_color_site',
			'id'      => 'ys_color_site_font_sub',
			'default' => ys_customizer_get_default_color( 'ys_color_site_font_sub' ),
			'label'   => 'サイト文字色（グレー）',
		)
	);
}

/**
 * ヘッダー色
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_add_header_color( $wp_customize ) {
		/**
		 * ヘッダー色
		 */
		$wp_customize->add_section(
			'ys_color_header',
			array(
				'title'    => 'ヘッダー',
				'panel'    => 'ys_customizer_panel_color',
				'priority' => 0,
			)
		);
		// ヘッダー背景色.
		ys_customizer_add_setting_color(
			$wp_customize,
			array(
				'section' => 'ys_color_header',
				'id'      => 'ys_color_header_bg',
				'default' => ys_customizer_get_default_color( 'ys_color_header_bg' ),
				'label'   => 'ヘッダー背景色',
			)
		);
		// ヘッダー文字色.
		ys_customizer_add_setting_color(
			$wp_customize,
			array(
				'section' => 'ys_color_header',
				'id'      => 'ys_color_header_font',
				'default' => ys_customizer_get_default_color( 'ys_color_header_font' ),
				'label'   => 'ヘッダー文字色',
			)
		);
}

/**
 * ナビゲーション色
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_add_global_nav_color( $wp_customize ) {
		/**
		 * ナビゲーション色
		 */
		$wp_customize->add_section(
			'ys_color_nav',
			array(
				'title'    => 'グローバルナビゲーション',
				'panel'    => 'ys_customizer_panel_color',
				'priority' => 0,
			)
		);

		/**
		 * ナビゲーション背景色（PC）
		 */
		ys_customizer_add_setting_color(
			$wp_customize,
			array(
				'section' => 'ys_color_nav',
				'id'      => 'ys_color_nav_bg_pc',
				'default' => ys_customizer_get_default_color( 'ys_color_nav_bg_pc' ),
				'label'   => '[PC]ナビゲーション背景色',
			)
		);
		/**
		 * ナビゲーション文字色（PC）
		 */
		ys_customizer_add_setting_color(
			$wp_customize,
			array(
				'section' => 'ys_color_nav',
				'id'      => 'ys_color_nav_font_pc',
				'default' => ys_customizer_get_default_color( 'ys_color_nav_font_pc' ),
				'label'   => '[PC]ナビゲーション文字色',
			)
		);

		/**
		 * ナビゲーション背景色（SP）
		 */
		ys_customizer_add_setting_color(
			$wp_customize,
			array(
				'section' => 'ys_color_nav',
				'id'      => 'ys_color_nav_bg_sp',
				'default' => ys_customizer_get_default_color( 'ys_color_nav_bg_sp' ),
				'label'   => '[SP]ナビゲーション背景色',
			)
		);
		/**
		 * ナビゲーション文字色（SP）
		 */
		ys_customizer_add_setting_color(
			$wp_customize,
			array(
				'section' => 'ys_color_nav',
				'id'      => 'ys_color_nav_font_sp',
				'default' => ys_customizer_get_default_color( 'ys_color_nav_font_sp' ),
				'label'   => '[SP]ナビゲーション文字色',
			)
		);
}
/**
 * フッター
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_add_footer_color( $wp_customize ) {
		/**
		 * フッター色
		 */
		$wp_customize->add_section(
			'ys_color_footer',
			array(
				'title'    => 'フッター',
				'panel'    => 'ys_customizer_panel_color',
				'priority' => 0,
			)
		);

		/**
		 * フッター背景色
		 */
		ys_customizer_add_setting_color(
			$wp_customize,
			array(
				'section' => 'ys_color_footer',
				'id'      => 'ys_color_footer_bg',
				'default' => ys_customizer_get_default_color( 'ys_color_footer_bg' ),
				'label'   => 'フッター背景色',
			)
		);

		/**
		 * フッターSNSアイコン背景色タイプ
		 */
		ys_customizer_add_setting_radio(
			$wp_customize,
			array(
				'section' => 'ys_color_footer',
				'id'      => 'ys_color_footer_sns_bg_type',
				'default' => 'light',
				'label'   => 'フッターSNSアイコン背景色',
				'choices' => array(
					'light' => 'ライト',
					'dark'  => 'ダーク',
				),
			)
		);

		/**
		 * フッターSNSアイコン背景色不透明度
		 */
		ys_customizer_add_setting_number(
			$wp_customize,
			array(
				'section'     => 'ys_color_footer',
				'id'          => 'ys_color_footer_sns_bg_opacity',
				'default'     => 30,
				'label'       => 'フッターSNSアイコン背景色の不透明度',
				'description' => '0~100の間で入力して下さい',
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 100,
					'size' => 20,
				),
			)
		);

		/**
		 * フッター文字色
		 */
		ys_customizer_add_setting_color(
			$wp_customize,
			array(
				'section' => 'ys_color_footer',
				'id'      => 'ys_color_footer_font',
				'default' => ys_customizer_get_default_color( 'ys_color_footer_font' ),
				'label'   => 'フッター文字色',
			)
		);
}

/**
 * テーマカスタマイザーでの色変更機能を無効にする
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_add_disable_ys_color( $wp_customize ) {
	/**
	 * セクション追加
	 */
	$wp_customize->add_section(
		'ys_customizer_section_disable_ys_color',
		array(
			'title'    => '色変更機能を無効にする(上級者向け)',
			'panel'    => 'ys_customizer_panel_color',
			'priority' => 0,
		)
	);
	/**
	 * テーマカスタマイザーでの色変更機能を無効にする
	 */
	ys_customizer_add_setting_checkbox(
		$wp_customize,
		array(
			'id'          => 'ys_desabled_color_customizeser',
			'label'       => 'テーマカスタマイザーでの色変更機能を無効にする',
			'default'     => 0,
			'description' => '※ご自身でCSSを調整する場合はこちらのチェックをいれてください。<br>カスタマイザーで指定している部分のCSSコードが出力されなくなります',
			'section'     => 'ys_customizer_section_disable_ys_color',
		)
	);
}