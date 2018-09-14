<?php
/**
 * WordPress標準のカスタマイザー項目のカスタマイズ
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * WordPress標準のカスタマイザー項目を変更
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_wp( $wp_customize ) {
	ys_customizer_delete_site_icon( $wp_customize );
	ys_customizer_add_partial_bloginfo( $wp_customize );
	ys_customizer_add_apple_touch_icon( $wp_customize );
	ys_customizer_add_logo( $wp_customize );
	ys_customizer_add_description( $wp_customize );
	ys_customizer_add_header_media( $wp_customize );
}

/**
 * WP標準の背景色と色を削除
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_delete_site_icon( $wp_customize ) {
	$wp_customize->remove_setting( 'background_color' );
	$wp_customize->remove_section( 'colors' );
}

/**
 * ブログ名などをカスタマイザーショートカット対応させる
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_add_partial_bloginfo( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'            => '.site-title a',
				'container_inclusive' => false,
				'render_callback'     => function () {
					bloginfo( 'name' );
				},
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'            => '.site-description',
				'container_inclusive' => false,
				'render_callback'     => function () {
					bloginfo( 'description' );
				},
			)
		);
	}
}

/**
 * ロゴ設定追加
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_add_logo( $wp_customize ) {
	$ys_customizer = new YS_Customizer( $wp_customize );
	$ys_customizer->add_checkbox(
		array(
			'id'          => 'ys_logo_hidden',
			'default'     => 0,
			'label'       => 'ロゴを非表示にする',
			'description' => 'サイトヘッダーにロゴ画像を表示しない場合はチェックをつけてください<br>（ロゴの指定がないと構造化データでエラーになるので、仮のロゴ画像でも良いので設定することを推奨します）',
			'section'     => 'title_tagline',
			'priority'    => 9,
		)
	);
}

/**
 * 概要表示・デスクリプション
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_add_description( $wp_customize ) {
	$ys_customizer = new YS_Customizer( $wp_customize );
	$ys_customizer->add_checkbox(
		array(
			'id'          => 'ys_wp_hidden_blogdescription',
			'default'     => 0,
			'label'       => 'キャッチフレーズを非表示にする',
			'description' => 'サイトタイトル・ロゴの下にキャッチフレーズを表示したくない場合はチェックを付けて下さい',
			'section'     => 'title_tagline',
			'priority'    => 20,
		)
	);
	$ys_customizer->add_plain_textarea(
		array(
			'id'          => 'ys_wp_site_description',
			'transport'   => 'postMessage',
			'label'       => 'TOPページのmeta description',
			'description' => '※HTMLタグ・改行は削除されます',
			'section'     => 'title_tagline',
			'priority'    => 21,
		)
	);
}


/**
 * Apple touch icon設定追加
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_add_apple_touch_icon( $wp_customize ) {

	// サイトアイコンの説明を変更.
	$wp_customize->get_control( 'site_icon' )->description = sprintf(
		'ファビコン用の画像を設定して下さい。縦横%spx以上である必要があります。',
		'<strong>512</strong>'
	);

	$wp_customize->add_setting(
		'ys_apple_touch_icon',
		array(
			'type'       => 'option',
			'capability' => 'manage_options',
			'transport'  => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Site_Icon_Control(
			$wp_customize,
			'ys_apple_touch_icon',
			array(
				'label'       => 'apple touch icon',
				'description' => sprintf(
					'apple touch icon用の画像を設定して下さい。縦横%spx以上である必要があります。',
					'<strong>512</strong>'
				),
				'section'     => 'title_tagline',
				'priority'    => 61,
				'height'      => 512,
				'width'       => 512,
			)
		)
	);
}

/**
 * ヘッダーメディア設定追加
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_add_header_media( $wp_customize ) {
	$ys_customizer = new YS_Customizer( $wp_customize );
	/**
	 * ヘッダーメディアショートコード
	 */
	$ys_customizer->add_text(
		array(
			'id'          => 'ys_wp_header_media_shortcode',
			'label'       => '[ys]ヘッダーメディア用ショートコード',
			'description' => 'ヘッダー画像をプラグイン等のショートコードで出力する場合、ショートコードを入力してください。',
			'section'     => 'header_image',
			'priority'    => 0,
		)
	);
	/**
	 * ヘッダーメディアに動画を使う場合、メニューを動画の上に重ねるか
	 */
	$ys_customizer->add_label(
		array(
			'id'       => 'ys_wp_header_media_full_label',
			'label'    => '[ys]ヘッダーメディアにサイトヘッダーを重ねる（PC）',
			'section'  => 'header_image',
			'priority' => 20,
		)
	);
	$ys_customizer->add_checkbox(
		array(
			'id'          => 'ys_wp_header_media_full',
			'default'     => 0,
			'label'       => '[ys]ヘッダーメディアにサイトヘッダーを重ねる（PC）',
			'description' => 'PC表示でヘッダーメディア（画像・動画）の上にサイトヘッダーを重ねる。',
			'section'     => 'header_image',
			'priority'    => 21,
		)
	);
	/**
	 * メニュー表示タイプ
	 */
	$ys_customizer->add_radio(
		array(
			'id'          => 'ys_wp_header_media_full_type',
			'default'     => 'dark',
			'label'       => '[ys]ヘッダーメディアにサイトヘッダーを重ねる（PC）：サイトヘッダー表示タイプ',
			'description' => 'PC表示でヘッダーメディア（画像・動画）の上にサイトヘッダーを重ねる場合のサイトヘッダー表示タイプ',
			'section'     => 'header_image',
			'priority'    => 22,
			'choices'     => array(
				'dark'  => 'ダーク',
				'light' => 'ライト',
			),
		)
	);
	/**
	 * メニュー不透明度
	 */
	$ys_customizer->add_number(
		array(
			'id'          => 'ys_wp_header_media_full_opacity',
			'default'     => 50,
			'label'       => '[ys]ヘッダーメディアにサイトヘッダーを重ねる（PC）：サイトヘッダー不透明度',
			'description' => 'サイトヘッダーの不透明度を設定します。0~100の間で入力して下さい。（数値が小さいほど透明になります。）',
			'section'     => 'header_image',
			'priority'    => 23,
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 100,
				'size' => 20,
			),
		)
	);
	/**
	 * カスタムヘッダーの全ページ表示
	 */
	$ys_customizer->add_label(
		array(
			'id'       => 'ys_wp_header_media_all_page_label',
			'label'    => '[ys]ヘッダーメディアを全ページ表示する',
			'section'  => 'header_image',
			'priority' => 24,
		)
	);
	$ys_customizer->add_checkbox(
		array(
			'id'          => 'ys_wp_header_media_all_page',
			'default'     => 0,
			'label'       => '[ys]ヘッダーメディアを全ページ表示する',
			'description' => 'TOPページ以外の全ページでヘッダーメディアを表示する。',
			'section'     => 'header_image',
			'priority'    => 24,
		)
	);
}