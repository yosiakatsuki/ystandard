<?php
/**
 * 最適化
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

/**
 * Class Optimization
 *
 * @package ystandard
 */
class Optimization {

	/**
	 * Optimisation constructor.
	 */
	public function __construct() {
		add_action( 'customize_register', [ $this, 'customize_register' ] );
		add_action( 'after_setup_theme', [ $this, 'optimize_emoji' ] );
		add_action( 'after_setup_theme', [ $this, 'optimize_oembed' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'disable_jquery' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'defer_jquery' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'jquery_in_footer' ] );
		add_action( 'init', [ $this, 'use_jquery_cdn' ] );
	}

	/**
	 * CDNのjQueryを使用
	 */
	public function use_jquery_cdn() {
		if ( ! $this->can_optimize_jquery() ) {
			return;
		}
		if ( '' === Option::get_option( 'ys_load_cdn_jquery_url', '' ) ) {
			return;
		}
		wp_deregister_script( 'jquery' );
		wp_deregister_script( 'jquery-core' );
		wp_register_script(
			'jquery-core',
			Option::get_option( 'ys_load_cdn_jquery_url', '' ),
			[],
			Utility::get_ystandard_version()
		);
		wp_register_script(
			'jquery',
			false,
			[ 'jquery-core' ],
			Utility::get_ystandard_version()
		);
	}

	/**
	 * [jQuery]をフッターで読み込む
	 */
	public function jquery_in_footer() {
		if ( ! $this->can_optimize_jquery() ) {
			return;
		}
		if ( ! Option::get_option_by_bool( 'ys_load_jquery_in_footer', false ) ) {
			return;
		}
		wp_script_add_data( 'jquery', 'group', 1 );
		wp_script_add_data( 'jquery-core', 'group', 1 );
	}

	/**
	 * [jQuery]にDefer追加
	 */
	public function defer_jquery() {
		if ( ! $this->can_optimize_jquery() ) {
			return;
		}
		if ( ! Option::get_option_by_bool( 'ys_option_optimize_load_js', false ) ) {
			return;
		}
		Enqueue_Utility::add_defer( 'jquery' );
		Enqueue_Utility::add_defer( 'jquery-core' );
	}

	/**
	 * [jQuery]削除
	 */
	public function disable_jquery() {
		if ( ! $this->can_optimize_jquery() ) {
			return;
		}
		if ( ! Option::get_option_by_bool( 'ys_not_load_jquery', false ) ) {
			return;
		}
		wp_deregister_script( 'jquery' );
		wp_deregister_script( 'jquery-core' );
		wp_dequeue_script( 'jquery' );
		wp_dequeue_script( 'jquery-core' );
	}

	/**
	 * Embed関連の調整
	 */
	public function optimize_oembed() {
		if ( ! Option::get_option_by_bool( 'ys_option_disable_wp_oembed', true ) ) {
			return;
		}
		if ( is_admin() ) {
			return;
		}
		add_filter( 'embed_oembed_discover', '__return_false' );
		remove_action( 'wp_head', 'rest_output_link_wp_head' );
		remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
		remove_action( 'wp_head', 'wp_oembed_add_host_js' );
	}

	/**
	 * 絵文字の調整
	 */
	public function optimize_emoji() {
		if ( ! ys_get_option_by_bool( 'ys_option_disable_wp_emoji', true ) ) {
			return;
		}
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		add_action( 'wp_footer', 'print_emoji_detection_script' );
	}

	/**
	 * [jQuery]操作してできるか
	 *
	 * @return bool
	 */
	private function can_optimize_jquery() {
		$login_page = in_array( $GLOBALS['pagenow'], [ 'wp-login.php', 'wp-register.php' ], true );

		return ( ! is_admin() && ! $login_page && ! is_customize_preview() );
	}


	/**
	 * カスタマイザー追加
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 */
	public function customize_register( $wp_customize ) {
		$customizer = new Customize_Control( $wp_customize );

		$customizer->add_panel(
			[
				'panel'       => 'ys_performance_tuning',
				'title'       => '[ys]高速化',
				'description' => 'サイト高速化に関する設定',
			]
		);
		// キャッシュ.
		$this->add_cache_section( $customizer );
		// 絵文字.
		$this->add_emoji_section( $customizer );
		// embed.
		$this->add_oembed_section( $customizer );
		// css.
		$this->add_css_section( $customizer );
		// JavaScript.
		$this->add_javascript_section( $customizer );
	}

	/**
	 * キャッシュ設定追加
	 *
	 * @param Customize_Control $customizer customize control.
	 */
	private function add_cache_section( $customizer ) {
		/**
		 * キャッシュ
		 */
		$customizer->add_section(
			[
				'section'     => 'ys_cache_query',
				'title'       => 'キャッシュ',
				'description' => '記事一覧作成結果のキャッシュ機能設定',
			]
		);
		// 新着記事一覧.
		$customizer->add_radio(
			[
				'id'          => 'ys_query_cache_recent_posts',
				'default'     => 'none',
				'transport'   => 'postMessage',
				'label'       => '新着記事一覧の結果キャッシュ',
				'description' => '「[ys]新着記事一覧ウィジェット」・新着記事一覧ショートコードの結果をキャッシュしてサーバー処理の高速化を図ります。<br>キャッシュする日数を選択して下さい。',
				'choices'     => [
					'none' => 'キャッシュしない',
					'1'    => '1日',
					'7'    => '7日',
					'30'   => '30日',
				],
			]
		);
		// 関連記事.
		$customizer->add_radio(
			[
				'id'          => 'ys_query_cache_related_posts',
				'default'     => 'none',
				'transport'   => 'postMessage',
				'label'       => '記事下エリア「関連記事」の結果キャッシュ',
				'description' => '記事下エリア「関連記事」の結果をキャッシュしてサーバー処理の高速化を図ります。<br>キャッシュする日数を選択して下さい。',
				'choices'     => [
					'none' => 'キャッシュしない',
					'1'    => '1日',
					'7'    => '7日',
					'30'   => '30日',
				],
			]
		);
	}

	/**
	 * 絵文字設定追加
	 *
	 * @param Customize_Control $customizer customize control.
	 */
	private function add_emoji_section( $customizer ) {
		/**
		 * 絵文字
		 */
		$customizer->add_section(
			[
				'section'     => 'ys_optimize_emoji',
				'title'       => '絵文字',
				'description' => '絵文字関連のCSS・JavaScript設定',
			]
		);
		$customizer->add_checkbox(
			[
				'id'          => 'ys_option_disable_wp_emoji',
				'default'     => 1,
				'label'       => '絵文字関連のCSS・JSの出力を最適化する',
				'description' => 'ページ表示時に絵文字の表示が少し遅れると感じる場合はこの設定をOFFにしてください。',
			]
		);
	}

	/**
	 * 埋め込み設定追加
	 *
	 * @param Customize_Control $customizer customize control.
	 */
	private function add_oembed_section( $customizer ) {
		/**
		 * Embed
		 */
		$customizer->add_section(
			[
				'section'     => 'ys_optimize_oembed',
				'title'       => 'oEmbed',
				'description' => 'oEmbedによる埋め込みの設定',
			]
		);
		$customizer->add_checkbox(
			[
				'id'          => 'ys_option_disable_wp_oembed',
				'default'     => 1,
				'label'       => 'oEmbedによる埋め込みを無効にする',
				'description' => 'oEmbedの埋め込みリンクを有効にしたい場合はこの設定をOFFにしてください。',
			]
		);
	}

	/**
	 * CSS設定追加
	 *
	 * @param Customize_Control $customizer customize control.
	 */
	private function add_css_section( $customizer ) {
		/**
		 * CSSの読み込みを最適化
		 */
		$customizer->add_section(
			[
				'section' => 'ys_optimize_css',
				'title'   => 'CSSインライン読み込み（上級者向け）',
			]
		);

		$customizer->add_checkbox(
			[
				'id'          => 'ys_option_optimize_load_css',
				'default'     => 0,
				'label'       => 'CSSをインラインで読み込む',
				'description' => 'この設定をONにすると、yStandard関連のCSSが全てhead内にインラインで読み込まれます。',
			]
		);
	}

	/**
	 * JavaScript設定追加
	 *
	 * @param Customize_Control $customizer customize control.
	 */
	private function add_javascript_section( $customizer ) {
		/**
		 * JavaScript読み込みの最適化
		 */
		$customizer->add_section(
			[
				'section' => 'ys_optimize_load_js',
				'title'   => 'JavaScript・jQueryの読み込み設定（上級者向け）',
			]
		);
		// JavaScriptの読み込みを非同期化する.
		$customizer->add_label(
			[
				'id'    => 'ys_optimize_load_js_label',
				'label' => 'JavaScriptの読み込みを非同期化する',
			]
		);
		$customizer->add_checkbox(
			[
				'id'          => 'ys_option_optimize_load_js',
				'default'     => 0,
				'label'       => 'JavaScriptの読み込みを非同期化する',
				'description' => 'この設定をONにすると、jQuery以外のJavaScriptの読み込みを非同期化します。',
			]
		);
		// [jQueryをフッターで読み込む].
		$customizer->add_label(
			[
				'id'    => 'ys_load_jquery_in_footer_label',
				'label' => 'jQueryの読み込みを最適化する',
			]
		);
		$customizer->add_checkbox(
			[
				'id'          => 'ys_load_jquery_in_footer',
				'default'     => 0,
				'label'       => 'jQueryの読み込みを最適化する',
				'description' => 'jQueryをフッターで読み込み、サイトの高速化を図ります。<br>※この設定を有効にすると利用しているプラグインの動作が不安定になる恐れがあります。<br>プラグインの機能が正常に動作しなくなる場合は設定を無効化してください。',
			]
		);
		// CDNにホストされているjQueryを読み込む.
		$customizer->add_url(
			[
				'id'          => 'ys_load_cdn_jquery_url',
				'default'     => '',
				'transport'   => 'postMessage',
				'label'       => 'CDN経由でjQueryを読み込む',
				'description' => '※WordPress標準のjQueryを読み込む場合は空白にしてください（デフォルト）<br>※ホストされているjQueryのURLを入力してください。',
			]
		);
		// [jQueryを無効化する].
		$customizer->add_label(
			[
				'id'    => 'ys_not_load_jquery_label',
				'label' => 'jQueryを無効化する',
			]
		);
		$customizer->add_checkbox(
			[
				'id'          => 'ys_not_load_jquery',
				'default'     => 0,
				'transport'   => 'postMessage',
				'label'       => 'jQueryを無効化する',
				'description' => '※この設定を有効にするとサイト表示高速化が期待できますが、jQueryを使用している処理が動かなくなります。<br>※プラグインの動作に影響が出る恐れがありますのでご注意ください。<br>※yStandard内のJavaScriptではjQueryを使用する機能は使っていません',
			]
		);
	}

}

new Optimization();
