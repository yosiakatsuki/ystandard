<?php
/**
 * SNS設定
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * SNS設定
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_sns( $wp_customize ) {
	/**
	 * パネルの追加
	 */
	$wp_customize->add_panel(
		'ys_customizer_panel_sns',
		array(
			'title'    => '[ys]SNS設定',
			'priority' => 1100,
		)
	);
	/**
	 * OGP設定
	 */
	ys_customizer_sns_add_ogp( $wp_customize );
	/**
	 * Twitter Cards
	 */
	ys_customizer_sns_add_twitter_cards( $wp_customize );
	/**
	 * SNS Share Button
	 */
	ys_customizer_sns_add_sns_share_button( $wp_customize );
	/**
	 * Twitter Share
	 */
	ys_customizer_sns_add_twitter_share( $wp_customize );
	/**
	 * フォローボタン設定
	 */
	ys_customizer_sns_add_sns_follow( $wp_customize );
	/**
	 * フッターSNSフォローボタン設定
	 */
	ys_customizer_sns_add_footer_sns_follow( $wp_customize );
	/**
	 * SNS用JavaScriptの読み込み
	 */
	ys_customizer_advanced_add_load_script( $wp_customize );
}

/**
 * OGP設定
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_sns_add_ogp( $wp_customize ) {
	$ys_customizer = new YS_Customizer( $wp_customize );
	/**
	 * セクション追加
	 */
	$ys_customizer->add_section(
		array(
			'section' => 'ys_customizer_section_ogp',
			'title'   => 'OGP設定',
			'panel'   => 'ys_customizer_panel_sns',
		)
	);

	/**
	 * OGP metaタグを出力する
	 */
	$ys_customizer->add_label(
		array(
			'id'    => 'ys_ogp_enable_label',
			'label' => 'OGP metaタグ',
		)
	);
	$ys_customizer->add_checkbox(
		array(
			'id'        => 'ys_ogp_enable',
			'default'   => 1,
			'transport' => 'postMessage',
			'label'     => 'OGPのmetaタグを出力する',
		)
	);
	/**
	 * Facebook app_id
	 */
	$ys_customizer->add_text(
		array(
			'id'          => 'ys_ogp_fb_app_id',
			'default'     => '',
			'transport'   => 'postMessage',
			'label'       => 'Facebook app_id',
			'input_attrs' => array(
				'placeholder' => '000000000000000',
				'maxlength'   => 15,
			),
		)
	);
	/**
	 * Facebook app_id
	 */
	$ys_customizer->add_text(
		array(
			'id'          => 'ys_ogp_fb_admins',
			'default'     => '',
			'transport'   => 'postMessage',
			'label'       => 'Facebook admins',
			'input_attrs' => array(
				'placeholder' => '000000000000000',
				'maxlength'   => 15,
			),
		)
	);
	/**
	 * OGPデフォルト画像
	 */
	$ys_customizer->add_image(
		array(
			'id'          => 'ys_ogp_default_image',
			'default'     => '',
			'transport'   => 'postMessage',
			'label'       => 'OGPデフォルト画像',
			'description' => 'トップページ・アーカイブページ・投稿にアイキャッチ画像が無かった場合のデフォルト画像を指定して下さい。<br>おすすめサイズ：横1200px - 縦630px ',
		)
	);
}

/**
 * Twitter Cards
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_sns_add_twitter_cards( $wp_customize ) {
	$ys_customizer = new YS_Customizer( $wp_customize );
	/**
	 * セクション追加
	 */
	$ys_customizer->add_section(
		array(
			'section' => 'ys_customizer_section_twitter_cards',
			'title'   => 'Twitterカード設定',
			'panel'   => 'ys_customizer_panel_sns',
		)
	);
	/**
	 * Twitterカードのmetaタグを出力する
	 */
	$ys_customizer->add_label(
		array(
			'id'    => 'ys_twittercard_enable_label',
			'label' => 'Twitterカードmetaタグ',
		)
	);
	$ys_customizer->add_checkbox(
		array(
			'id'        => 'ys_twittercard_enable',
			'default'   => 1,
			'transport' => 'postMessage',
			'label'     => 'Twitterカードのmetaタグを出力する',
		)
	);
	/**
	 * ユーザー名
	 */
	$ys_customizer->add_text(
		array(
			'id'          => 'ys_twittercard_user',
			'default'     => '',
			'transport'   => 'postMessage',
			'label'       => 'Twitterカードのユーザー名',
			'description' => '「@」なしのTwitterユーザー名を入力して下さい。<br>例：Twitterユーザー名…「@yosiakatsuki」<br>→入力…「yosiakatsuki」',
			'input_attrs' => array(
				'placeholder' => 'username',
			),
		)
	);
	/**
	 * カードタイプ
	 */
	$ys_customizer->add_radio(
		array(
			'id'        => 'ys_twittercard_type',
			'default'   => 'summary_large_image',
			'transport' => 'postMessage',
			'label'     => 'カードタイプ',
			'choices'   => array(
				'summary_large_image' => 'Summary Card with Large Image',
				'summary'             => 'Summary Card',
			),
		)
	);
}

/**
 * SNS Share Button
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_sns_add_sns_share_button( $wp_customize ) {
	$ys_customizer = new YS_Customizer( $wp_customize );
	/**
	 * セクション追加
	 */
	$ys_customizer->add_section(
		array(
			'section'     => 'ys_customizer_section_sns_share_button',
			'title'       => 'SNSシェアボタン設定',
			'description' => '記事詳細ページに表示するSNSシェアボタンの設定',
			'panel'       => 'ys_customizer_panel_sns',
		)
	);
	/**
	 * シェアボタン表示設定
	 */
	$ys_customizer->add_label(
		array(
			'id'    => 'ys_sns_share_button_label',
			'label' => '表示するSNSシェアボタン',
		)
	);
	/**
	 * Twitter
	 */
	$ys_customizer->add_checkbox(
		array(
			'id'      => 'ys_sns_share_button_twitter',
			'default' => 1,
			'label'   => 'Twitter',
		)
	);
	/**
	 * Facebook
	 */
	$ys_customizer->add_checkbox(
		array(
			'id'      => 'ys_sns_share_button_facebook',
			'default' => 1,
			'label'   => 'Facebook',
		)
	);
	/**
	 * はてブ
	 */
	$ys_customizer->add_checkbox(
		array(
			'id'      => 'ys_sns_share_button_hatenabookmark',
			'default' => 1,
			'label'   => 'はてなブックマーク',
		)
	);
	/**
	 * Pocket
	 */
	$ys_customizer->add_checkbox(
		array(
			'id'      => 'ys_sns_share_button_pocket',
			'default' => 1,
			'label'   => 'Pocket',
		)
	);
	/**
	 * LINE
	 */
	$ys_customizer->add_checkbox(
		array(
			'id'      => 'ys_sns_share_button_line',
			'default' => 1,
			'label'   => 'LINE',
		)
	);
	/**
	 * Feedly
	 */
	$ys_customizer->add_checkbox(
		array(
			'id'      => 'ys_sns_share_button_feedly',
			'default' => 1,
			'label'   => 'Feedly',
		)
	);
	/**
	 * RSS
	 */
	$ys_customizer->add_checkbox(
		array(
			'id'      => 'ys_sns_share_button_rss',
			'default' => 1,
			'label'   => 'RSS',
		)
	);
	/**
	 * シェアボタン表示位置
	 */
	$ys_customizer->add_label(
		array(
			'id'    => 'ys_sns_share_on_label',
			'label' => 'シェアボタンの表示位置',
		)
	);
	/**
	 * 記事上部に表示する
	 */
	$ys_customizer->add_checkbox(
		array(
			'id'      => 'ys_sns_share_on_entry_header',
			'default' => 1,
			'label'   => '記事上部にシェアボタンを表示する',
		)
	);
	/**
	 * 記事下部に表示する
	 */
	$ys_customizer->add_checkbox(
		array(
			'id'      => 'ys_sns_share_on_below_entry',
			'default' => 1,
			'label'   => '記事下部にシェアボタンを表示する',
		)
	);
	/**
	 * シェアボタン表示列数
	 */
	$ys_customizer->add_label(
		array(
			'id'    => 'ys_sns_share_col_label',
			'label' => 'シェアボタンの表示列数',
		)
	);
	/**
	 * PCでの列数
	 */
	$ys_customizer->add_number(
		array(
			'id'          => 'ys_sns_share_col_pc',
			'default'     => 6,
			'label'       => 'PCでの列数(1~6)',
			'input_attrs' => array(
				'min' => 1,
				'max' => 6,
			),
		)
	);
	$ys_customizer->add_number(
		array(
			'id'          => 'ys_sns_share_col_tablet',
			'default'     => 3,
			'label'       => 'タブレットでの列数(1~6)',
			'input_attrs' => array(
				'min' => 1,
				'max' => 6,
			),
		)
	);
	$ys_customizer->add_number(
		array(
			'id'          => 'ys_sns_share_col_sp',
			'default'     => 3,
			'label'       => 'スマートフォンでの列数(1~6)',
			'input_attrs' => array(
				'min' => 1,
				'max' => 6,
			),
		)
	);
}

/**
 * Twitter share
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_sns_add_twitter_share( $wp_customize ) {
	$ys_customizer = new YS_Customizer( $wp_customize );
	/**
	 * セクション追加
	 */
	$ys_customizer->add_section(
		array(
			'section' => 'ys_customizer_section_twitter_share',
			'title'   => 'Twitterシェアボタン設定',
			'panel'   => 'ys_customizer_panel_sns',
		)
	);
	/**
	 * 投稿ユーザー（via）の設定
	 */
	$ys_customizer->add_label(
		array(
			'id'    => 'ys_sns_share_tweet_via_label',
			'label' => '投稿ユーザー（via）の設定',
		)
	);
	/**
	 * Viaに設定するTwitterアカウント名
	 */
	$ys_customizer->add_text(
		array(
			'id'          => 'ys_sns_share_tweet_via_account',
			'default'     => '',
			'transport'   => 'postMessage',
			'label'       => 'viaに設定するTwitterアカウント名',
			'description' => '「@」なしのTwitterユーザー名を入力して下さい。<br>例：Twitterユーザー名…「@yosiakatsuki」<br>→入力…「yosiakatsuki」<br>未入力の場合viaは設定されません。',
			'input_attrs' => array(
				'placeholder' => 'username',
			),
		)
	);
	/**
	 * おすすめアカウントの設定
	 */
	$ys_customizer->add_label(
		array(
			'id'    => 'ys_sns_share_tweet_related_label',
			'label' => 'おすすめアカウントの設定',
		)
	);
	/**
	 * ツイート後に表示するおすすめアカウント
	 */
	$ys_customizer->add_text(
		array(
			'id'          => 'ys_sns_share_tweet_related_account',
			'default'     => '',
			'transport'   => 'postMessage',
			'label'       => 'ツイート後に表示するおすすめアカウント',
			'description' => '「@」なしのTwitterユーザー名を入力して下さい。<br>例：Twitterユーザー名…「@yosiakatsuki」<br>→入力…「yosiakatsuki」<br>複数のアカウントをおすすめ表示する場合はカンマで区切って下さい。<br>未入力の場合おすすめアカウントは設定されません。',
			'input_attrs' => array(
				'placeholder' => 'username',
			),
		)
	);
}

/**
 * 購読ボタン設定
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_sns_add_sns_follow( $wp_customize ) {
	$ys_customizer = new YS_Customizer( $wp_customize );
	/**
	 * セクション追加
	 */
	$ys_customizer->add_section(
		array(
			'section'     => 'ys_customizer_section_sns_follow',
			'title'       => 'フォローボタン設定',
			'description' => '記事下に表示されるフォローボタンのリンク先URLの設定',
			'panel'       => 'ys_customizer_panel_sns',
		)
	);
	/**
	 * SNS購読ボタン設定
	 */
	$ys_customizer->add_label(
		array(
			'id'          => 'ys_subscribe_label',
			'label'       => 'フォローボタン設定',
			'description' => '※フォローボタンを表示しない場合は空白にしてください',
		)
	);
	/**
	 * Twitter
	 */
	$ys_customizer->add_url(
		array(
			'id'      => 'ys_subscribe_url_twitter',
			'default' => '',
			'label'   => 'Twitter',
		)
	);
	/**
	 * Facebookページ
	 */
	$ys_customizer->add_url(
		array(
			'id'      => 'ys_subscribe_url_facebook',
			'default' => '',
			'label'   => 'Facebookページ',
		)
	);
	/**
	 * Feedly
	 */
	$ys_customizer->add_url(
		array(
			'id'          => 'ys_subscribe_url_feedly',
			'default'     => '',
			'label'       => 'Feedly',
			'description' => '<a href="https://feedly.com/factory.html" target="_blank">https://feedly.com/factory.html</a>で購読用URLを生成・取得してください。（出来上がったHTMLタグのhref部分）',
		)
	);
}

/**
 * フッターSNSフォローボタン設定
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_sns_add_footer_sns_follow( $wp_customize ) {
	$ys_customizer = new YS_Customizer( $wp_customize );
	/**
	 * セクション追加
	 */
	$ys_customizer->add_section(
		array(
			'section'     => 'ys_customizer_section_footer_sns_follow',
			'title'       => 'フッターSNSフォローリンク設定',
			'description' => 'フッターに表示するSNSフォローボタンの設定<br>リンクする各SNSのプロフィールページ等のURLを入力して下さい',
			'panel'       => 'ys_customizer_panel_sns',
		)
	);
	/**
	 * Twitter
	 */
	$ys_customizer->add_url(
		array(
			'id'      => 'ys_follow_url_twitter',
			'default' => '',
			'label'   => 'Twitter',
		)
	);
	/**
	 * Facebook
	 */
	$ys_customizer->add_url(
		array(
			'id'      => 'ys_follow_url_facebook',
			'default' => '',
			'label'   => 'Facebook',
		)
	);
	/**
	 * Instagram
	 */
	$ys_customizer->add_url(
		array(
			'id'      => 'ys_follow_url_instagram',
			'default' => '',
			'label'   => 'Instagram',
		)
	);
	/**
	 * Tumblr
	 */
	$ys_customizer->add_url(
		array(
			'id'      => 'ys_follow_url_tumblr',
			'default' => '',
			'label'   => 'Tumblr',
		)
	);
	/**
	 * Youtube
	 */
	$ys_customizer->add_url(
		array(
			'id'      => 'ys_follow_url_youtube',
			'default' => '',
			'label'   => 'Youtube',
		)
	);
	/**
	 * GitHub
	 */
	$ys_customizer->add_url(
		array(
			'id'      => 'ys_follow_url_github',
			'default' => '',
			'label'   => 'GitHub',
		)
	);
	/**
	 * Pinterest
	 */
	$ys_customizer->add_url(
		array(
			'id'      => 'ys_follow_url_pinterest',
			'default' => '',
			'label'   => 'Pinterest',
		)
	);
	/**
	 * LinkedIn
	 */
	$ys_customizer->add_url(
		array(
			'id'      => 'ys_follow_url_linkedin',
			'default' => '',
			'label'   => 'LinkedIn',
		)
	);
}

/**
 * SNS用JavaScriptの読み込み
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_advanced_add_load_script( $wp_customize ) {
	$ys_customizer = new YS_Customizer( $wp_customize );
	/**
	 * セクション追加
	 */
	$ys_customizer->add_section(
		array(
			'section'     => 'ys_customizer_section_load_script',
			'title'       => 'SNS用JavaScriptの読み込み(上級者向け)',
			'description' => 'SNS用のJavaScriptを読み込みます。<br>通常、各SNSで発行した埋め込みコードにはJavaScriptのコードも含まれるのでこの設定は不要です。<br>独自に読み込み位置などを調整する場合はご利用下さい。',
			'panel'       => 'ys_customizer_panel_sns',
		)
	);
	/**
	 * Twitter用JavaScriptを読み込む
	 */
	$ys_customizer->add_checkbox(
		array(
			'id'          => 'ys_load_script_twitter',
			'default'     => 0,
			'transport'   => 'postMessage',
			'label'       => 'Twitter用JavaScriptを読み込む',
			'description' => '※Twitterのフォローボタンなどをサイト内で使用する場合、こちらにチェックを入れてください。<br>※Twitter用のJavaScriptを&lt;/body&gt;直前で読み込みます',
		)
	);
	/**
	 *  Facebook用JavaScriptを読み込む
	 */
	$ys_customizer->add_checkbox(
		array(
			'id'          => 'ys_load_script_facebook',
			'default'     => 0,
			'transport'   => 'postMessage',
			'label'       => 'Facebook用JavaScriptを読み込む',
			'description' => '※FacebookのいいねボタンやPagePluginなどをサイト内で使用する場合、こちらにチェックを入れてください。<br>※Facebook用のJavaScriptを&lt;/body&gt;直前で読み込みます',
		)
	);
}
