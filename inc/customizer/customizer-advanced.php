<?php
/**
 *
 *	上級者向け設定
 *
 */
function ys_customizer_advanced( $wp_customize ){
	/**
	 * パネルの追加
	 */
	$wp_customize->add_panel(
										'ys_customizer_panel_advanced',
										array(
											'priority'       => 1200,
											'title'          => '[ys]上級者向け設定'
										)
									);
	/**
	 * テーマカスタマイザーでの色変更機能を無効にする
	 */
	ys_customizer_advanced_add_disable_ys_color( $wp_customize );
	/**
	 * SNS用javascriptの読み込み
	 */
	ys_customizer_advanced_add_load_script( $wp_customize );
	/**
	 * jQuery設定
	 */
	ys_customizer_advanced_add_jquery( $wp_customize );
}

/**
 *	テーマカスタマイザーでの色変更機能を無効にする
 */
function ys_customizer_advanced_add_disable_ys_color( $wp_customize ) {
	/**
	 * セクション追加
	 */
	$wp_customize->add_section(
										'ys_customizer_section_disable_ys_color',
										array(
											'title'    => 'テーマカスタマイザーでの色変更機能を無効にする',
											'panel'    => 'ys_customizer_panel_advanced',
											'priority' => 1,
										)
									);
	/**
	 * テーマカスタマイザーでの色変更機能を無効にする
	 */
	ys_customizer_add_setting_checkbox(
		$wp_customize,
		array(
			'id'        => 'ys_desabled_color_customizeser',
			'label'       => 'テーマカスタマイザーでの色変更機能を無効にする',
			'default'   => 0,
			'description' => '※ご自身でCSSを調整する場合はこちらのチェックをいれてください。余分なCSSコードが出力されなくなります',
			'section'   => 'ys_customizer_section_disable_ys_color',
			'transport' => 'postMessage',
		)
	);
}

/**
 *	SNS用javascriptの読み込み
 */
function ys_customizer_advanced_add_load_script( $wp_customize ) {
	/**
	 * セクション追加
	 */
	$wp_customize->add_section(
										'ys_customizer_section_load_script',
										array(
											'title'    => 'SNS用javascriptの読み込み',
											'panel'    => 'ys_customizer_panel_advanced',
											'priority' => 1,
										)
									);
	/**
	 * Twitter用javascriptを読み込む
	 */
	ys_customizer_add_setting_checkbox(
		$wp_customize,
		array(
			'id'        => 'ys_load_script_twitter',
			'label'       => 'Twitter用javascriptを読み込む',
			'description' => '※Twitterのフォローボタンなどをサイト内で使用する場合、こちらにチェックを入れてください。<br>※Twitter用のjavascriptを&lt;/body&gt;直前で読み込みます',
			'default'   => 0,
			'section'   => 'ys_customizer_section_load_script',
			'transport' => 'postMessage',
		)
	);
	/**
	 *  Facebook用javascriptを読み込む
	 */
	ys_customizer_add_setting_checkbox(
		$wp_customize,
		array(
			'id'        => 'ys_load_script_facebook',
			'label'       => 'Facebook用javascriptを読み込む',
			'description' => '※FacebookのいいねボタンやPagePluginなどをサイト内で使用する場合、こちらにチェックを入れてください。<br>※Facebook用のjavascriptを&lt;/body&gt;直前で読み込みます',
			'default'   => 0,
			'section'   => 'ys_customizer_section_load_script',
			'transport' => 'postMessage',
		)
	);
}

/**
 *	jQuery設定
 */
function ys_customizer_advanced_add_jquery( $wp_customize ) {
	/**
	 * セクション追加
	 */
	$wp_customize->add_section(
										'ys_customizer_section_jquery',
										array(
											'title'    => 'jQuery設定',
											'panel'    => 'ys_customizer_panel_advanced',
											'priority' => 1,
										)
									);
	/**
	 * CDNにホストされているjQueryを読み込む
	 */
	ys_customizer_add_setting_url(
		$wp_customize,
		array(
			'id'        => 'ys_load_cdn_jquery_url',
			'label'       => 'Twitter用javascriptを読み込む',
			'description' => '※WordPress標準のjQueryを読み込む場合は空白にしてください（デフォルト）<br>※ホストされているjQueryのURLを入力してください。',
			'default'   => '',
			'section'   => 'ys_customizer_section_jquery',
			'transport' => 'postMessage',
		)
	);
	/**
	 *  jQueryを読み込まない
	 */
	ys_customizer_add_setting_checkbox(
		$wp_customize,
		array(
			'id'        => 'ys_not_load_jquery',
			'label'       => 'jQueryを読み込まない',
			'description' => '※この設定を有効にするとサイト表示高速化が期待できますが、jQueryを使用している処理が動かなくなります。<br>※プラグインの動作に影響が出る恐れがありますのでご注意ください。<br>※テーマ標準のjavascriptではjQueryを使用する機能は使っていません',
			'default'   => 0,
			'section'   => 'ys_customizer_section_jquery',
			'transport' => 'postMessage',
		)
	);
}