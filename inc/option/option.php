<?php
/**
 * 設定取得
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * テーマ内で使用する設定の取得
 *
 * @return array
 */
function ys_get_options() {
	global $ys_options;
	if ( null !== $ys_options ) {
		return apply_filters( 'ys_get_options', $ys_options );
	}
	/**
	 * 配列作成
	 */
	$result = array();
	/**
	 * **********
	 * 基本設定
	 * **********
	 */
	// ロゴを出力しない.
	$result['ys_logo_hidden'] = get_option( 'ys_logo_hidden', 0 );
	// キャッチフレーズを出力しない.
	$result['ys_wp_hidden_blogdescription'] = get_option( 'ys_wp_hidden_blogdescription', 0 );
	// TOPページのmeta description.
	$result['ys_wp_site_description'] = get_option( 'ys_wp_site_description', '' );
	/**
	 * **********
	 * 色 設定
	 * **********
	 */
	// テーマカスタマイザーの色設定を無効にする.
	$result['ys_desabled_color_customizeser'] = get_option( 'ys_desabled_color_customizeser', 0 );
	/**
	 * **********
	 * [ys]サイト共通設定
	 * **********
	 */
	// タイトルの区切り文字.
	$result['ys_title_separate'] = get_option( 'ys_title_separate', '' );
	// 発行年.
	$result['ys_copyright_year'] = ys_get_copyright_year_option();
	// 発行年.
	$result['ys_option_excerpt_length'] = get_option( 'ys_option_excerpt_length', 80 );
	/**
	 * **********
	 * [ys]デザイン設定
	 * **********
	 */
	// ヘッダータイプ.
	$result['ys_design_header_type'] = get_option( 'ys_design_header_type', '1row' );
	// モバイル表示でサイドバーを出力しない.
	$result['ys_show_sidebar_mobile'] = get_option( 'ys_show_sidebar_mobile', 0 );
	/**
	 * **********
	 * [ys]投稿ページ設定
	 * **********
	 */
	// 個別ページでアイキャッチ画像を表示する.
	$result['ys_show_post_thumbnail'] = get_option( 'ys_show_post_thumbnail', 1 );
	// カテゴリー・タグ情報を表示する.
	$result['ys_show_post_category'] = get_option( 'ys_show_post_category', 1 );
	// ブログフォローボックスを表示する.
	$result['ys_show_post_follow_box'] = get_option( 'ys_show_post_follow_box', 1 );
	// 著者情報を表示する.
	$result['ys_show_post_author'] = get_option( 'ys_show_post_author', 1 );
	// 関連記事を出力する.
	$result['ys_show_post_related'] = get_option( 'ys_show_post_related', 1 );
	// 次の記事・前の記事を表示する.
	$result['ys_show_post_paging'] = get_option( 'ys_show_post_paging', 1 );
	/**
	 * **********
	 * [ys]固定ページ設定
	 * **********
	 */
	// 個別ページでアイキャッチ画像を表示する.
	$result['ys_show_page_thumbnail'] = get_option( 'ys_show_page_thumbnail', 1 );
	// ブログフォローボックスを表示する.
	$result['ys_show_page_follow_box'] = get_option( 'ys_show_page_follow_box', 1 );
	// 著者情報を表示する.
	$result['ys_show_page_author'] = get_option( 'ys_show_page_author', 1 );
	/**
	 * **********
	 * [ys]アーカイブページ設定
	 * **********
	 */
	// 一覧表示タイプ.
	$result['ys_archive_type'] = get_option( 'ys_archive_type', 'list' );
	// 著者情報を表示する.
	$result['ys_show_archive_author'] = get_option( 'ys_show_archive_author', 1 );
	/**
	 * **********
	 * [ys]SNS設定
	 * **********
	 */
	/**
	 * OGP
	 */
	// OGPメタタグを出力する.
	$result['ys_ogp_enable'] = get_option( 'ys_ogp_enable', 1 );
	// Facebook app id.
	$result['ys_ogp_fb_app_id'] = esc_attr( get_option( 'ys_ogp_fb_app_id', '' ) );
	// facebook admins.
	$result['ys_ogp_fb_admins'] = esc_attr( get_option( 'ys_ogp_fb_admins', '' ) );
	// OGPデフォルト画像.
	$result['ys_ogp_default_image'] = get_option( 'ys_ogp_default_image', '' );
	/**
	 * Twitterカード
	 */
	// Twitterカードを出力する.
	$result['ys_twittercard_enable'] = get_option( 'ys_twittercard_enable', 1 );
	// Twitterカードのユーザー名.
	$result['ys_twittercard_user'] = esc_attr( get_option( 'ys_twittercard_user', '' ) );
	// Twitterカード タイプ.
	$result['ys_twittercard_type'] = esc_attr( get_option( 'ys_twittercard_type', 'summary_large_image' ) );
	/**
	 * SNSシェアボタン設定
	 */
	// Twitter.
	$result['ys_sns_share_button_twitter'] = get_option( 'ys_sns_share_button_twitter', 1 );
	// Facebook.
	$result['ys_sns_share_button_facebook'] = get_option( 'ys_sns_share_button_facebook', 1 );
	// はてブ.
	$result['ys_sns_share_button_hatenabookmark'] = get_option( 'ys_sns_share_button_hatenabookmark', 1 );
	// Google+.
	$result['ys_sns_share_button_googlepuls'] = get_option( 'ys_sns_share_button_googlepuls', 1 );
	// Pocket.
	$result['ys_sns_share_button_pocket'] = get_option( 'ys_sns_share_button_pocket', 1 );
	// LINE.
	$result['ys_sns_share_button_line'] = get_option( 'ys_sns_share_button_line', 1 );
	// Feedly.
	$result['ys_sns_share_button_feedly'] = get_option( 'ys_sns_share_button_feedly', 1 );
	// RSS.
	$result['ys_sns_share_button_rss'] = get_option( 'ys_sns_share_button_rss', 1 );
	// シェアボタンを投稿上部に表示する.
	$result['ys_sns_share_on_entry_header'] = get_option( 'ys_sns_share_on_entry_header', 1 );
	// シェアボタンを投稿下に表示する.
	$result['ys_sns_share_on_below_entry'] = get_option( 'ys_sns_share_on_below_entry', 1 );
	/**
	 * Twitterシェアボタン
	 */
	// Tweetポタンに via を出力するか.
	$result['ys_sns_share_tweet_via'] = get_option( 'ys_sns_share_tweet_via', 0 );
	// Twitter via アカウント.
	$result['ys_sns_share_tweet_via_account'] = esc_attr( get_option( 'ys_sns_share_tweet_via_account', '' ) );
	// ツイート後におすすめアカウントを表示する.
	$result['ys_sns_share_tweet_related'] = get_option( 'ys_sns_share_tweet_related', 0 );
	// Twitter related アカウント.
	$result['ys_sns_share_tweet_related_account'] = esc_attr( get_option( 'ys_sns_share_tweet_related_account', '' ) );
	/**
	 * 購読ボタン設定
	 */
	// Twitterフォローリンク.
	$result['ys_subscribe_url_twitter'] = esc_url( get_option( 'ys_subscribe_url_twitter', '' ) );
	// Facebookページフォローリンク.
	$result['ys_subscribe_url_facebook'] = esc_url( get_option( 'ys_subscribe_url_facebook', '' ) );
	// Google＋ページフォローリンク.
	$result['ys_subscribe_url_googleplus'] = esc_url( get_option( 'ys_subscribe_url_googleplus', '' ) );
	// Feedlyフォローリンク.
	$result['ys_subscribe_url_feedly'] = esc_url( get_option( 'ys_subscribe_url_feedly', '' ) );
	// SP表示列数.
	$result['ys_subscribe_col_sp'] = get_option( 'ys_subscribe_col_sp', '2' );
	// PC表示列数.
	$result['ys_subscribe_col_pc'] = get_option( 'ys_subscribe_col_pc', '4' );
	/**
	 * フッターSNSフォローリンク
	 */
	// TwitterフォローURL.
	$result['ys_follow_url_twitter'] = esc_url( get_option( 'ys_follow_url_twitter', '' ) );
	// facebookフォローURL.
	$result['ys_follow_url_facebook'] = esc_url( get_option( 'ys_follow_url_facebook', '' ) );
	// google+フォローURL.
	$result['ys_follow_url_googleplus'] = esc_url( get_option( 'ys_follow_url_googleplus', '' ) );
	// instagramフォローURL.
	$result['ys_follow_url_instagram'] = esc_url( get_option( 'ys_follow_url_instagram', '' ) );
	// tumblrフォローURL.
	$result['ys_follow_url_tumblr'] = esc_url( get_option( 'ys_follow_url_tumblr', '' ) );
	// YouTubeフォローURL.
	$result['ys_follow_url_youtube'] = esc_url( get_option( 'ys_follow_url_youtube', '' ) );
	// GitHubフォローURL.
	$result['ys_follow_url_github'] = esc_url( get_option( 'ys_follow_url_github', '' ) );
	// PinterestフォローURL.
	$result['ys_follow_url_pinterest'] = esc_url( get_option( 'ys_follow_url_pinterest', '' ) );
	// linkedinフォローURL.
	$result['ys_follow_url_linkedin'] = esc_url( get_option( 'ys_follow_url_linkedin', '' ) );
	/**
	 * SNS用JavaScriptの読み込み
	 */
	// Twitter埋め込み用js読み込み.
	$result['ys_load_script_twitter'] = get_option( 'ys_load_script_twitter', 0 );
	// Facebook埋め込み用js読み込み.
	$result['ys_load_script_facebook'] = get_option( 'ys_load_script_facebook', 0 );
	/**
	 * **********
	 * [ys]SEO設定
	 * **********
	 */
	/**
	 * アーカイブページのnoindex設定
	 */
	// カテゴリー一覧をnoindexにする.
	$result['ys_archive_noindex_category'] = get_option( 'ys_archive_noindex_category', 0 );
	// タグ一覧をnoindexにする.
	$result['ys_archive_noindex_tag'] = get_option( 'ys_archive_noindex_tag', 1 );
	// 投稿者一覧をnoindexにする.
	$result['ys_archive_noindex_author'] = get_option( 'ys_archive_noindex_author', 1 );
	// 日別一覧をnoindexにする.
	$result['ys_archive_noindex_date'] = get_option( 'ys_archive_noindex_date', 1 );
	/**
	 * Google Analytics
	 */
	// Google Aanalytics トラッキングID.
	$result['ys_ga_tracking_id'] = esc_attr( get_option( 'ys_ga_tracking_id', '' ) );
	// Google Aanalytics トラッキングコードタイプ.
	$result['ys_ga_tracking_type'] = esc_attr( get_option( 'ys_ga_tracking_type', 'gtag' ) );
	// ログイン中はアクセス数をカウントしない.
	$result['ys_ga_exclude_logged_in_user'] = get_option( 'ys_ga_exclude_logged_in_user', 0 );
	/**
	 * 構造化データ設定
	 */
	// パブリッシャー画像.
	$result['ys_option_structured_data_publisher_image'] = get_option( 'ys_option_structured_data_publisher_image', '' );
	// パブリッシャー名.
	$result['ys_option_structured_data_publisher_name'] = get_option( 'ys_option_structured_data_publisher_name', '' );
	/**
	 * **********
	 * [ys]サイト高速化設定
	 * **********
	 */
	/**
	 * WordPress標準機能で読み込むCSS・JavaScriptの無効化
	 */
	// 絵文字を出力しない.
	$result['ys_option_disable_wp_emoji'] = get_option( 'ys_option_disable_wp_emoji', 1 );
	// oembedを出力しない.
	$result['ys_option_disable_wp_oembed'] = get_option( 'ys_option_disable_wp_oembed', 1 );
	/**
	 * CSS読み込みの最適化
	 */
	// CSSの読み込みを最適化する.
	$result['ys_option_optimize_load_css'] = get_option( 'ys_option_optimize_load_css', 0 );
	/**
	 * JavaScript読み込みの最適化
	 */
	// JavaScriptの読み込みを非同期化する.
	$result['ys_option_optimize_load_js'] = get_option( 'ys_option_optimize_load_js', 0 );
	// CDNにホストされているjQueryを読み込む（URLを設定）.
	$result['ys_load_cdn_jquery_url'] = esc_url( get_option( 'ys_load_cdn_jquery_url', '' ) );
	// jQueryを読み込まない.
	$result['ys_not_load_jquery'] = get_option( 'ys_not_load_jquery', 0 );
	/**
	 * **********
	 * [ys]広告設定
	 * **********
	 */
	// 広告　タイトル下.
	$result['ys_advertisement_under_title'] = get_option( 'ys_advertisement_under_title', '' );
	// 広告　moreタグ置換.
	$result['ys_advertisement_replace_more'] = get_option( 'ys_advertisement_replace_more', '' );
	// 広告　記事下　左.
	$result['ys_advertisement_under_content_left'] = get_option( 'ys_advertisement_under_content_left', '' );
	// 広告　記事下　右.
	$result['ys_advertisement_under_content_right'] = get_option( 'ys_advertisement_under_content_right', '' );
	// 広告　タイトル下 SP.
	$result['ys_advertisement_under_title_sp'] = get_option( 'ys_advertisement_under_title_sp', '' );
	// 広告　moreタグ置換 SP.
	$result['ys_advertisement_replace_more_sp'] = get_option( 'ys_advertisement_replace_more_sp', '' );
	// 広告　記事下 SP.
	$result['ys_advertisement_under_content_sp'] = get_option( 'ys_advertisement_under_content_sp', '' );
	/**
	 * **********
	 * [ys]AMP設定
	 * **********
	 */
	// AMPページを有効化するか.
	$result['ys_amp_enable'] = get_option( 'ys_amp_enable', 0 );
	// AMPのGoogle Analyticsトラッキングコード.
	$result['ys_ga_tracking_id_amp'] = esc_attr( get_option( 'ys_ga_tracking_id_amp', '' ) );
	// 広告　タイトル下.
	$result['ys_amp_advertisement_under_title'] = get_option( 'ys_amp_advertisement_under_title', '' );
	// moreタグ置換.
	$result['ys_amp_advertisement_replace_more'] = get_option( 'ys_amp_advertisement_replace_more', '' );
	// 記事下　左.
	$result['ys_amp_advertisement_under_content'] = get_option( 'ys_amp_advertisement_under_content', '' );

	return apply_filters( 'ys_get_options', $result );
}

/**
 * 設定取得
 *
 * @param string $name option key.
 */
function ys_get_option( $name ) {
	$options = ys_get_options();
	return apply_filters( 'ys_get_option', $options[ $name ], $name );
}

/**
 * 発行年の取得
 */
function ys_get_copyright_year_option() {
	$copyright_year = esc_attr( get_option( 'ys_copyright_year', date_i18n( 'Y' ) ) );
	return '' == $copyright_year ? date_i18n( 'Y' ) : $copyright_year;
}