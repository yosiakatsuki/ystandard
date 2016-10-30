<?php
//------------------------------------------------------------------------------
//
//	テーマカスタマイザー
//
//------------------------------------------------------------------------------




//-----------------------------------------------
//	カスタマイザーコア機能の書き換え
//-----------------------------------------------
if (!function_exists( 'ys_customizer')) {
	function ys_customizer($wp_customize) {
		// ------------------------
		// 設定の削除
		// ------------------------

		// サイトアイコン削除
		ys_customizer_delete_site_icon($wp_customize);

		// ------------------------
		// 設定の追加
		// ------------------------

		// apple touch icon
		ys_customizer_add_apple_touch_icon($wp_customize);

	}
}
add_action('customize_register', 'ys_customizer');




//-----------------------------------------------
//	サイトアイコンの削除
//-----------------------------------------------
if (!function_exists( 'ys_customizer_delete_site_icon')) {
	function ys_customizer_delete_site_icon($wp_customize) {
		// サイトアイコン削除
		//$wp_customize->remove_setting('site_icon');
	}
}




//-----------------------------------------------
//	apple touch icon設定追加
//-----------------------------------------------
if (!function_exists( 'ys_customizer_add_apple_touch_icon')) {
	function ys_customizer_add_apple_touch_icon($wp_customize) {

		// サイトアイコンの説明を変更
		$wp_customize->get_control('site_icon')->description = sprintf(
			'ファビコン用の画像を設定して下さい。縦横%spx以上である必要があります。',
			'<strong>512</strong>'
		);

		$wp_customize->add_setting( 'ys_apple_touch_icon', array(
			'type'       => 'option',
			'capability' => 'manage_options',
			'transport'  => 'postMessage', // Previewed with JS in the Customizer controls window.
		) );

		$wp_customize->add_control( new WP_Customize_Site_Icon_Control( $wp_customize, 'ys_apple_touch_icon', array(
			'label'       => 'apple touch icon',
			'description' => sprintf(
				'apple touch icon用の画像を設定して下さい。縦横%spx以上である必要があります。',
				'<strong>512</strong>'
			),
			'section'     => 'title_tagline',
			'priority'    => 61,
			'height'      => 512,
			'width'       => 512,
		) ) );
	}
}


?>