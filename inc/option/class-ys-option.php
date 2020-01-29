<?php
/**
 * 設定 クラス
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * Class YS_Option
 */
class YS_Option {

	/**
	 * YS_Option constructor.
	 */
	public function __construct() {
		add_action( 'customize_save_after', array( $this, 'cache_refresh' ) );
	}

	/**
	 * 設定取得
	 *
	 * @param string $name    option key.
	 * @param mixed  $default デフォルト値.
	 * @param mixed  $type    取得する型.
	 *
	 * @return mixed
	 */
	public static function get_option( $name, $default = false, $type = false ) {
		$result         = null;
		$option_default = null;
		/**
		 * 設定値のキャッシュ機能
		 */
		if ( 'none' !== get_option( 'ys_query_cache_ys_options' ) ) {
			global $ystandard_option;

			/**
			 * グローバルにセットされてない場合はキャッシュから取得 or リスト作成
			 */
			if ( ! is_array( $ystandard_option ) ) {
				$ystandard_option = YS_Cache::get_cache( 'ystandard_options', array() );
				if ( false === $ystandard_option ) {
					$ystandard_option = self::create_cache();
				}
			}
			/**
			 * 設定チェック
			 */
			if ( isset( $ystandard_option[ $name ] ) ) {
				$result = $ystandard_option[ $name ];
			}
		}

		/**
		 * 設定取得できなかった場合通常取得
		 */
		if ( null === $result ) {
			$option_default = self::get_default_option( $name, $default );
			$result         = get_option( $name, $option_default );
		}
		/**
		 * 指定のタイプで取得
		 */
		if ( false !== $type ) {
			switch ( $type ) {
				case 'bool':
				case 'boolean':
					$result = ys_to_bool( $result );
					break;
			}
		}

		return apply_filters( "ys_get_option_${name}", $result, $name, $option_default );
	}

	/**
	 * 設定取得(bool)
	 *
	 * @param string $name    option key.
	 * @param mixed  $default デフォルト値.
	 *
	 * @return mixed
	 */
	public static function get_option_by_bool( $name, $default = false ) {
		return self::get_option( $name, $default, 'bool' );
	}


	/**
	 * 設定デフォルト値取得
	 *
	 * @param string $name    設定名.
	 * @param mixed  $default デフォルト値.
	 *
	 * @return mixed
	 */
	public static function get_default_option( $name, $default = false ) {
		$defaults = self::get_defaults();
		/**
		 * 結果作成
		 */
		$result = $default;
		if ( isset( $defaults[ $name ] ) ) {
			$result = $defaults[ $name ];
		}

		return apply_filters( "ys_get_option_default_${name}", $result, $name, $defaults );
	}

	/**
	 * 設定リストの作成・取得とキャッシュ作成
	 *
	 * @return array
	 */
	public static function create_cache() {
		$options  = array();
		$defaults = self::get_defaults();
		/**
		 * 設定一覧の作成
		 */
		foreach ( $defaults as $key => $value ) {
			$options[] = get_option( $key, $value );
		}
		/**
		 * キャッシュの作成
		 */
		if ( 'none' !== get_option( 'ys_query_cache_ys_options' ) ) {
			$expiration = (int) get_option( 'ys_query_cache_ys_options' );
			$options    = YS_Cache::set_cache(
				'ystandard_options',
				$options,
				array(),
				$expiration
			);
		}

		return $options;
	}

	/**
	 * 設定初期値取得
	 *
	 * @return array
	 */
	public static function get_defaults() {

		return array(
			/**
			 * デザイン -> フォント
			 */
			'ys_design_font_type'                       => 'yugo', // フォントタイプ.
			/**
			 * デザイン -> ヘッダー
			 */
			'ys_design_header_type'                     => 'row1', // ヘッダータイプ.
			'ys_color_header_bg'                        => '#ffffff',
			'ys_color_header_font'                      => '#222222',
			'ys_color_header_dscr_font'                 => '#757575',
			'ys_header_fixed'                           => 0, // 固定ヘッダー.
			'ys_header_fixed_height_pc'                 => 0, // ヘッダー高さ(PC).
			'ys_header_fixed_height_tablet'             => 0, // ヘッダー高さ(PC).
			'ys_header_fixed_height_mobile'             => 0, // ヘッダー高さ(PC).
			/**
			 * 色設定
			 */
			'ys_color_site_bg'                          => '#ffffff',
			'ys_color_nav_bg_sp'                        => '#000000',
			'ys_color_nav_font_sp'                      => '#ffffff',
			'ys_color_nav_btn_sp_open'                  => '#222222',
			'ys_color_nav_btn_sp'                       => '#ffffff',
			'ys_color_footer_bg'                        => '#222222',
			'ys_color_footer_font'                      => '#ffffff',
			// カラーパレット.
			'ys-color-palette-ys-blue'                  => '#82B9E3',
			'ys-color-palette-ys-light-blue'            => '#77C7E4',
			'ys-color-palette-ys-red'                   => '#D53939',
			'ys-color-palette-ys-pink'                  => '#E28DA9',
			'ys-color-palette-ys-green'                 => '#92C892',
			'ys-color-palette-ys-yellow'                => '#F5EC84',
			'ys-color-palette-ys-orange'                => '#EB962D',
			'ys-color-palette-ys-purple'                => '#B67AC2',
			'ys-color-palette-ys-gray'                  => '#757575',
			'ys-color-palette-ys-light-gray'            => '#F1F1F3',
			'ys-color-palette-ys-black'                 => '#000000',
			'ys-color-palette-ys-white'                 => '#ffffff',
			'ys-color-palette-ys-user-1'                => '#ffffff',
			'ys-color-palette-ys-user-2'                => '#ffffff',
			'ys-color-palette-ys-user-3'                => '#ffffff',
			/**
			 * 基本設定
			 */
			'ys_logo_hidden'                            => 0, // ロゴを出力しない.
			'ys_wp_hidden_blogdescription'              => 0, // キャッチフレーズを出力しない.
			'ys_wp_site_description'                    => '', // TOPページのmeta description.
			/**
			 * 色 設定
			 */
			'ys_desabled_color_customizeser'            => 0, // テーマカスタマイザーの色設定を無効にする.
			/**
			 * ヘッダーメディア.
			 */
			'ys_wp_header_media_shortcode'              => '', // ヘッダーメディア用ショートコード.
			'ys_wp_header_media_full'                   => 0, // 画像・動画の全面表示.
			'ys_wp_header_media_full_type'              => 'dark', // 画像・動画の全面表示 表示タイプ.
			'ys_wp_header_media_full_opacity'           => 50, // 画像・動画の全面表示 ヘッダー不透明度.
			'ys_wp_header_media_all_page'               => 0, // カスタムヘッダーの全ページ表示.
			// [ys]サイト共通設定.
			'ys_title_separate'                         => '', // タイトルの区切り文字.
			'ys_copyright_year'                         => date_i18n( 'Y' ), // 発行年.
			'ys_option_excerpt_length'                  => 110, // 抜粋文字数.
			// [ys]デザイン設定.
			'ys_show_sidebar_mobile'                    => 0, // モバイル表示でサイドバーを出力しない.
			'ys_show_search_form_on_slide_menu'         => 0, // スライドメニューに検索フォームを出力する.
			'ys_enqueue_icon_font_type'                 => 'js', // アイコンフォント（Font Awesome）読み込み方式.
			'ys_enqueue_icon_font_kit_url'              => '', // Font Awesome Kit URL.
			// [ys]投稿ページ設定.
			'ys_post_layout'                            => '2col', // 表示レウアウト.
			'ys_show_post_thumbnail'                    => 1, // 個別ページでアイキャッチ画像を表示する.
			'ys_show_post_publish_date'                 => 1, // 個別ページで投稿日・更新日を表示する.
			'ys_show_post_category'                     => 1, // カテゴリー・タグ情報を表示する.
			'ys_show_post_follow_box'                   => 1, // ブログフォローボックスを表示する.
			'ys_show_post_author'                       => 1, // 著者情報を表示する.
			'ys_show_post_related'                      => 1, // 関連記事を出力する.
			'ys_show_post_paging'                       => 1, // 次の記事・前の記事を表示する.
			'ys_show_post_before_content_widget'        => 0, // 記事上ウィジェットを出力する.
			'ys_post_before_content_widget_priority'    => 10, // 記事上ウィジェットの優先順位.
			'ys_show_post_after_content_widget'         => 0, // 記事下ウィジェットを出力する.
			'ys_post_after_content_widget_priority'     => 10, // 記事下ウィジェットの優先順位.
			// [ys]固定ページ設定.
			'ys_page_layout'                            => '2col', // 表示レウアウト.
			'ys_show_page_thumbnail'                    => 1, // 個別ページでアイキャッチ画像を表示する.
			'ys_show_page_publish_date'                 => 1, // 個別ページで投稿日時を表示する.
			'ys_show_page_follow_box'                   => 1, // ブログフォローボックスを表示する.
			'ys_show_page_author'                       => 1, // 著者情報を表示する.
			'ys_show_page_before_content_widget'        => 0, // 記事上ウィジェットを出力する.
			'ys_page_before_content_widget_priority'    => 10, // 記事上ウィジェットの優先順位.
			'ys_show_page_after_content_widget'         => 0, // 記事下ウィジェットを出力する.
			'ys_page_after_content_widget_priority'     => 10, // 記事下ウィジェットの優先順位.
			// [ys]アーカイブページ設定.
			'ys_archive_layout'                         => '2col', // 表示レウアウト.
			'ys_archive_type'                           => 'list', // 一覧表示タイプ.
			'ys_show_archive_publish_date'              => 1, // 投稿日を表示する.
			'ys_show_archive_author'                    => 1, // 著者情報を表示する.
			'ys_show_page_for_posts_on_breadcrumbs'     => 1, // パンくずリストの「投稿ページ」表示.
			// [ys]ワンカラムテンプレート設定.
			'ys_design_one_col_thumbnail_type'          => 'normal', // アイキャッチ画像表示タイプ.
			'ys_design_one_col_content_type'            => 'normal', // コンテンツタイプ normal,wide.
			// [ys]フロントページ設定.
			'ys_front_page_layout'                      => '1col', // 表示レイアウト.
			'ys_front_page_type'                        => 'normal', // フロントページ作成タイプ.
			// [ys]SNS設定.
			// OGP.
			'ys_ogp_enable'                             => 1, // OGPメタタグを出力する.
			'ys_ogp_fb_app_id'                          => '', // Facebook app id.
			'ys_ogp_fb_admins'                          => '', // facebook admins.
			'ys_ogp_default_image'                      => '', // OGPデフォルト画像.
			// Twitterカード.
			'ys_twittercard_enable'                     => 1, // Twitterカードを出力する.
			'ys_twittercard_user'                       => '', // // Twitterカードのユーザー名.
			'ys_twittercard_type'                       => 'summary_large_image', // Twitterカード タイプ.
			// SNSシェアボタン設定.
			'ys_sns_share_button_twitter'               => 1, // Twitter.
			'ys_sns_share_button_facebook'              => 1, // Facebook.
			'ys_sns_share_button_hatenabookmark'        => 1, // はてブ.
			'ys_sns_share_button_pocket'                => 1, // Pocket.
			'ys_sns_share_button_line'                  => 1, // LINE.
			'ys_sns_share_button_feedly'                => 1, // Feedly.
			'ys_sns_share_button_rss'                   => 1, // RSS.
			'ys_sns_share_on_entry_header'              => 1, // シェアボタンを投稿上部に表示する.
			'ys_sns_share_on_below_entry'               => 1, // シェアボタンを投稿下に表示する.
			'ys_sns_share_col_pc'                       => 6, // PCでの列数.
			'ys_sns_share_col_tablet'                   => 3, // タブレットでの列数.
			'ys_sns_share_col_sp'                       => 3, // スマホでの列数.
			// Twitterシェアボタン.
			'ys_sns_share_tweet_via_account'            => '', // Twitter via アカウント.
			'ys_sns_share_tweet_related_account'        => '', // Twitter related アカウント.
			// 購読ボタン設定.
			'ys_subscribe_url_twitter'                  => '', // Twitterフォローリンク.
			'ys_subscribe_url_facebook'                 => '', // Facebookページフォローリンク.
			'ys_subscribe_url_feedly'                   => '', // Feedlyフォローリンク.
			'ys_subscribe_col_sp'                       => '2', // SP表示列数.
			'ys_subscribe_col_pc'                       => '4', // PC表示列数.
			// フッターSNSフォローリンク.
			'ys_follow_url_twitter'                     => '', // TwitterフォローURL.
			'ys_follow_url_facebook'                    => '', // facebookフォローURL.
			'ys_follow_url_instagram'                   => '', // instagramフォローURL.
			'ys_follow_url_tumblr'                      => '', // tumblrフォローURL.
			'ys_follow_url_youtube'                     => '', // YouTubeフォローURL.
			'ys_follow_url_github'                      => '', // GitHubフォローURL.
			'ys_follow_url_pinterest'                   => '', // PinterestフォローURL.
			'ys_follow_url_linkedin'                    => '', // linkedinフォローURL.
			// SNS用JavaScriptの読み込み.
			'ys_load_script_twitter'                    => 0, // Twitter埋め込み用js読み込み.
			'ys_load_script_facebook'                   => 0, // Facebook埋め込み用js読み込み.
			// [ys]SEO設定.
			// メタデスクリプション設定.
			'ys_option_create_meta_description'         => 1, // メタデスクリプションを自動生成する.
			'ys_option_meta_description_length'         => 80, // メタデスクリプションに使用する文字数.
			// アーカイブページのnoindex設定.
			'ys_archive_noindex_category'               => 0,  // カテゴリー一覧をnoindexにする.
			'ys_archive_noindex_tag'                    => 1,  // タグ一覧をnoindexにする.
			'ys_archive_noindex_author'                 => 1,  // 投稿者一覧をnoindexにする.
			'ys_archive_noindex_date'                   => 1,  // 日別一覧をnoindexにする.
			// Google Analytics.
			'ys_ga_tracking_id'                         => '', // Google Analytics トラッキングID.
			'ys_ga_tracking_type'                       => 'gtag', // Google Analytics トラッキングコードタイプ.
			'ys_ga_exclude_logged_in_user'              => 0, // ログイン中はアクセス数をカウントしない.
			// 構造化データ設定.
			'ys_option_structured_data_publisher_image' => '', // パブリッシャー画像.
			'ys_option_structured_data_publisher_name'  => '', // パブリッシャー名.
			// [ys]サイト高速化設定.
			// キャッシュ設定.
			'ys_query_cache_ranking'                    => 'none', // 「[ys]人気ランキングウィジェット」の結果キャッシュ.
			'ys_query_cache_recent_posts'               => 'none', // 「[ys]新着記事一覧」の結果キャッシュ.
			'ys_query_cache_related_posts'              => 'none', // 記事下エリア「関連記事」の結果キャッシュ.
			'ys_query_cache_ys_options'                 => 'none', // テーマ設定のキャッシュ.
			// WordPress標準機能で読み込むCSS・JavaScriptの無効化.
			'ys_option_disable_wp_emoji'                => 1, // 絵文字を出力しない.
			'ys_option_disable_wp_oembed'               => 1, // oembedを出力しない.
			// CSS読み込みの最適化.
			'ys_option_optimize_load_css'               => 0, // CSSの読み込みを最適化する.
			// JavaScript読み込みの最適化.
			'ys_option_optimize_load_js'                => 0, // JavaScriptの読み込みを非同期化する.
			'ys_load_jquery_in_footer'                  => 0, // jQueryをフッターで読み込む.
			'ys_load_cdn_jquery_url'                    => '', // CDNにホストされているjQueryを読み込む（URLを設定）.
			'ys_not_load_jquery'                        => 0, // jQueryを読み込まない.
			// [ys]広告設定.
			'ys_advertisement_ads_label'                => 'スポンサーリンク', // 広告ラベル.
			'ys_advertisement_before_title'             => '', // 広告　タイトル上.
			'ys_advertisement_after_title'              => '', // 広告　タイトル下.
			'ys_advertisement_before_content'           => '', // 記事本文上（旧 タイトル下）.
			'ys_advertisement_replace_more'             => '', // 広告　moreタグ置換.
			'ys_advertisement_under_content_left'       => '', // 広告　記事下　左.
			'ys_advertisement_under_content_right'      => '', // 広告　記事下　右.
			'ys_advertisement_before_title_sp'          => '', // 広告　タイトル上 SP.
			'ys_advertisement_after_title_sp'           => '', // 広告　タイトル下 SP.
			'ys_advertisement_before_content_sp'        => '', // 記事本文上 SP（旧 タイトル下 SP）.
			'ys_advertisement_replace_more_sp'          => '', // 広告　moreタグ置換 SP.
			'ys_advertisement_under_content_sp'         => '', // 広告　記事下 SP.
			'ys_advertisement_infeed_pc'                => '', // インフィード広告 PC.
			'ys_advertisement_infeed_pc_step'           => 3, // インフィード広告 広告を表示する間隔 PC.
			'ys_advertisement_infeed_pc_limit'          => 3, // インフィード広告 表示する広告の最大数 PC.
			'ys_advertisement_infeed_sp'                => '', // インフィード広告 SP.
			'ys_advertisement_infeed_sp_step'           => 3, // インフィード広告 広告を表示する間隔 SP.
			'ys_advertisement_infeed_sp_limit'          => 3, // インフィード広告 表示する広告の最大数 SP.
			// [ys]AMP設定.
			'ys_amp_enable'                             => 0, // AMPページを有効化するか.
			'ys_amp_enable_amp_plugin_integration'      => 0, // AMPプラグイン連携を有効化するか.
			'ys_ga_tracking_id_amp'                     => '', // AMPのGoogle Analyticsトラッキングコード.
			'ys_show_amp_before_content_widget'         => 0, // 記事上ウィジェットを出力する.
			'ys_amp_before_content_widget_priority'     => 10, // 記事上ウィジェットの優先順位.
			'ys_show_amp_after_content_widget'          => 0, // 記事下ウィジェットを出力する.
			'ys_amp_after_content_widget_priority'      => 10, // 記事下ウィジェットの優先順位.
			'ys_amp_advertisement_before_title'         => '', // 広告　タイトル上 SP.
			'ys_amp_advertisement_after_title'          => '', // 広告　タイトル下 SP.
			'ys_amp_advertisement_before_content'       => '', // 広告　記事本文上 SP（旧 タイトル下）.
			'ys_amp_advertisement_replace_more'         => '', // 広告　moreタグ置換.
			'ys_amp_advertisement_under_content'        => '', // 広告　記事下.
			'ys_amp_thumbnail_type'                     => 'full', // アイキャッチ画像表示タイプ.
			// [ys]サイト運営支援.
			'ys_admin_enable_block_editor_style'        => 1, // Gutenberg用CSSを追加する.
			'ys_admin_enable_tiny_mce_style'            => 0, // ビジュアルエディタ用CSSを追加する.
			// 削除予定
			// ブロックエディター設定.
			'ys_enqueue_gutenberg_css'                  => 0, // ブロックエディター対応のCSSを読み込む.
		);
	}

	/**
	 * 設定キャッシュの再作成
	 */
	public function cache_refresh() {
		if ( 'none' !== get_option( 'ys_query_cache_ys_options' ) ) {
			ys_get_options_and_create_cache();
		} else {
			YS_Cache::delete_cache( 'ystandard_options', array() );
		}
	}

	/**
	 * 設定の変更処理
	 *
	 * @param string $old_key     旧設定.
	 * @param mixed  $old_default 旧設定の初期値.
	 * @param string $new_key     新設定.
	 * @param mixed  $new_default 新設定の初期値.
	 */
	private function change_option_key( $old_key, $old_default, $new_key, $new_default ) {
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
}