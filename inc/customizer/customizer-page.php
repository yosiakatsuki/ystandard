<?php
/**
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */
/**
 *	投稿ページ設定
 */
function ys_customizer_page( $wp_customize ){
	/**
	 * パネルの追加
	 */
	// $wp_customize->add_panel(
	// 									'ys_customizer_panel_page',
	// 									array(
	// 										'priority'       => 1030,
	// 										'title'          => '[ys]固定ページ設定',
	// 										'active_callback' => 'ys_customizer_active_callback_page'
	// 									)
	// 								);
	/**
	 * セクション追加
	 * 	active_callbackが効かないのでデザイン設定の中に入れる
	 */
	$wp_customize->add_section(
		'ys_customizer_section_page',
		array(
			'title'           => '固定ページ設定',
			'panel'           => 'ys_customizer_panel_design',
			'priority'        => 1,
			'active_callback' => 'ys_customizer_active_callback_page',
		)
	);
	/**
	 * 固定ページ設定
	 */
	ys_customizer_page_add_settings( $wp_customize );
}
/**
 * 固定ページ設定の表示条件
 */
function ys_customizer_active_callback_page() {
	return true;
	//TODO:active_callbackが効かない
	return is_page();
}

/**
 * 投稿ページ設定
 */
function ys_customizer_page_add_settings( $wp_customize ) {

	/**
	 * アイキャッチ画像を表示する
	 */
	ys_customizer_add_setting_checkbox(
		$wp_customize,
		array(
			'id'          => 'ys_show_page_thumbnail',
			'label'       => 'アイキャッチ画像を表示する',
			'description' => '※投稿内の先頭にアイキャッチ画像を配置している場合、こちらの設定を無効にすることにより画像が2枚連続で表示されないようにします。（他ブログサービスからの引っ越してきた場合に役立つかもしれません）',
			'default'     => 1,
			'section'     => 'ys_customizer_section_page',
		)
	);
	/**
	 * 「この記事を書いた人」ボックスを表示する
	 */
	ys_customizer_add_setting_checkbox(
		$wp_customize,
		array(
			'id'      => 'ys_show_page_author',
			'label'   => '「この記事を書いた人」ボックスを表示する',
			'default' => 1,
			'section' => 'ys_customizer_section_page',
		)
	);

}