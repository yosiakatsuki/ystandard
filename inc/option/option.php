<?php
/**
 * 設定取得
 *
 * @package ystandard
 * @author  yosiakatsuki
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
	 * ヘッダーメディア
	 */
	// ヘッダーメディア用ショートコード.
	$result['ys_wp_header_media_shortcode'] = get_option( 'ys_wp_header_media_shortcode', '' );
	// 画像・動画の全面表示.
	$result['ys_wp_header_media_full'] = get_option( 'ys_wp_header_media_full', 0 );
	// 画像・動画の全面表示 表示タイプ.
	$result['ys_wp_header_media_full_type'] = get_option( 'ys_wp_header_media_full_type', 'dark' );
	// 画像・動画の全面表示 ヘッダー不透明度.
	$result['ys_wp_header_media_full_opacity'] = get_option( 'ys_wp_header_media_full_opacity', 50 );
	// カスタムヘッダーの全ページ表示.
	$result['ys_wp_header_media_all_page'] = get_option( 'ys_wp_header_media_all_page', 0 );
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
	$result['ys_option_excerpt_length'] = get_option( 'ys_option_excerpt_length', 110 );
	/**
	 * **********
	 * [ys]デザイン設定
	 * **********
	 */
	// ヘッダータイプ.
	$result['ys_design_header_type'] = get_option( 'ys_design_header_type', '1row' );
	// モバイル表示でサイドバーを出力しない.
	$result['ys_show_sidebar_mobile'] = get_option( 'ys_show_sidebar_mobile', 0 );
	// スライドメニューに検索フォームを出力する.
	$result['ys_show_search_form_on_slide_menu'] = get_option( 'ys_show_search_form_on_slide_menu', 0 );
	/**
	 * [ys]投稿ページ設定
	 */
	// 表示レウアウト.
	$result['ys_post_layout'] = get_option( 'ys_post_layout', '2col' );
	// 個別ページでアイキャッチ画像を表示する.
	$result['ys_show_post_thumbnail'] = get_option( 'ys_show_post_thumbnail', 1 );
	// 個別ページで投稿日・更新日を表示する.
	$result['ys_show_post_publish_date'] = get_option( 'ys_show_post_publish_date', 1 );
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
	// 記事上ウィジェットを出力する.
	$result['ys_show_post_before_content_widget'] = get_option( 'ys_show_post_before_content_widget', 0 );
	// 記事上ウィジェットの優先順位.
	$result['ys_post_before_content_widget_priority'] = get_option( 'ys_post_before_content_widget_priority', 10 );
	// 記事下ウィジェットを出力する.
	$result['ys_show_post_after_content_widget'] = get_option( 'ys_show_post_after_content_widget', 0 );
	// 記事下ウィジェットの優先順位.
	$result['ys_post_after_content_widget_priority'] = get_option( 'ys_post_after_content_widget_priority', 10 );
	/**
	 * [ys]固定ページ設定
	 */
	// 表示レウアウト.
	$result['ys_page_layout'] = get_option( 'ys_page_layout', '2col' );
	// 個別ページでアイキャッチ画像を表示する.
	$result['ys_show_page_thumbnail'] = get_option( 'ys_show_page_thumbnail', 1 );
	// 個別ページで投稿日時を表示する.
	$result['ys_show_page_publish_date'] = get_option( 'ys_show_page_publish_date', 1 );
	// ブログフォローボックスを表示する.
	$result['ys_show_page_follow_box'] = get_option( 'ys_show_page_follow_box', 1 );
	// 著者情報を表示する.
	$result['ys_show_page_author'] = get_option( 'ys_show_page_author', 1 );
	// 記事上ウィジェットを出力する.
	$result['ys_show_page_before_content_widget'] = get_option( 'ys_show_page_before_content_widget', 0 );
	// 記事上ウィジェットの優先順位.
	$result['ys_page_before_content_widget_priority'] = get_option( 'ys_page_before_content_widget_priority', 10 );
	// 記事下ウィジェットを出力する.
	$result['ys_show_page_after_content_widget'] = get_option( 'ys_show_page_after_content_widget', 0 );
	// 記事下ウィジェットの優先順位.
	$result['ys_page_after_content_widget_priority'] = get_option( 'ys_page_after_content_widget_priority', 10 );
	/**
	 * [ys]アーカイブページ設定
	 */
	// 表示レウアウト.
	$result['ys_archive_layout'] = get_option( 'ys_archive_layout', '2col' );
	// 一覧表示タイプ.
	$result['ys_archive_type'] = get_option( 'ys_archive_type', 'list' );
	// 投稿日を表示する.
	$result['ys_show_archive_publish_date'] = get_option( 'ys_show_archive_publish_date', 1 );
	// 著者情報を表示する.
	$result['ys_show_archive_author'] = get_option( 'ys_show_archive_author', 1 );
	// パンくずリストの「投稿ページ」表示.
	$result['ys_show_page_for_posts_on_breadcrumbs'] = get_option( 'ys_show_page_for_posts_on_breadcrumbs', 1 );
	/**
	 * [ys]ワンカラムテンプレート設定設定
	 */
	// アイキャッチ画像表示タイプ.
	/**
	 * Typo吸収
	 * TODO: 設定変更の削除 v2.5.0くらいで
	 */
	ys_change_option_key(
		'ys_design_one_col_tumbnail_type',
		'full',
		'ys_design_one_col_thumbnail_type',
		'full'
	);
	$result['ys_design_one_col_thumbnail_type'] = get_option( 'ys_design_one_col_thumbnail_type', 'full' );
	/**
	 * Gutenberg設定
	 */
	$result['ys_enqueue_gutenberg_css'] = get_option( 'ys_enqueue_gutenberg_css', 1 );

	/**
	 * **********
	 * [ys]フロントページ設定
	 * **********
	 */
	// 表示レイアウト.
	$result['ys_front_page_layout'] = get_option( 'ys_front_page_layout', '2col' );
	// フロントページ作成タイプ.
	$result['ys_front_page_type'] = get_option( 'ys_front_page_type', 'normal' );
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
	$result['ys_follow_url_google_plus'] = esc_url( get_option( 'ys_follow_url_google_plus', '' ) );
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
	 * メタデスクリプション設定
	 */
	// メタデスクリプションを自動生成する.
	$result['ys_option_create_meta_description'] = get_option( 'ys_option_create_meta_description', 0 );
	// メタデスクリプションに使用する文字数.
	$result['ys_option_meta_description_length'] = get_option( 'ys_option_meta_description_length', 80 );
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
	// Google Analytics トラッキングID.
	$result['ys_ga_tracking_id'] = esc_attr( get_option( 'ys_ga_tracking_id', '' ) );
	// Google Analytics トラッキングコードタイプ.
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
	 * ランキング、カテゴリー・タグの記事一覧、関連記事のクエリ結果キャッシュ
	 */
	// 「[ys]人気ランキングウィジェット」の結果キャッシュ.
	$result['ys_query_cache_ranking'] = get_option( 'ys_query_cache_ranking', 'none' );
	// 「[ys]カテゴリー・タグの記事一覧」の結果キャッシュ.
	$result['ys_query_cache_taxonomy_posts'] = get_option( 'ys_query_cache_taxonomy_posts', 'none' );
	// 記事下エリア「関連記事」の結果キャッシュ.
	$result['ys_query_cache_related_posts'] = get_option( 'ys_query_cache_related_posts', 'none' );
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
	// jQueryをフッターで読み込む.
	$result['ys_load_jquery_in_footer'] = get_option( 'ys_load_jquery_in_footer', 0 );
	// CDNにホストされているjQueryを読み込む（URLを設定）.
	$result['ys_load_cdn_jquery_url'] = esc_url( get_option( 'ys_load_cdn_jquery_url', '' ) );
	// jQueryを読み込まない.
	$result['ys_not_load_jquery'] = get_option( 'ys_not_load_jquery', 0 );
	/**
	 * **********
	 * [ys]広告設定
	 * **********
	 */
	// 広告　タイトル上.
	$result['ys_advertisement_before_title'] = get_option( 'ys_advertisement_before_title', '' );
	// 広告　タイトル下.
	$result['ys_advertisement_after_title'] = get_option( 'ys_advertisement_after_title', '' );
	/**
	 * 記事本文上（旧 タイトル下）
	 * TODO: ys_advertisement_under_title の削除 v2.5.0くらいで
	 */
	/**
	 * 設定変換処理
	 */
	ys_change_option_key(
		'ys_advertisement_under_title',
		'',
		'ys_advertisement_before_content',
		''
	);
	$result['ys_advertisement_before_content'] = get_option( 'ys_advertisement_before_content', '' );
	// 広告　moreタグ置換.
	$result['ys_advertisement_replace_more'] = get_option( 'ys_advertisement_replace_more', '' );
	// 広告　記事下　左.
	$result['ys_advertisement_under_content_left'] = get_option( 'ys_advertisement_under_content_left', '' );
	// 広告　記事下　右.
	$result['ys_advertisement_under_content_right'] = get_option( 'ys_advertisement_under_content_right', '' );
	// 広告　タイトル上 SP.
	$result['ys_advertisement_before_title_sp'] = get_option( 'ys_advertisement_before_title_sp', '' );
	// 広告　タイトル下 SP.
	$result['ys_advertisement_after_title_sp'] = get_option( 'ys_advertisement_after_title_sp', '' );
	/**
	 * 記事本文上 SP（旧 タイトル下 SP）
	 * TODO: ys_advertisement_under_title_sp の削除 v2.5.0くらいで
	 */
	ys_change_option_key(
		'ys_advertisement_under_title_sp',
		'',
		'ys_advertisement_before_content_sp',
		''
	);
	$result['ys_advertisement_before_content_sp'] = get_option( 'ys_advertisement_before_content_sp', '' );
	// 広告　moreタグ置換 SP.
	$result['ys_advertisement_replace_more_sp'] = get_option( 'ys_advertisement_replace_more_sp', '' );
	// 広告　記事下 SP.
	$result['ys_advertisement_under_content_sp'] = get_option( 'ys_advertisement_under_content_sp', '' );
	// インフィード広告 PC.
	$result['ys_advertisement_infeed_pc'] = get_option( 'ys_advertisement_infeed_pc', '' );
	// インフィード広告 広告を表示する間隔 PC.
	$result['ys_advertisement_infeed_pc_step'] = get_option( 'ys_advertisement_infeed_pc_step', 3 );
	// インフィード広告 表示する広告の最大数 PC.
	$result['ys_advertisement_infeed_pc_limit'] = get_option( 'ys_advertisement_infeed_pc_limit', 3 );
	// インフィード広告 SP.
	$result['ys_advertisement_infeed_sp'] = get_option( 'ys_advertisement_infeed_sp', '' );
	// インフィード広告 広告を表示する間隔 SP.
	$result['ys_advertisement_infeed_sp_step'] = get_option( 'ys_advertisement_infeed_sp_step', 3 );
	// インフィード広告 表示する広告の最大数 SP.
	$result['ys_advertisement_infeed_sp_limit'] = get_option( 'ys_advertisement_infeed_sp_limit', 3 );
	/**
	 * **********
	 * [ys]AMP設定
	 * **********
	 */
	// AMPページを有効化するか.
	$result['ys_amp_enable'] = get_option( 'ys_amp_enable', 0 );
	// AMPのGoogle Analyticsトラッキングコード.
	$result['ys_ga_tracking_id_amp'] = esc_attr( get_option( 'ys_ga_tracking_id_amp', '' ) );
	// 記事上ウィジェットを出力する.
	$result['ys_show_amp_before_content_widget'] = get_option( 'ys_show_amp_before_content_widget', 0 );
	// 記事上ウィジェットの優先順位.
	$result['ys_amp_before_content_widget_priority'] = get_option( 'ys_amp_before_content_widget_priority', 10 );
	// 記事下ウィジェットを出力する.
	$result['ys_show_amp_after_content_widget'] = get_option( 'ys_show_amp_after_content_widget', 0 );
	// 記事下ウィジェットの優先順位.
	$result['ys_amp_after_content_widget_priority'] = get_option( 'ys_amp_after_content_widget_priority', 10 );
	// 広告　タイトル上 SP.
	$result['ys_amp_advertisement_before_title'] = get_option( 'ys_amp_advertisement_before_title', '' );
	// 広告　タイトル下 SP.
	$result['ys_amp_advertisement_after_title'] = get_option( 'ys_amp_advertisement_after_title', '' );
	/**
	 * 広告　記事本文上 SP（旧 タイトル下）
	 * TODO: ys_amp_advertisement_under_title の削除 v2.5.0くらいで
	 */
	ys_change_option_key(
		'ys_amp_advertisement_under_title',
		'',
		'ys_amp_advertisement_before_content',
		''
	);
	$result['ys_amp_advertisement_before_content'] = get_option( 'ys_amp_advertisement_before_content', '' );
	// 広告　moreタグ置換.
	$result['ys_amp_advertisement_replace_more'] = get_option( 'ys_amp_advertisement_replace_more', '' );
	// 広告　記事下.
	$result['ys_amp_advertisement_under_content'] = get_option( 'ys_amp_advertisement_under_content', '' );
	// アイキャッチ画像表示タイプ.
	/**
	 * Typo吸収
	 * TODO: 設定変更の削除 v2.5.0くらいで
	 */
	ys_change_option_key(
		'ys_amp_tumbnail_type',
		'full',
		'ys_amp_thumbnail_type',
		'full'
	);
	$result['ys_amp_thumbnail_type'] = get_option( 'ys_amp_thumbnail_type', 'full' );
	/**
	 * **********
	 * [ys]サイト運営支援
	 * **********
	 */
	// Gutenberg用CSSを追加する.
	$result['ys_admin_enable_block_editor_style'] = get_option( 'ys_admin_enable_block_editor_style', 1 );
	// ビジュアルエディタ用CSSを追加する.
	$result['ys_admin_enable_tiny_mce_style'] = get_option( 'ys_admin_enable_tiny_mce_style', 0 );

	return apply_filters( 'ys_get_options', $result );
}

/**
 * 設定取得
 *
 * @param string $name option key.
 *
 * @return mixed
 */
function ys_get_option( $name ) {
	$options = ys_get_options();
	$result  = '';
	if ( isset( $options[ $name ] ) ) {
		$result = $options[ $name ];
	}

	return apply_filters( 'ys_get_option', $result, $name );
}

/**
 * 発行年の取得
 */
function ys_get_copyright_year_option() {
	$copyright_year = esc_attr( get_option( 'ys_copyright_year', date_i18n( 'Y' ) ) );

	return '' == $copyright_year ? date_i18n( 'Y' ) : $copyright_year;
}

/**
 * 設定の変更処理
 *
 * @param string $old_key     旧設定.
 * @param mixed  $old_default 旧設定の初期値.
 * @param string $new_key     新設定.
 * @param mixed  $new_default 新設定の初期値.
 */
function ys_change_option_key( $old_key, $old_default, $new_key, $new_default ) {
	if ( get_option( $new_key, $new_default ) === $new_default ) {
		if ( get_option( $old_key, $old_default ) !== $old_default ) {
			update_option(
				$new_key,
				get_option( $old_key, $new_default )
			);
			delete_option( $old_key );
		}
	}
}