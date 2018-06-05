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
			'title'           => '[ys]サイト高速化設定',
			'priority'        => 1120,
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
	$ys_customizer = new YS_Customizer( $wp_customize );
	/**
	 * セクション追加
	 */
	$ys_customizer->add_section( array(
		'section'     => 'ys_cusomizer_section_disable_wp_scripts',
		'title'       => 'WordPress標準機能で読み込むCSS・JavaScriptの無効化',
		'priority'    => 1,
		'description' => 'WordPress標準機能で読み込むCSS・JavaScriptの無効化設定',
		'panel'       => 'ys_customizer_panel_performance_tuning',
	) );
	/**
	 * 絵文字を出力しない
	 */
	$ys_customizer->add_checkbox(
		array(
			'id'      => 'ys_option_disable_wp_emoji',
			'default' => 1,
			'label'   => '絵文字関連のスタイルシート・スクリプトを無効化する',
		)
	);
	/**
	 * 絵文字を出力しない
	 */
	$ys_customizer->add_checkbox(
		array(
			'id'      => 'ys_option_disable_wp_oembed',
			'default' => 1,
			'label'   => 'oembed関連のスタイルシート・スクリプトを無効化する',
		)
	);
}
/**
 * CSS読み込みの最適化
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_performance_tuning_add_optimize_load_css( $wp_customize ) {
	$ys_customizer = new YS_Customizer( $wp_customize );
	/**
	 * セクション追加
	 */
	$ys_customizer->add_section( array(
		'section'     => 'ys_customizer_section_optimize_load_css',
		'title'       => 'CSS読み込み最適化設定（上級者向け）',
		'priority'    => 1,
		'description' => 'CSSの読み込み方式を最適化します。',
		'panel'       => 'ys_customizer_panel_performance_tuning',
	) );
	/**
	 * CSSの読み込みを最適化する
	 */
	$ys_customizer->add_checkbox(
		array(
			'id'          => 'ys_option_optimize_load_css',
			'default'     => 0,
			'label'       => 'CSSの読み込みを最適化する',
			'description' => 'この設定をONにすると、CSSが「ファーストビューに関わる部分」「ファーストビュー以外」の2つに別れます。※詳しい説明は別途用意します',
		)
	);
}
/**
 * JavaScript読み込みの最適化
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_performance_tuning_add_optimize_load_js( $wp_customize ) {
	$ys_customizer = new YS_Customizer( $wp_customize );
	/**
	 * セクション追加
	 */
	$ys_customizer->add_section( array(
		'section'     => 'ys_customizer_section_optimize_load_js',
		'title'       => 'JavaScript読み込み最適化設定（上級者向け）',
		'priority'    => 1,
		'description' => 'JavaScriptの読み込み方式を最適化します。',
		'panel'       => 'ys_customizer_panel_performance_tuning',
	) );
	/**
	 * JavaScriptの読み込みを非同期化する
	 */
	$ys_customizer->add_label( array(
		'id'    => 'ys_optimize_load_js_label',
		'label' => 'JavaScriptの読み込みを非同期化する',
	) );
	$ys_customizer->add_checkbox(
		array(
			'id'          => 'ys_option_optimize_load_js',
			'default'     => 0,
			'label'       => 'JavaScriptの読み込みを非同期化する',
			'description' => 'この設定をONにすると、jQuery以外のJavaScriptの読み込みを非同期化します（scriptタグにasyncとdefer属性を追加します）',
		)
	);
	/**
	 * [jQueryをフッターで読み込む]
	 */
	$ys_customizer->add_label( array(
		'id'    => 'ys_load_jquery_in_footer_label',
		'label' => 'jQueryをフッターで読み込む',
	) );
	$ys_customizer->add_checkbox(
		array(
			'id'          => 'ys_load_jquery_in_footer',
			'default'     => 0,
			'label'       => 'jQueryをフッターで読み込む',
			'description' => '※この設定を有効にすると利用しているプラグインの動作が不安定になる恐れがあります。<br>プラグインの機能が正常に動作しなくなる場合は設定を無効化してください。',
		)
	);
	/**
	 * CDNにホストされているjQueryを読み込む
	 */
	$ys_customizer->add_url(
		array(
			'id'          => 'ys_load_cdn_jquery_url',
			'default'     => '',
			'transport'   => 'postMessage',
			'label'       => 'CDN経由でjQueryを読み込む',
			'description' => '※WordPress標準のjQueryを読み込む場合は空白にしてください（デフォルト）<br>※ホストされているjQueryのURLを入力してください。',
		)
	);
	/**
	 * [jQueryを無効化する]
	 */
	$ys_customizer->add_label( array(
		'id'    => 'ys_not_load_jquery_label',
		'label' => 'jQueryを無効化する',
	) );
	$ys_customizer->add_checkbox(
		array(
			'id'          => 'ys_not_load_jquery',
			'default'     => 0,
			'transport'   => 'postMessage',
			'label'       => 'jQueryを無効化する',
			'description' => '※この設定を有効にするとサイト表示高速化が期待できますが、jQueryを使用している処理が動かなくなります。<br>※プラグインの動作に影響が出る恐れがありますのでご注意ください。<br>※yStandard内のJavaScriptではjQueryを使用する機能は使っていません',
		)
	);
}
