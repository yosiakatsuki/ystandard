<?php
//------------------------------------------------------------------------------
//
//	テーマカスタマイザー
//
//------------------------------------------------------------------------------




//-----------------------------------------------
//	カスタマイザーサンプル
//-----------------------------------------------
if (!function_exists( 'ys_customizer_sample')) {
	function ys_customizer_sample($wp_customize) {

		// セクションの追加
		$wp_customize->
			add_section(
				'ys_section_name',
				array(
					'title' => "セクション名",
					'priority' => 21,	//優先度
					'description' => "セクションの説明"
				)
			);

		// 設定の追加
		$wp_customize->
			add_setting(
				'ys_setting_name',
				array(
					'default' => 0,	//初期値
					'type' => 'option'	//タイプ
				)
			);

		// コントロールの追加
		$wp_customize->
			add_control(
				'ys_setting_name',
				array(
					'section' => 'ys_section_name',
					'settings' => 'ys_setting_name',
					'label' => 'コントロールに表示するラベル',
					'description'=>'コントロールの説明',
					'type' => 'text',
					'priority' => 1	//順番
				)
			);

	}//function ys_customizer_sample($wp_customize)
}
//add_action('customize_register', 'ys_customizer_sample');
?>