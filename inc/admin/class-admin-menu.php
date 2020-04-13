<?php
/**
 * テーマオプションページ
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

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
			'',
			3
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
	 * スタートページ
	 */
	public function start_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		?>
		<div class="wrap ys-option-page">
			<h2><span class="orbitron">yStandard</span> スタートページ</h2>
			<div class="ys-option__section">
				<h3><span class="orbitron">yStandard</span>情報</h3>
				<dl class="ystandard-info">
					<dt><span class="orbitron">yStandard</span>本体</span></dt>
					<dd><?php echo Utility::get_ystandard_version(); ?></dd>
					<?php if ( get_template() !== get_stylesheet() ) : ?>
						<dt>子テーマ</dt>
						<dd><?php echo Utility::get_theme_version(); ?></dd>
					<?php endif; ?>
				</dl>
			</div>
			<div class="ys-option__section">
				<h3><span class="orbitron">yStandard</span>コンテンツ</h3>
				<div class="ys-contents">

					<div class="ys-contents__item">
						<h4>マニュアル</h4>
						<div class="ys-contents__icon">
							<?php echo Icon::get_icon( 'book' ); ?>
						</div>
						<p class="ys-contents__text">
							yStandardの設定や使い方のマニュアル
						</p>
						<p class="wp-block-button">
							<a class="wp-block-button__link" href="https://wp-ystandard.com/manual/" target="_blank" rel="noopener noreferrer nofollow">マニュアルを見る</a>
						</p>
					</div>

					<div class="ys-contents__item">
						<h4>拡張プラグイン</h4>
						<div class="ys-contents__icon">
							<?php echo Icon::get_icon( 'tool' ); ?>
						</div>
						<p class="ys-contents__text">
							yStandardでブログを書くことがもっと楽しくなる拡張プラグイン！<br>
							ブロック拡張プラグインやデザインスキンの配布・販売を予定しています！
						</p>
						<p class="wp-block-button">
							<a class="wp-block-button__link" href="https://wp-ystandard.com/plugins/" target="_blank" rel="noopener noreferrer nofollow">拡張プラグインを見る</a>
						</p>
					</div>

					<div class="ys-contents__item">
						<h4><span class="orbitron">yStandard</span>を応援する</h4>
						<div class="ys-contents__icon">
							<?php echo Icon::get_icon( 'gift' ); ?>
						</div>
						<div class="ys-contents__text">
							<ul style="text-align: center;">
								<li>「知り合いにyStandardを紹介する」</li>
								<li>「ブログでyStandardを紹介する」</li>
							</ul>
							<p>
								…など、ちょっとしたことでもyStandadを応援する方法があります。
							</p>
						</div>
						<p class="wp-block-button">
							<a class="wp-block-button__link" href="https://wp-ystandard.com/contribute/" target="_blank" rel="noopener noreferrer nofollow"><span class="orbitron">yStandard</span>を応援する</a>
						</p>
					</div>

					<div class="ys-contents__item">
						<h4>フォーラム</h4>
						<div class="ys-contents__icon">
							<?php echo Icon::get_icon( 'message-square' ); ?>
						</div>
						<p class="ys-contents__text">
							yStandardの使い方や機能要望、不具合かも？という内容はフォーラムにて質問・相談を受け付けております。
						</p>
						<p class="wp-block-button">
							<a class="wp-block-button__link" href="https://support.wp-ystandard.com/forums/" target="_blank" rel="noopener noreferrer nofollow">フォーラムを見る</a>
						</p>
					</div>

					<div class="ys-contents__item">
						<h4>ユーザーコミュニティ</h4>
						<div class="ys-contents__icon">
							<?php echo Icon::get_icon( 'slack' ); ?>
						</div>
						<p class="ys-contents__text">
							yStandard利用者同士での交流を目的としたSlackチームです<br>
							コミュニティ参加者限定のオンラインもくもく会などを開催しています。
						</p>
						<p class="wp-block-button">
							<a class="wp-block-button__link" href="https://wp-ystandard.com/ystandard-user-community/" target="_blank" rel="noopener noreferrer nofollow">ユーザーコミュニティに参加する</a>
						</p>
					</div>
				</div>
			</div>
		</div>
		<?php
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
						<tr>
							<th>種類</th>
							<td>件数</td>
							<td></td>
						</tr>
						</thead>
						<tbody>
						<tr>
							<th>新着記事一覧</th>
							<td><?php echo $this->get_cache_count( 'tax_posts' ); ?></td>
							<td><input type="submit" name="delete[tax_posts]" id="submit" class="button button-primary" value="キャッシュを削除"></td>
						</tr>
						<tr>
							<th>記事下エリア「関連記事」</th>
							<td><?php echo $this->get_cache_count( 'related_posts' ); ?></td>
							<td><input type="submit" name="delete[related_posts]" id="submit" class="button button-primary" value="キャッシュを削除"></td>
						</tr>
						<tr>
							<th>ブログカード</th>
							<td><?php echo $this->get_cache_count( Blog_Card::CACHE_KEY ); ?></td>
							<td><input type="submit" name="delete[<?php echo Blog_Card::CACHE_KEY; ?>]" id="submit" class="button button-primary" value="キャッシュを削除"></td>
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
			$result = $this->get_cache_delete_message(
				$this->delete_cache_data( 'all' )
			);
		}
		/**
		 * 個別削除
		 */
		if ( isset( $_POST['delete'] ) ) {
			foreach ( $_POST['delete'] as $key => $value ) {
				$result = $this->get_cache_delete_message(
					$this->delete_cache_data( $key )
				);
			}
		}

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
		$transient_key = $prefix . $cache_key;
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
		$transient_key = $prefix . $cache_key;
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
			[
				'all'           => 'すべて',
				'tax_posts'     => 'カテゴリーに属する記事一覧',
				'related_posts' => '関連記事',
			],
			$type
		);
		/**
		 * メッセージの作成
		 */
		$message = '';
		if ( isset( $cache_type[ $type ] ) ) {
			$message = $cache_type[ $type ] . 'のキャッシュを' . $count . '件 削除しました。';
		}

		return apply_filters( 'ys_get_cache_delete_message', $message, $type );
	}

}

new Admin_Menu();
