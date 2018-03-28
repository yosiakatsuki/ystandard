<?php
/**
 * 高速化設定
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 高速化設定の追加
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_performance_tuning( $wp_customize ) {
	/**
	 * パネルの追加
	 */
	$wp_customize->add_panel(
		'ys_customizer_panel_performance_tuning',
		array(
			'priority'        => 1120,
			'title'           => '[ys]サイト高速化設定',
			'description'     => 'サイト高速化を行うための設定',
			'active_callback' => array(),
		)
	);
	/**
	 * WordPress標準機能で読み込むCSS・JavaScriptの無効化
	 */
	ys_customizer_performance_tuning_add_disable_wp_scripts( $wp_customize );
	/**
	 * CSS読み込みの最適化
	 */
	ys_customizer_performance_tuning_add_optimize_load_css( $wp_customize );
	/**
	 * JavaScript読み込みの最適化
	 */
	ys_customizer_performance_tuning_add_optimize_load_js( $wp_customize );
}

/**
 * WordPress標準機能で読み込むCSS・JavaScriptの無効化
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_performance_tuning_add_disable_wp_scripts( $wp_customize ) {
	/**
	 * セクション追加
	 */
	$wp_customize->add_section(
		'ys_customizer_section_disable_wp_scripts',
		array(
			'title'           => 'WordPress標準機能で読み込むCSS・JavaScriptの無効化',
			'panel'           => 'ys_customizer_panel_performance_tuning',
			'priority'        => 1,
			'description'     => 'WordPress標準機能で読み込むCSS・JavaScriptの無効化設定',
			'active_callback' => array(),
		)
	);
	/**
	 * 絵文字を出力しない
	 */
	ys_customizer_add_setting_checkbox(
		$wp_customize,
		array(
			'id'      => 'ys_option_disable_wp_emoji',
			'label'   => '絵文字関連のスタイルシート・スクリプトを無効化する',
			'default' => 1,
			'section' => 'ys_customizer_section_disable_wp_scripts',
		)
	);
	/**
	 * 絵文字を出力しない
	 */
	ys_customizer_add_setting_checkbox(
		$wp_customize,
		array(
			'id'      => 'ys_option_disable_wp_oembed',
			'label'   => 'oembed関連のスタイルシート・スクリプトを無効化する',
			'default' => 1,
			'section' => 'ys_customizer_section_disable_wp_scripts',
		)
	);
}
/**
 * CSS読み込みの最適化
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_performance_tuning_add_optimize_load_css( $wp_customize ) {
	/**
	 * セクション追加
	 */
	$wp_customize->add_section(
		'ys_customizer_section_optimize_load_css',
		array(
			'title'           => 'CSS読み込み最適化設定（上級者向け）',
			'panel'           => 'ys_customizer_panel_performance_tuning',
			'priority'        => 1,
			'description'     => 'CSSの読み込み方式を最適化します。',
			'active_callback' => array(),
		)
	);
	/**
	 * CSSの読み込みを最適化する
	 */
	ys_customizer_add_setting_checkbox(
		$wp_customize,
		array(
			'id'          => 'ys_option_optimize_load_css',
			'label'       => 'CSSの読み込みを最適化する',
			'default'     => 0,
			'description' => 'この設定をONにすると、CSSが「ファーストビューに関わる部分」「ファーストビュー以外」の2つに別れます。※詳しい説明は別途用意します',
			'section'     => 'ys_customizer_section_optimize_load_css',
		)
	);
}
/**
 * JavaScript読み込みの最適化
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_performance_tuning_add_optimize_load_js( $wp_customize ) {
	/**
	 * セクション追加
	 */
	$wp_customize->add_section(
		'ys_customizer_section_optimize_load_js',
		array(
			'title'       => 'JavaScript読み込み最適化設定（上級者向け）',
			'panel'       => 'ys_customizer_panel_performance_tuning',
			'priority'    => 1,
			'description' => 'JavaScriptの読み込み方式を最適化します。',
		)
	);
	/**
	 * JavaScriptの読み込みを非同期化する
	 */
	ys_customizer_add_label(
		$wp_customize,
		array(
			'id'      => 'ys_optimize_load_js_label',
			'label'   => 'JavaScriptの読み込みを非同期化する',
			'section' => 'ys_customizer_section_optimize_load_js',
		)
	);
	/**
	 * JavaScriptの読み込みを非同期化する
	 */
	ys_customizer_add_setting_checkbox(
		$wp_customize,
		array(
			'id'          => 'ys_option_optimize_load_js',
			'label'       => 'JavaScriptの読み込みを非同期化する',
			'default'     => 0,
			'description' => 'この設定をONにすると、jQuery以外のJavaScriptの読み込みを非同期化します（scriptタグにasyncとdefer属性を追加します）',
			'section'     => 'ys_customizer_section_optimize_load_js',
		)
	);
	/**
	 * CDNにホストされているjQueryを読み込む
	 */
	ys_customizer_add_setting_url(
		$wp_customize,
		array(
			'id'          => 'ys_load_cdn_jquery_url',
			'label'       => 'CDN経由でjQueryを読み込む',
			'description' => '※WordPress標準のjQueryを読み込む場合は空白にしてください（デフォルト）<br>※ホストされているjQueryのURLを入力してください。',
			'default'     => '',
			'section'     => 'ys_customizer_section_optimize_load_js',
			'transport'   => 'postMessage',
		)
	);
	/**
	 * ラベル : jQueryを無効化する
	 */
	ys_customizer_add_label(
		$wp_customize,
		array(
			'id'      => 'ys_not_load_jquery_label',
			'label'   => 'jQueryを無効化する',
			'section' => 'ys_customizer_section_optimize_load_js',
		)
	);
	/**
	 *  JQueryを読み込まない
	 */
	ys_customizer_add_setting_checkbox(
		$wp_customize,
		array(
			'id'          => 'ys_not_load_jquery',
			'label'       => 'jQueryを無効化する',
			'description' => '※この設定を有効にするとサイト表示高速化が期待できますが、jQueryを使用している処理が動かなくなります。<br>※プラグインの動作に影響が出る恐れがありますのでご注意ください。<br>※yStandard内のJavaScriptではjQueryを使用する機能は使っていません',
			'default'     => 0,
			'section'     => 'ys_customizer_section_optimize_load_js',
			'transport'   => 'postMessage',
		)
	);
}