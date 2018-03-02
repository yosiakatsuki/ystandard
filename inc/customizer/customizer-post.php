<?php
/**
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */
/**
 *	投稿ページ設定
 */
function ys_customizer_post( $wp_customize ){
	/**
	 * パネルの追加
	 */
	// $wp_customize->add_panel(
	// 									'ys_customizer_panel_post',
	// 									array(
	// 										'priority'       => 1020,
	// 										'title'          => '[ys]投稿ページ設定',
	// 										'active_callback' => 'ys_customizer_active_callback_post'
	// 									)
	// 								);
	/**
	 * セクション追加
	 */
	$wp_customize->add_section(
										'ys_customizer_section_post',
										array(
											'title'    => '投稿ページ設定',
											'panel'    => 'ys_customizer_panel_design',
											'priority' => 1,
											'active_callback' => 'ys_customizer_active_callback_post'
										)
									);
	/**
	 * 投稿ページ設定
	 * active_callbackが効かないのでデザイン設定の中に入れる
	 */
	ys_customizer_post_add_settings( $wp_customize );
}
/**
 * 投稿ページ設定の表示条件
 */
function ys_customizer_active_callback_post() {
	return true;
	//TODO:active_callbackが効かない
	return is_single();
}

/**
 *	投稿ページ設定
 */
function ys_customizer_post_add_settings( $wp_customize ) {

	/**
	 * アイキャッチ画像を表示しない
	 */
	ys_customizer_add_setting_checkbox(
		$wp_customize,
		array(
			'id'        => 'ys_hide_post_thumbnail',
			'label'       => 'アイキャッチ画像を表示しない',
			'description' => '※投稿内の先頭にアイキャッチ画像を配置している場合、こちらの設定を有効にすることにより画像が2枚連続で表示されないようにします。（他ブログサービスからの引っ越してきた場合に役立つかもしれません）',
			'default'   => 0,
			'section'   => 'ys_customizer_section_post'
		)
	);
	/**
	 * 記事下に関連記事を出力する
	 */
	ys_customizer_add_setting_checkbox(
		$wp_customize,
		array(
			'id'        => 'ys_show_post_related',
			'label'       => '記事下に関連記事を出力する',
			'default'   => 1,
			'section'   => 'ys_customizer_section_post'
		)
	);
	/**
	 * 次の記事・前の記事のリンクを出力しない
	 */
	ys_customizer_add_setting_checkbox(
		$wp_customize,
		array(
			'id'        => 'ys_hide_post_paging',
			'label'       => '次の記事・前の記事のリンクを出力しない',
			'default'   => 1,
			'section'   => 'ys_customizer_section_post'
		)
	);
	/**
	 * 次の記事・前の記事のリンクを出力しない
	 */
	ys_customizer_add_setting_checkbox(
		$wp_customize,
		array(
			'id'        => 'ys_hide_post_author',
			'label'       => '「この記事を書いた人」ボックスを表示しない',
			'default'   => 0,
			'section'   => 'ys_customizer_section_post'
		)
	);

}