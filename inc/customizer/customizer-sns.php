<?php
/**
 * SNS設定
 *
 * @package ystandard
 * @author yosiakatsuki
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
			'priority' => 1100,
			'title'    => '[ys]SNS設定',
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
	$ys_customizer->add_section( array(
		'section' => 'ys_customizer_section_ogp',
		'title'   => 'OGP設定',
		'panel'   => 'ys_customizer_panel_sns',
	) );

	/**
	 * OGP metaタグを出力する
	 */
	ys_customizer_add_label(
		$wp_customize,
		array(
			'id'          => 'ys_ogp_enable_label',
			'label'       => 'OGP metaタグ',
			'section'     => 'ys_customizer_section_ogp',
			'description' => '',
		)
	);
	ys_customizer_add_setting_checkbox(
		$wp_customize,
		array(
			'id'        => 'ys_ogp_enable',
			'label'     => 'OGPのmetaタグを出力する',
			'section'   => 'ys_customizer_section_ogp',
			'transport' => 'postMessage',
		)
	);
	/**
	 * Facebook app_id
	 */
	ys_customizer_add_setting_text(
		$wp_customize,
		array(
			'id'          => 'ys_ogp_fb_app_id',
			'default'     => '',
			'label'       => 'Facebook app_id',
			'section'     => 'ys_customizer_section_ogp',
			'transport'   => 'postMessage',
			'input_attrs' => array(
				'placeholder' => '000000000000000',
				'maxlength'   => 15,
			),
		)
	);
	/**
	 * Facebook app_id
	 */
	ys_customizer_add_setting_text(
		$wp_customize,
		array(
			'id'          => 'ys_ogp_fb_admins',
			'default'     => '',
			'label'       => 'Facebook admins',
			'section'     => 'ys_customizer_section_ogp',
			'transport'   => 'postMessage',
			'input_attrs' => array(
				'placeholder' => '000000000000000',
				'maxlength'   => 15,
			),
		)
	);
	/**
	 * OGPデフォルト画像
	 */
	$ys_customizer->add_image( array(
		'id'          => 'ys_ogp_default_image',
		'default'     => '',
		'label'       => 'OGPデフォルト画像',
		'description' => 'トップページ・アーカイブページ・投稿にアイキャッチ画像が無かった場合のデフォルト画像を指定して下さい。<br>おすすめサイズ：横1200px - 縦630px ',
		'transport'   => 'postMessage',
	) );
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
	$ys_customizer->add_section( array(
		'section' => 'ys_customizer_section_twitter_cards',
		'title'   => 'Twitterカード設定',
		'panel'   => 'ys_customizer_panel_sns',
	) );
	/**
	 * Twitterカードのmetaタグを出力する
	 */
	ys_customizer_add_label(
		$wp_customize,
		array(
			'id'          => 'ys_twittercard_enable_label',
			'label'       => 'Twitterカードmetaタグ',
			'section'     => 'ys_customizer_section_twitter_cards',
			'description' => '',
		)
	);
	ys_customizer_add_setting_checkbox(
		$wp_customize,
		array(
			'id'        => 'ys_twittercard_enable',
			'label'     => 'Twitterカードのmetaタグを出力する',
			'default'   => 1,
			'section'   => 'ys_customizer_section_twitter_cards',
			'transport' => 'postMessage',
		)
	);
	/**
	 * ユーザー名
	 */
	ys_customizer_add_setting_text(
		$wp_customize,
		array(
			'id'          => 'ys_twittercard_user',
			'default'     => '',
			'label'       => 'Twitterカードのユーザー名',
			'section'     => 'ys_customizer_section_twitter_cards',
			'description' => '「@」なしのTwitterユーザー名を入力して下さい。<br>例：Twitterユーザー名…「@yosiakatsuki」<br>→入力…「yosiakatsuki」',
			'transport'   => 'postMessage',
			'input_attrs' => array(
				'placeholder' => 'username',
			),
		)
	);
	/**
	 * カードタイプ
	 */
	$ys_customizer->add_radio( array(
		'id'        => 'ys_twittercard_type',
		'default'   => 'summary_large_image',
		'label'     => 'カードタイプ',
		'transport' => 'postMessage',
		'choices'   => array(
			'summary_large_image' => 'Summary Card with Large Image',
			'summary'             => 'Summary Card',
		),
	) );
}

/**
 * SNS Share Button
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_sns_add_sns_share_button( $wp_customize ) {
	/**
	 * セクション追加
	 */
	$wp_customize->add_section(
		'ys_customizer_section_sns_share_button',
		array(
			'title'       => 'SNSシェアボタン設定',
			'panel'       => 'ys_customizer_panel_sns',
			'description' => '記事詳細ページに表示するSNSシェアボタンの設定',
		)
	);
	/**
	 * シェアボタン表示設定
	 */
	ys_customizer_add_label(
		$wp_customize,
		array(
			'id'          => 'ys_sns_share_button_label',
			'label'       => '表示するSNSシェアボタン',
			'section'     => 'ys_customizer_section_sns_share_button',
			'description' => '',
		)
	);
	/**
	 * Twitter
	 */
	ys_customizer_add_setting_checkbox(
		$wp_customize,
		array(
			'id'      => 'ys_sns_share_button_twitter',
			'label'   => 'Twitter',
			'default' => 1,
			'section' => 'ys_customizer_section_sns_share_button',
		)
	);
	/**
	 * Facebook
	 */
	ys_customizer_add_setting_checkbox(
		$wp_customize,
		array(
			'id'      => 'ys_sns_share_button_facebook',
			'label'   => 'Facebook',
			'default' => 1,
			'section' => 'ys_customizer_section_sns_share_button',
		)
	);
	/**
	 * はてブ
	 */
	ys_customizer_add_setting_checkbox(
		$wp_customize,
		array(
			'id'      => 'ys_sns_share_button_hatenabookmark',
			'label'   => 'はてなブックマーク',
			'default' => 1,
			'section' => 'ys_customizer_section_sns_share_button',
		)
	);
	/**
	 * Google+
	 */
	ys_customizer_add_setting_checkbox(
		$wp_customize,
		array(
			'id'      => 'ys_sns_share_button_googlepuls',
			'label'   => 'Google+',
			'default' => 1,
			'section' => 'ys_customizer_section_sns_share_button',
		)
	);
	/**
	 * Pocket
	 */
	ys_customizer_add_setting_checkbox(
		$wp_customize,
		array(
			'id'      => 'ys_sns_share_button_pocket',
			'label'   => 'Pocket',
			'default' => 1,
			'section' => 'ys_customizer_section_sns_share_button',
		)
	);
	/**
	 * LINE
	 */
	ys_customizer_add_setting_checkbox(
		$wp_customize,
		array(
			'id'      => 'ys_sns_share_button_line',
			'label'   => 'LINE',
			'default' => 1,
			'section' => 'ys_customizer_section_sns_share_button',
		)
	);
	/**
	 * Feedly
	 */
	ys_customizer_add_setting_checkbox(
		$wp_customize,
		array(
			'id'      => 'ys_sns_share_button_feedly',
			'label'   => 'Feedly',
			'default' => 1,
			'section' => 'ys_customizer_section_sns_share_button',
		)
	);
	/**
	 * RSS
	 */
	ys_customizer_add_setting_checkbox(
		$wp_customize,
		array(
			'id'      => 'ys_sns_share_button_rss',
			'label'   => 'RSS',
			'default' => 1,
			'section' => 'ys_customizer_section_sns_share_button',
		)
	);
	/**
	 * シェアボタン表示位置
	 */
	ys_customizer_add_label(
		$wp_customize,
		array(
			'id'          => 'ys_sns_share_on_label',
			'label'       => 'シェアボタンの表示位置',
			'section'     => 'ys_customizer_section_sns_share_button',
			'description' => '',
		)
	);
	/**
	 * 記事上部に表示する
	 */
	ys_customizer_add_setting_checkbox(
		$wp_customize,
		array(
			'id'      => 'ys_sns_share_on_entry_header',
			'label'   => '記事上部にシェアボタンを表示する',
			'default' => 1,
			'section' => 'ys_customizer_section_sns_share_button',
		)
	);
	/**
	 * 記事下部に表示する
	 */
	ys_customizer_add_setting_checkbox(
		$wp_customize,
		array(
			'id'      => 'ys_sns_share_on_below_entry',
			'label'   => '記事下部にシェアボタンを表示する',
			'default' => 1,
			'section' => 'ys_customizer_section_sns_share_button',
		)
	);
}

/**
 * Twitter share
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_sns_add_twitter_share( $wp_customize ) {
	/**
	 * セクション追加
	 */
	$wp_customize->add_section(
		'ys_customizer_section_twitter_share',
		array(
			'title' => 'Twitterシェアボタン設定',
			'panel' => 'ys_customizer_panel_sns',
		)
	);
	/**
	 * 投稿ユーザー（via）の設定
	 */
	ys_customizer_add_label(
		$wp_customize,
		array(
			'id'          => 'ys_sns_share_tweet_via_label',
			'label'       => '投稿ユーザー（via）の設定',
			'section'     => 'ys_customizer_section_twitter_share',
			'description' => '',
		)
	);
	ys_customizer_add_setting_checkbox(
		$wp_customize,
		array(
			'id'          => 'ys_sns_share_tweet_via',
			'label'       => 'Twitterシェアにviaを付加する',
			'description' => '※「viaに設定するTwitterアカウント名」の設定も必要です',
			'default'     => 0,
			'section'     => 'ys_customizer_section_twitter_share',
			'transport'   => 'postMessage',
		)
	);
	/**
	 * Viaに設定するTwitterアカウント名
	 */
	ys_customizer_add_setting_text(
		$wp_customize,
		array(
			'id'          => 'ys_sns_share_tweet_via_account',
			'default'     => '',
			'label'       => 'viaに設定するTwitterアカウント名',
			'section'     => 'ys_customizer_section_twitter_share',
			'description' => '「@」なしのTwitterユーザー名を入力して下さい。<br>例：Twitterユーザー名…「@yosiakatsuki」<br>→入力…「yosiakatsuki」',
			'transport'   => 'postMessage',
			'input_attrs' => array(
				'placeholder' => 'username',
			),
		)
	);
	/**
	 * おすすめアカウントの設定
	 */
	ys_customizer_add_label(
		$wp_customize,
		array(
			'id'          => 'ys_sns_share_tweet_related_label',
			'label'       => 'おすすめアカウントの設定',
			'section'     => 'ys_customizer_section_twitter_share',
			'description' => '',
		)
	);
	ys_customizer_add_setting_checkbox(
		$wp_customize,
		array(
			'id'          => 'ys_sns_share_tweet_related',
			'label'       => 'ツイート後におすすめアカウントを表示する',
			'description' => '※合わせて「ツイート後に表示するおすすめアカウント」の設定が必要です',
			'default'     => 0,
			'section'     => 'ys_customizer_section_twitter_share',
			'transport'   => 'postMessage',
		)
	);
	/**
	 * ツイート後に表示するおすすめアカウント
	 */
	ys_customizer_add_setting_text(
		$wp_customize,
		array(
			'id'          => 'ys_sns_share_tweet_related_account',
			'default'     => '',
			'label'       => 'ツイート後に表示するおすすめアカウント',
			'section'     => 'ys_customizer_section_twitter_share',
			'description' => '「@」なしのTwitterユーザー名を入力して下さい。<br>例：Twitterユーザー名…「@yosiakatsuki」<br>→入力…「yosiakatsuki」<br>複数のアカウントをおすすめ表示する場合はカンマで区切って下さい',
			'transport'   => 'postMessage',
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
	/**
	 * セクション追加
	 */
	$wp_customize->add_section(
		'ys_customizer_section_sns_follow',
		array(
			'title'       => 'フォローボタン設定',
			'panel'       => 'ys_customizer_panel_sns',
			'description' => '記事下に表示されるフォローボタンのリンク先URLの設定',
		)
	);
	/**
	 * SNS購読ボタン設定
	 */
	ys_customizer_add_label(
		$wp_customize,
		array(
			'id'          => 'ys_subscribe_label',
			'label'       => 'フォローボタン設定',
			'section'     => 'ys_customizer_section_sns_follow',
			'description' => '※フォローボタンを表示しない場合は空白にしてください',
		)
	);
	/**
	 * Twitter
	 */
	ys_customizer_add_setting_url(
		$wp_customize,
		array(
			'id'          => 'ys_subscribe_url_twitter',
			'default'     => '',
			'label'       => 'Twitter',
			'section'     => 'ys_customizer_section_sns_follow',
			'description' => '',
		)
	);
	/**
	 * Facebookページ
	 */
	ys_customizer_add_setting_url(
		$wp_customize,
		array(
			'id'          => 'ys_subscribe_url_facebook',
			'default'     => '',
			'label'       => 'Facebookページ',
			'section'     => 'ys_customizer_section_sns_follow',
			'description' => '',
		)
	);
	/**
	 * Facebookページ
	 */
	ys_customizer_add_setting_url(
		$wp_customize,
		array(
			'id'          => 'ys_subscribe_url_googleplus',
			'default'     => '',
			'label'       => 'Google+',
			'section'     => 'ys_customizer_section_sns_follow',
			'description' => '',
		)
	);
	/**
	 * Feedly
	 */
	ys_customizer_add_setting_url(
		$wp_customize,
		array(
			'id'          => 'ys_subscribe_url_feedly',
			'default'     => '',
			'label'       => 'Feedly',
			'section'     => 'ys_customizer_section_sns_follow',
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
	/**
	 * セクション追加
	 */
	$wp_customize->add_section(
		'ys_customizer_section_footer_sns_follow',
		array(
			'title'       => 'フッターSNSフォローリンク設定',
			'panel'       => 'ys_customizer_panel_sns',
			'description' => 'フッターに表示するSNSフォローボタンの設定<br>リンクする各SNSのプロフィールページ等のURLを入力して下さい',
		)
	);
	/**
	 * Twitter
	 */
	ys_customizer_add_setting_url(
		$wp_customize,
		array(
			'id'          => 'ys_follow_url_twitter',
			'default'     => '',
			'label'       => 'Twitter',
			'section'     => 'ys_customizer_section_footer_sns_follow',
			'description' => '',
		)
	);
	/**
	 * Facebook
	 */
	ys_customizer_add_setting_url(
		$wp_customize,
		array(
			'id'          => 'ys_follow_url_facebook',
			'default'     => '',
			'label'       => 'Facebook',
			'section'     => 'ys_customizer_section_footer_sns_follow',
			'description' => '',
		)
	);
	/**
	 * Google+
	 */
	ys_customizer_add_setting_url(
		$wp_customize,
		array(
			'id'          => 'ys_follow_url_googlepuls',
			'default'     => '',
			'label'       => 'Google+',
			'section'     => 'ys_customizer_section_footer_sns_follow',
			'description' => '',
		)
	);
	/**
	 * Instagram
	 */
	ys_customizer_add_setting_url(
		$wp_customize,
		array(
			'id'          => 'ys_follow_url_instagram',
			'default'     => '',
			'label'       => 'Instagram',
			'section'     => 'ys_customizer_section_footer_sns_follow',
			'description' => '',
		)
	);
	/**
	 * Tumblr
	 */
	ys_customizer_add_setting_url(
		$wp_customize,
		array(
			'id'          => 'ys_follow_url_tumblr',
			'default'     => '',
			'label'       => 'Tumblr',
			'section'     => 'ys_customizer_section_footer_sns_follow',
			'description' => '',
		)
	);
	/**
	 * Youtube
	 */
	ys_customizer_add_setting_url(
		$wp_customize,
		array(
			'id'          => 'ys_follow_url_youtube',
			'default'     => '',
			'label'       => 'Youtube',
			'section'     => 'ys_customizer_section_footer_sns_follow',
			'description' => '',
		)
	);
	/**
	 * GitHub
	 */
	ys_customizer_add_setting_url(
		$wp_customize,
		array(
			'id'          => 'ys_follow_url_github',
			'default'     => '',
			'label'       => 'GitHub',
			'section'     => 'ys_customizer_section_footer_sns_follow',
			'description' => '',
		)
	);
	/**
	 * Pinterest
	 */
	ys_customizer_add_setting_url(
		$wp_customize,
		array(
			'id'          => 'ys_follow_url_pinterest',
			'default'     => '',
			'label'       => 'Pinterest',
			'section'     => 'ys_customizer_section_footer_sns_follow',
			'description' => '',
		)
	);
	/**
	 * LinkedIn
	 */
	ys_customizer_add_setting_url(
		$wp_customize,
		array(
			'id'          => 'ys_follow_url_linkedin',
			'default'     => '',
			'label'       => 'LinkedIn',
			'section'     => 'ys_customizer_section_footer_sns_follow',
			'description' => '',
		)
	);
}
/**
 * SNS用JavaScriptの読み込み
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_advanced_add_load_script( $wp_customize ) {
	/**
	 * セクション追加
	 */
	$wp_customize->add_section(
		'ys_customizer_section_load_script',
		array(
			'title'       => 'SNS用JavaScriptの読み込み(上級者向け)',
			'panel'       => 'ys_customizer_panel_sns',
			'description' => 'SNS用のJavaScriptを読み込みます。<br>通常、各SNSで発行した埋め込みコードにはJavaScriptのコードも含まれるのでこの設定は不要です。<br>独自に読み込み位置などを調整する場合はご利用下さい。',
		)
	);
	/**
	 * Twitter用JavaScriptを読み込む
	 */
	ys_customizer_add_setting_checkbox(
		$wp_customize,
		array(
			'id'          => 'ys_load_script_twitter',
			'label'       => 'Twitter用JavaScriptを読み込む',
			'description' => '※Twitterのフォローボタンなどをサイト内で使用する場合、こちらにチェックを入れてください。<br>※Twitter用のJavaScriptを&lt;/body&gt;直前で読み込みます',
			'default'     => 0,
			'section'     => 'ys_customizer_section_load_script',
			'transport'   => 'postMessage',
		)
	);
	/**
	 *  Facebook用JavaScriptを読み込む
	 */
	ys_customizer_add_setting_checkbox(
		$wp_customize,
		array(
			'id'          => 'ys_load_script_facebook',
			'label'       => 'Facebook用JavaScriptを読み込む',
			'description' => '※FacebookのいいねボタンやPagePluginなどをサイト内で使用する場合、こちらにチェックを入れてください。<br>※Facebook用のJavaScriptを&lt;/body&gt;直前で読み込みます',
			'default'     => 0,
			'section'     => 'ys_customizer_section_load_script',
			'transport'   => 'postMessage',
		)
	);
}
