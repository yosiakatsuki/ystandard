<?php
/**
 * 投稿ページ設定
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 投稿ページの設定追加
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 *
 * @return void
 */
function ys_customizer_post( $wp_customize ) {
	/**
	 * パネルの追加
	 */
	/**
	 * セクション追加
	 */
	$wp_customize->add_section(
		'ys_customizer_section_post',
		array(
			'title'           => '投稿ページ設定',
			'priority'        => 1,
			'panel'           => 'ys_customizer_panel_design',
			'active_callback' => 'ys_customizer_active_callback_post',
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
}

/**
 * 投稿ページ設定
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_post_add_settings( $wp_customize ) {
	$ys_customizer = new YS_Customizer( $wp_customize );
	/**
	 * 表示カラム数
	 */
	$assets_url = ys_get_template_customizer_assets_img_dir_uri();
	$col1       = $assets_url . '/design/column-type/col-1.png';
	$col2       = $assets_url . '/design/column-type/col-2.png';
	$img        = '<img src="%s" alt="" width="100" height="100" />';
	$ys_customizer->add_image_label_radio(
		array(
			'id'          => 'ys_post_layout',
			'default'     => '2col',
			'label'       => 'レイアウト',
			'description' => '投稿ページの表示レイアウト<br>※デフォルトテンプレートの表示レイアウト',
			'section'     => 'ys_customizer_section_post',
			'choices'     => array(
				'2col' => sprintf( $img, $col2 ),
				'1col' => sprintf( $img, $col1 ),
			),
		)
	);
	/**
	 * 記事上部表示設定
	 */
	$ys_customizer->add_label(
		array(
			'id'      => 'ys_above_post_label',
			'label'   => '記事上部設定',
			'section' => 'ys_customizer_section_post',
		)
	);
	$ys_customizer->add_checkbox(
		array(
			'id'          => 'ys_show_post_thumbnail',
			'default'     => 1,
			'label'       => 'アイキャッチ画像を表示する',
			'description' => '※投稿内の先頭にアイキャッチ画像を配置している場合、こちらの設定を無効にすることにより画像が2枚連続で表示されないようにします。（他ブログサービスからの引っ越してきた場合に役立つかもしれません）',
			'section'     => 'ys_customizer_section_post',
		)
	);
	/**
	 * 投稿日時を表示する
	 */
	$ys_customizer->add_checkbox(
		array(
			'id'      => 'ys_show_post_publish_date',
			'default' => 1,
			'label'   => '投稿日・更新日を表示する',
			'section' => 'ys_customizer_section_post',
		)
	);
	/**
	 * 記事下表示設定
	 */
	$ys_customizer->add_label(
		array(
			'id'          => 'ys_below_post_label',
			'label'       => '記事下表示設定',
			'description' => '※シェアボタンの表示・非表示は[SNS設定]→[SNSシェアボタン設定]から行って下さい',
			'section'     => 'ys_customizer_section_post',
		)
	);
	/**
	 * カテゴリー・タグ情報を表示する
	 */
	$ys_customizer->add_checkbox(
		array(
			'id'      => 'ys_show_post_category',
			'default' => 1,
			'label'   => 'カテゴリー・タグ情報を表示する',
			'section' => 'ys_customizer_section_post',
		)
	);
	/**
	 * ブログフォローボックスを表示する
	 */
	$ys_customizer->add_checkbox(
		array(
			'id'      => 'ys_show_post_follow_box',
			'default' => 1,
			'label'   => 'ブログフォローボックスを表示する',
			'section' => 'ys_customizer_section_post',
		)
	);
	/**
	 * 著者情報を表示する
	 */
	$ys_customizer->add_checkbox(
		array(
			'id'      => 'ys_show_post_author',
			'default' => 1,
			'label'   => '著者情報を表示する',
			'section' => 'ys_customizer_section_post',
		)
	);
	/**
	 * 記事下に関連記事を出力する
	 */
	$ys_customizer->add_checkbox(
		array(
			'id'      => 'ys_show_post_related',
			'default' => 1,
			'label'   => '記事下に関連記事を表示する',
			'section' => 'ys_customizer_section_post',
		)
	);
	/**
	 * 次の記事・前の記事のリンクを出力しない
	 */
	$ys_customizer->add_checkbox(
		array(
			'id'      => 'ys_show_post_paging',
			'default' => 1,
			'label'   => '次の記事・前の記事のリンクを表示する',
			'section' => 'ys_customizer_section_post',
		)
	);
	/**
	 * 記事前後のウィジェット表示設定
	 */
	$ys_customizer->add_label(
		array(
			'id'          => 'ys_post_content_widget_label',
			'label'       => '記事前後のウィジェット表示設定',
			'description' => '記事前後に表示するウィジェットの設定',
			'section'     => 'ys_customizer_section_post',
		)
	);
	/**
	 * 記事上ウィジェットを出力する
	 */
	$ys_customizer->add_checkbox(
		array(
			'id'      => 'ys_show_post_before_content_widget',
			'default' => 0,
			'label'   => '記事上ウィジェットを出力する',
			'section' => 'ys_customizer_section_post',
		)
	);
	/**
	 * 記事上ウィジェットの優先順位
	 */
	$ys_customizer->add_number(
		array(
			'id'          => 'ys_post_before_content_widget_priority',
			'default'     => 10,
			'label'       => '記事上ウィジェットの優先順位',
			'description' => '記事上ウィジェットの優先順位。1~99を目安に設定して下さい。（初期値10）数字が小さいほどコンテンツに近い位置にウィジェットが表示されます。（他プラグインで出力している内容との表示順調整用）',
			'section'     => 'ys_customizer_section_post',
		)
	);
	/**
	 * 記事下ウィジェットを出力する
	 */
	$ys_customizer->add_checkbox(
		array(
			'id'      => 'ys_show_post_after_content_widget',
			'default' => 0,
			'label'   => '記事下ウィジェットを出力する',
			'section' => 'ys_customizer_section_post',
		)
	);
	/**
	 * 記事下ウィジェットの優先順位
	 */
	$ys_customizer->add_number(
		array(
			'id'          => 'ys_post_after_content_widget_priority',
			'default'     => 10,
			'label'       => '記事下ウィジェットの優先順位',
			'description' => '記事下ウィジェットの優先順位。1~99を目安に設定して下さい。（初期値10）数字が小さいほどコンテンツに近い位置にウィジェットが表示されます。（他プラグインで出力している内容との表示順調整用）',
			'section'     => 'ys_customizer_section_post',
		)
	);
}
