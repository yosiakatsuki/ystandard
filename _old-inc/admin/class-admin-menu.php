<?php
/**
 * テーマオプションページ
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

/**
 * Class Admin_Menu
 *
 * @package ystandard
 */
class Admin_Menu {

	/**
	 * Nonce Action.
	 */
	const NONCE_ACTION = 'ystandard_delete_cache';
	/**
	 * Nonce Name.
	 */
	const NONCE_NAME = 'ystandard_delete_cache_nonce';

	/**
	 * Admin_Menu constructor.
	 */
	public function __construct() {
		add_action( 'admin_menu', [ $this, 'add_admin_menu' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ], 11 );
		add_filter( 'admin_body_class', [ $this, 'admin_body_class' ] );
	}

	/**
	 * テーマオプションページの追加
	 */
	public function add_admin_menu() {
		if ( apply_filters( 'disable_ystandard_admin_menu', false ) ) {
			return;
		}
		/**
		 * [yStandardメニューの追加]
		 */
		add_menu_page(
			'yStandard',
			'yStandard',
			'manage_options',
			'ystandard-start-page',
			[ $this, 'start_page' ],
			self::get_menu_icon(),
			3
		);

		add_submenu_page(
			'ystandard-start-page',
			'アイコン',
			'アイコン',
			'manage_options',
			'ystandard-icons',
			[ $this, 'icons_page' ]
		);

		if ( $this->is_enable_cache() ) {
			/**
			 * キャッシュメニューの追加
			 */
			add_submenu_page(
				'ystandard-start-page',
				'キャッシュ管理',
				'キャッシュ管理',
				'manage_options',
				'ystandard-cache',
				[ $this, 'cache_page' ]
			);
		}
	}

	/**
	 * Admin Body Class.
	 *
	 * @param string $classes Classes.
	 *
	 * @return string
	 */
	public function admin_body_class( $classes ) {
		global $hook_suffix;
		if ( isset( $hook_suffix ) ) {
			if ( false !== strpos( $hook_suffix, 'ystandard-start-page' ) || false !== strpos( $hook_suffix, 'ystandard_page' ) ) {
				$classes .= ' ystandard-option';
			}
		}

		return $classes;
	}

	/**
	 * メニューアイコン取得
	 *
	 * @return string
	 */
	public static function get_menu_icon() {
		return 'data:image/svg+xml;base64,' . base64_encode( '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40"><g id="ys-menu-icon" data-name="ys-menu-icon"><g><path d="M18.37,22.44v8.69a3.5,3.5,0,0,1-3.51,3.5H5.2V31.72h9.66a.58.58,0,0,0,.42-.18.57.57,0,0,0,.17-.41v-2.7H6a3.35,3.35,0,0,1-2.48-1,3.35,3.35,0,0,1-1-2.48V12.84H5.36V24.92a.62.62,0,0,0,.6.6h8.9a.58.58,0,0,0,.42-.18.61.61,0,0,0,.17-.42V12.84h2.92Z" fill="#e1e1e1"/><path d="M37,16.29v.59H34.09v-.59a.57.57,0,0,0-.17-.42.61.61,0,0,0-.42-.17H24.6a.61.61,0,0,0-.42.17.58.58,0,0,0-.18.42v2.27a.54.54,0,0,0,.18.41.58.58,0,0,0,.42.18h8.9A3.5,3.5,0,0,1,37,22.66v2.26a3.52,3.52,0,0,1-3.51,3.51H24.6a3.52,3.52,0,0,1-3.51-3.51v-.59H24v.59a.62.62,0,0,0,.6.6h8.9a.58.58,0,0,0,.42-.18.57.57,0,0,0,.17-.42V22.66a.57.57,0,0,0-.17-.42.58.58,0,0,0-.42-.18H24.6a3.5,3.5,0,0,1-3.51-3.5V16.29a3.35,3.35,0,0,1,1-2.48,3.39,3.39,0,0,1,2.48-1h8.9a3.39,3.39,0,0,1,2.48,1A3.35,3.35,0,0,1,37,16.29Z" fill="#e1e1e1"/></g></g></svg>' );
	}

	/**
	 * 管理画面-JavaScriptの読み込み
	 *
	 * @param string $hook_suffix suffix.
	 *
	 * @return void
	 */
	public function admin_enqueue_scripts( $hook_suffix ) {
		// アイコン検索用.
		if ( 'ystandard_page_ystandard-icons' === $hook_suffix ) {
			wp_enqueue_script(
				'search-icons',
				get_template_directory_uri() . '/js/admin/search-icons.js',
				[],
				filemtime( get_template_directory() . '/js/admin/search-icons.js' ),
				true
			);
		}
	}

	/**
	 * スタートページ
	 */
	public function start_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		?>
		<div class="wrap ys-option-page">
			<h2><span class="orbitron">yStandard</span>を始めよう！</h2>
			<div class="ys-option__section">
				<div class="ys-contents">
					<div class="ys-contents__item">
						<div class="ys-contents__icon">
							<?php echo Icon::get_icon( 'book' ); ?>
						</div>
						<div class="ys-contents__text">
							<h3>マニュアル</h3>
							<p class="ys-contents__detail">
								yStandardの設定や使い方の公式マニュアルページです。<br>
								テーマをインストールしたら最初にやっておきたい設定など、様々なマニュアルを用意しています。
							</p>
							<p class="wp-block-button">
								<a class="wp-block-button__link" href="https://wp-ystandard.com/category/manual/" target="_blank" rel="noopener noreferrer nofollow">マニュアルを見る <?php echo Icon::get_icon( 'arrow-right-circle' ); ?></a>
							</p>
						</div>
					</div>

					<div class="ys-contents__item">
						<div class="ys-contents__icon">
							<?php echo Icon::get_icon( 'settings' ); ?>
						</div>
						<div class="ys-contents__text">
							<h3>テーマの設定</h3>
							<p class="ys-contents__detail">
								テーマカスタマイザーを開いてテーマの設定を始めましょう！<br>
								<small>※「外観」→「カスタマイズ」からも設定画面を開けます。</small>
							</p>
							<p class="wp-block-button">
								<a class="wp-block-button__link" href="<?php echo esc_url_raw( add_query_arg( 'return', rawurlencode( Utility::get_page_url() ), wp_customize_url() ) ); ?>" rel="noopener noreferrer nofollow">設定を始める <?php echo Icon::get_icon( 'arrow-right-circle' ); ?></a>
							</p>
						</div>
					</div>

					<div class="ys-contents__item">
						<div class="ys-contents__icon">
							<?php echo Icon::get_icon( 'tool' ); ?>
						</div>
						<div class="ys-contents__text">
							<h3>拡張プラグイン</h3>
							<p class="ys-contents__detail">
								yStandardの機能を更に強化する拡張プラグイン。<br>
								見出しスタイルのカスタマイズ機能やページ先頭の画像や動画の上にヘッダーメニューを重ねて表示できる機能など、コードを書かなくてもキレイなWebサイト・ブログを作れる機能を詰め込んだプラグインです。
							</p>
							<p class="wp-block-button">
								<a class="wp-block-button__link" href="https://wp-ystandard.com/plugins/" target="_blank" rel="noopener noreferrer nofollow">拡張プラグインを見る <?php echo Icon::get_icon( 'arrow-right-circle' ); ?></a>
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * アイコン コピーページ
	 */
	public function icons_page() {

		$icons = $this->get_icon_search_data();
		?>
		<div class="wrap ys-option-page">
			<h2>アイコン ショートコード一覧</h2>
			<?php echo Admin::manual_link( 'manual/icon-search' ); ?>
			<div class="ys-option__section">
				<p>ショートコードをコピーしてサイト内でご使用ください。</p>
				<div id="ys-search-icons">
					<p class="ys-search-icons__search">
						<label for="icon-search">検索：</label><input id="icon-search" name="icon-search" type="search" class="">
					</p>
					<div class="ys-icon-search__list">
						<?php foreach ( $icons as $icon ) : ?>
							<div class="ys-icon-search__item" data-icon-name="<?php echo esc_attr( $icon['name'] ); ?>">
								<div class="ys-icon-search__icon"><?php echo $icon['svg']; ?></div>
								<label for="icon-shortcode--<?php echo esc_attr( $icon['name'] ); ?>" class="ys-icon-search__label"><?php echo esc_html( $icon['label'] ); ?></label>
								<div class="copy-form">
									<input type="text" id="icon-shortcode--<?php echo esc_attr( $icon['name'] ); ?>" class="copy-form__target" value="<?php echo esc_attr( $icon['short_code'] ); ?>" readonly onfocus="this.select();"/>
									<button class="copy-form__button button action">
										<?php echo ys_get_icon( 'clipboard' ); ?>
									</button>
									<div class="copy-form__info">コピーしました！</div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * アイコンデータ取得
	 *
	 * @return array
	 */
	private function get_icon_search_data() {
		$icons = [];
		// feather.
		$icon_dir = get_template_directory() . '/library/feather';
		$files    = glob( $icon_dir . '/*.svg' );
		foreach ( $files as $file ) {
			$icon_name = str_replace(
				[
					$icon_dir . '/',
					'.svg',
				],
				'',
				$file
			);
			$icons[]   = [
				'name'       => $icon_name,
				'label'      => $icon_name,
				'short_code' => '[ys_icon name="' . $icon_name . '"]',
				'svg'        => Icon::get_icon( $icon_name ),
			];
		}
		// sns.
		$sns_icons = Icon::get_all_sns_icons();
		foreach ( $sns_icons as $key => $value ) {
			$icons[] = [
				'name'       => 'sns_' . $key,
				'label'      => $value['title'],
				'short_code' => '[ys_sns_icon name="' . $key . '"]',
				'svg'        => Icon::get_sns_icon( $key ),
			];
		}

		return $icons;
	}

	/**
	 * キャッシュ管理ページ
	 */
	public function cache_page() {

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		$result = $this->delete_cache();
		?>
		<div class="wrap">
			<h2>キャッシュ管理</h2>
			<?php echo Admin::manual_link( 'manual/cache-delete' ); ?>
			<?php if ( $result ) : ?>
				<div class="notice notice-success is-dismissible">
					<p><?php echo $result; ?></p>
				</div>
			<?php endif; ?>
			<div class="ys-option__section">
				<form method="post" action="" id="cache-clear">
					<?php wp_nonce_field( self::NONCE_ACTION, self::NONCE_NAME ); ?>
					<p>テーマ内で作成したキャッシュの件数確認・削除を行います。</p>
					<table class="ys-option__table">
						<thead>
						<tr style="border-bottom: 1px solid #ddd;">
							<th>種類</th>
							<td>件数</td>
							<td></td>
						</tr>
						</thead>
						<tbody>
						<tr>
							<th>新着記事一覧</th>
							<td><?php echo $this->get_cache_count( 'recent_posts' ); ?></td>
							<td><input type="submit" name="delete[recent_posts]" id="submit" class="button button-primary" value="キャッシュを削除"></td>
						</tr>
						<tr>
							<th>記事下エリア「関連記事」</th>
							<td><?php echo $this->get_cache_count( 'related_posts' ); ?></td>
							<td><input type="submit" name="delete[related_posts]" id="submit" class="button button-primary" value="キャッシュを削除"></td>
						</tr>
						<tr>
							<th>ブログカード</th>
							<td>
								<?php echo $this->get_cache_count( Blog_Card::CACHE_KEY ); ?>
							</td>
							<td><input type="submit" name="delete[<?php echo Blog_Card::CACHE_KEY; ?>]" id="submit" class="button button-primary" value="キャッシュを削除"></td>
						</tr>

						<tr>
							<th>アイコン</th>
							<td><?php echo $this->get_cache_count( Icon::CACHE_KEY ); ?></td>
							<td><input type="submit" name="delete[<?php echo Icon::CACHE_KEY; ?>]" id="submit" class="button button-primary" value="キャッシュを削除"></td>
						</tr>
						<?php do_action( 'ys_option_cache_table_row' ); ?>
						</tbody>
					</table>
					<p><input type="submit" name="delete_all" id="submit" class="button button-primary" value="すべてのキャッシュを削除"></p>
				</form>
			</div>
		</div>
		<?php
	}

	/**
	 * キャッシュ設定が有効か
	 *
	 * @return bool
	 */
	private function is_enable_cache() {
		if ( 'none' !== Option::get_option( 'ys_query_cache_recent_posts', 'none' ) ) {
			return true;
		}
		if ( 'none' !== Option::get_option( 'ys_query_cache_related_posts', 'none' ) ) {
			return true;
		}

		return false;
	}


	/**
	 * キャッシュ削除処理
	 *
	 * @return string
	 */
	private function delete_cache() {

		if ( ! Admin::verify_nonce( self::NONCE_NAME, self::NONCE_ACTION ) ) {
			return '';
		}

		$result = '';
		/**
		 * 全削除
		 */
		if ( isset( $_POST['delete_all'] ) ) {
			$delete = $this->get_delete_types();
			$count  = 0;
			foreach ( $delete as $key => $value ) {
				$count += $this->delete_cache_data( $key );
			}
			$result = $this->get_cache_delete_message(
				'all',
				$count
			);
		}
		/**
		 * 個別削除
		 */
		if ( isset( $_POST['delete'] ) ) {
			foreach ( $_POST['delete'] as $key => $value ) {
				$result = $this->get_cache_delete_message(
					$key,
					$this->delete_cache_data( $key )
				);
			}
		}

		return $result;
	}


	/**
	 * キャッシュ件数のカウント
	 *
	 * @param string $cache_key キャッシュキー.
	 * @param string $prefix    プレフィックス.
	 *
	 * @return int
	 */
	private function get_cache_count( $cache_key, $prefix = Cache::PREFIX ) {
		/**
		 * Class wpdb
		 *
		 * @global \wpdb
		 */
		global $wpdb;
		$transient_key = apply_filters(
			'ys_cache_count_key__' . $cache_key,
			$prefix . $cache_key,
			$cache_key,
			$prefix
		);
		// クエリ実行.
		$results = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT count(*) as 'count' FROM $wpdb->options WHERE option_name LIKE %s",
				'%_transient_' . $transient_key . '%'
			),
			OBJECT
		);
		if ( empty( $results ) ) {
			return 0;
		}

		return $results[0]->count;
	}

	/**
	 * キャッシュの削除
	 *
	 * @param string $cache_key キャッシュキー.
	 * @param string $prefix    プレフィックス.
	 *
	 * @return int;
	 */
	private function delete_cache_data( $cache_key, $prefix = Cache::PREFIX ) {
		/**
		 * Class wpdb
		 *
		 * @global \wpdb
		 */
		global $wpdb;
		$transient_key = apply_filters(
			'ys_cache_delete_key__' . $cache_key,
			$prefix . $cache_key,
			$cache_key,
			$prefix
		);
		/**
		 * キャッシュの削除
		 */
		$delete = $wpdb->query(
			$wpdb->prepare(
				"DELETE FROM $wpdb->options WHERE option_name LIKE %s",
				'%_transient_' . $transient_key . '%'
			)
		);
		/**
		 * キャッシュ有効期限の削除
		 */
		$wpdb->query(
			$wpdb->prepare(
				"DELETE FROM $wpdb->options WHERE option_name LIKE %s",
				'%_transient_timeout_' . $transient_key . '%'
			)
		);
		if ( false === $delete ) {
			$delete = 0;
		}

		return $delete;
	}

	/**
	 * キャッシュ削除メッセージ
	 *
	 * @param string $type  タイプ.
	 * @param int    $count 件数.
	 *
	 * @return string
	 */
	private function get_cache_delete_message( $type, $count = 0 ) {
		if ( 0 >= $count ) {
			return '';
		}
		$cache_type = apply_filters(
			'ys_get_cache_delete_message_type',
			$this->get_delete_types(),
			$type
		);
		/**
		 * メッセージの作成
		 */
		$message = '';
		if ( isset( $cache_type[ $type ] ) ) {
			$message = $cache_type[ $type ] . 'のキャッシュを ' . $count . '件 削除しました。';
		}

		return apply_filters( 'ys_get_cache_delete_message', $message, $type );
	}

	/**
	 * キャッシュ削除タイプ
	 *
	 * @return array
	 */
	private function get_delete_types() {
		return [
			'all'                => 'すべて',
			'recent_posts'       => '新着記事一覧',
			'related_posts'      => '関連記事',
			Blog_Card::CACHE_KEY => 'ブログカード',
		];
	}

}

new Admin_Menu();
