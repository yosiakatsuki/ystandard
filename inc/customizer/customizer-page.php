<?php
/**
 *
 *	投稿ページ設定
 *
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
	 */
	$wp_customize->add_section(
										'ys_customizer_section_page',
										array(
											'title'    => '[ys]固定ページ設定',
											'panel'    => '',
											'priority' => 1030,
											'active_callback' => 'ys_customizer_active_callback_page'
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
	return is_page();
}

/**
 *	投稿ページ設定
 */
function ys_customizer_page_add_settings( $wp_customize ) {

	/**
	 * アイキャッチ画像を表示しない
	 */
	ys_customizer_add_setting_checkbox(
		$wp_customize,
		array(
			'id'        => 'ys_hide_page_thumbnail',
			'label'       => 'アイキャッチ画像を表示しない',
			'description' => '※投稿内の先頭にアイキャッチ画像を配置している場合、こちらの設定を有効にすることにより画像が2枚連続で表示されないようにします。（他ブログサービスからの引っ越してきた場合に役立つかもしれません）',
			'default'   => 0,
			'section'   => 'ys_customizer_section_page'
		)
	);
	/**
	 * 「この記事を書いた人」ボックスを表示しない
	 */
	ys_customizer_add_setting_checkbox(
		$wp_customize,
		array(
			'id'        => 'ys_hide_page_author',
			'label'       => '「この記事を書いた人」ボックスを表示しない',
			'default'   => 0,
			'section'   => 'ys_customizer_section_page'
		)
	);

}