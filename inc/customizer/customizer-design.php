<?php
/**
 * デザイン設定
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 共通デザイン設定
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_design( $wp_customize ) {
	/**
	 * パネルの追加
	 */
	$wp_customize->add_panel(
		'ys_customizer_panel_design',
		array(
			'title'           => '[ys]デザイン設定',
			'priority'        => 1010,
			'description'     => 'サイト共通部分のデザイン設定',
			'active_callback' => array(),
		)
	);
	/**
	 * ヘッダー設定
	 */
	ys_customizer_design_add_header( $wp_customize );
	/**
	 * モバイルページ設定
	 */
	ys_customizer_design_add_mobile( $wp_customize );
	/**
	 * ワンカラムテンプレート設定
	 */
	ys_customizer_design_add_one_column_template( $wp_customize );
	/**
	 * ブロックエディター対応
	 */
	ys_customizer_design_add_gutenberg_css( $wp_customize );
	/**
	 * アイコンフォント設定
	 */
	ys_customizer_design_add_icon_font( $wp_customize );
}

/**
 * ヘッダー設定
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_design_add_header( $wp_customize ) {
	$ys_customizer = new YS_Customizer( $wp_customize );
	/**
	 * セクション追加
	 */
	$ys_customizer->add_section(
		array(
			'section'     => 'ys_customizer_section_header_design',
			'title'       => 'ヘッダー設定',
			'priority'    => 1,
			'description' => 'ヘッダー部分のデザイン設定',
			'panel'       => 'ys_customizer_panel_design',
		)
	);
	/**
	 * ヘッダータイプ
	 */
	$assets_url = ys_get_template_customizer_assets_img_dir_uri();
	$row1       = $assets_url . '/design/header/1row.png';
	$center     = $assets_url . '/design/header/center.png';
	$row2       = $assets_url . '/design/header/2row.png';
	$img        = '<img src="%s" alt="" width="100" height="100" />';
	$ys_customizer->add_image_label_radio(
		array(
			'id'          => 'ys_design_header_type',
			'default'     => '1row',
			'label'       => 'ヘッダータイプ',
			'description' => 'ヘッダーの表示タイプ',
			'section'     => 'ys_customizer_section_header_design',
			'choices'     => array(
				'1row'   => sprintf( $img, $row1 ),
				'center' => sprintf( $img, $center ),
				'2row'   => sprintf( $img, $row2 ),
			),
		)
	);
}


/**
 * モバイルページ設定
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_design_add_mobile( $wp_customize ) {
	$ys_customizer = new YS_Customizer( $wp_customize );
	/**
	 * セクション追加
	 */
	$ys_customizer->add_section(
		array(
			'section'     => 'ys_customizer_section_mobile_design',
			'title'       => 'モバイルページ設定',
			'priority'    => 1,
			'description' => 'モバイルページのデザイン設定',
			'panel'       => 'ys_customizer_panel_design',
		)
	);
	/**
	 * サイドバー出力
	 */
	$ys_customizer->add_checkbox(
		array(
			'id'          => 'ys_show_sidebar_mobile',
			'default'     => 0,
			'label'       => 'モバイル表示でサイドバーを非表示にする',
			'description' => 'モバイルページでサイドバー部分を表示しない場合にチェックを付けて下さい',
		)
	);
	/**
	 * スライドメニューに検索フォームを出力する
	 */
	$ys_customizer->add_checkbox(
		array(
			'id'          => 'ys_show_search_form_on_slide_menu',
			'default'     => 0,
			'label'       => 'スライドメニューに検索フォームを出力する(モバイル)',
			'description' => 'モバイルページでスライドメニューに検索フォームを出力する場合にチェックを付けて下さい',
		)
	);
}

/**
 * ワンカラムテンプレート設定
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_design_add_one_column_template( $wp_customize ) {
	$ys_customizer = new YS_Customizer( $wp_customize );
	/**
	 * セクション追加
	 */
	$ys_customizer->add_section(
		array(
			'section'     => 'ys_customizer_section_one_column_template',
			'title'       => 'ワンカラムテンプレート設定',
			'priority'    => 10,
			'description' => 'ワンカラムテンプレートの設定',
			'panel'       => 'ys_customizer_panel_design',
		)
	);
	/**
	 * ヘッダータイプ
	 */
	$assets_url = ys_get_template_customizer_assets_img_dir_uri();
	$row1       = $assets_url . '/design/one-col-template/full.png';
	$center     = $assets_url . '/design/one-col-template/normal.png';
	$img        = '<img src="%s" alt="" width="100" height="100" />';
	$ys_customizer->add_image_label_radio(
		array(
			'id'          => 'ys_design_one_col_thumbnail_type',
			'default'     => 'full',
			'label'       => 'アイキャッチ画像表示タイプ',
			'description' => 'アイキャッチ画像の表示タイプ',
			'choices'     => array(
				'full'   => sprintf( $img, $row1 ),
				'normal' => sprintf( $img, $center ),
			),
		)
	);
}

/**
 * ブロックエディターCSS設定
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_design_add_gutenberg_css( $wp_customize ) {
	$ys_customizer = new YS_Customizer( $wp_customize );
	/**
	 * セクション追加
	 */
	$ys_customizer->add_section(
		array(
			'section'     => 'ys_customizer_section_gutenberg_css',
			'title'       => 'ブロックエディターCSS設定',
			'priority'    => 10,
			'description' => 'WordPress 5.0から登場するブロックエディターのCSSに関する設定',
			'panel'       => 'ys_customizer_panel_design',
		)
	);
	/**
	 * ブロックエディター対応のCSSを読み込む
	 */
	$ys_customizer->add_checkbox(
		array(
			'id'          => 'ys_enqueue_gutenberg_css',
			'default'     => 1,
			'label'       => 'ブロックエディター対応のCSSを読み込む',
			'description' => 'WordPress 5.0から登場するブロックエディターに対応したCSSを読み込みます。<br><br><string>※yStandardのAMP機能を使っている場合、CSS容量オーバーのエラーが発生する恐れがありますので有効化の際はご注意下さい。</string><br><br>ブロックエディター対応のCSSを読み込む場合、yStandardテーマ本体のCSS容量が43KBとなり、上限値の50KBまで余裕がありません。子テーマなどで独自に追加したCSSの量によってはエラーになる恐れがあります。',
		)
	);
}


/**
 * アイコンフォント設定
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_design_add_icon_font( $wp_customize ) {
	$ys_customizer = new YS_Customizer( $wp_customize );
	/**
	 * セクション追加
	 */
	$ys_customizer->add_section(
		array(
			'section'     => 'ys_customizer_section_icon_fonts',
			'title'       => 'アイコンフォント設定',
			'priority'    => 10,
			'description' => 'アイコンフォントに関する設定',
			'panel'       => 'ys_customizer_panel_design',
		)
	);
	/**
	 * アイコンフォント読み込み方式
	 */
	$ys_customizer->add_radio(
		array(
			'id'          => 'ys_enqueue_icon_font_type',
			'default'     => 'js',
			'transport'   => 'postMessage',
			'label'       => 'アイコンフォント（Font Awesome）読み込み方式',
			'description' => 'Font Awesome読み込み方式を設定できます。',
			'choices'     => array(
				'js'   => 'JavaScript(推奨)',
				'css'  => 'CSS',
				'none' => '読み込まない(※表示が崩れる場合があります。プラグイン等でFont Awesomeを読み込む場合の設定)',
			),
		)
	);
}