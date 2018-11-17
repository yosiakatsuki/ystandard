<?php
/**
 * キャッシュ管理ページで利用する関数
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * キャッシュキー : yscache_
 *
 * タクソノミーに属する記事一覧 : tax_posts
 * ランキング : ranking
 * 関連記事 : related_posts
 */

/**
 * キャッシュ関連処理メイン
 *
 * @return string
 */
function ys_setting_cache_post() {
	$result = '';
	/**
	 * 全削除
	 */
	if ( isset( $_POST['delete_all'] ) ) {
		ys_setting_cache_delete_cache( '' );
		$result = ys_setting_cache_get_cache_delete_message( 'all', 0 );
	}
	/**
	 * 個別削除
	 */
	if ( isset( $_POST['delete'] ) ) {
		foreach ( $_POST['delete'] as $key => $value ) {
			$count  = ys_setting_cache_delete_cache( $key );
			$result = ys_setting_cache_get_cache_delete_message( $key, $count );
		}
	}

	return apply_filters( 'ys_setting_cache_post', $result );
}

/**
 * キャッシュ削除時のメッセージ
 *
 * @param string $key   削除するキャッシュのキー.
 * @param int    $count 削除件数.
 *
 * @return string
 */
function ys_setting_cache_get_cache_delete_message( $key, $count = 0 ) {
	$message = '';
	/**
	 * メッセージタイプ
	 */
	$cache_type = array(
		'all'           => 'すべて',
		'tax_posts'     => 'カテゴリーに属する記事一覧',
		'ranking'       => '人気記事ランキング',
		'related_posts' => '関連記事',
	);
	$cache_type = apply_filters( 'ys_get_cache_delete_message_cache_type', $cache_type, $key );
	/**
	 * メッセージの作成
	 */
	if ( isset( $cache_type[ $key ] ) ) {
		$count_message = '';
		if ( 0 < $count ) {
			$count_message = $count . '件';
		}
		$message = $cache_type[ $key ] . 'のキャッシュを' . $count_message . '削除しました。';
	}

	return apply_filters( 'ys_get_cache_delete_message', $message, $key );
}

/**
 * キャッシュ件数のカウント
 *
 * @param string $cache_key キャッシュキー.
 * @param string $prefix    プレフィックス.
 *
 * @return int
 */
function ys_setting_cache_get_count( $cache_key, $prefix = 'yscache_' ) {
	global $wpdb;
	/**
	 * 検索キーの作成
	 */
	$transient_key = $prefix . $cache_key;
	/**
	 * クエリの実行
	 */
	$results = $wpdb->get_results(
		$wpdb->prepare(
			"
			SELECT count(*) as 'count'
			FROM $wpdb->options
			WHERE option_name LIKE %s
			",
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
 * @return int
 */
function ys_setting_cache_delete_cache( $cache_key, $prefix = 'yscache_' ) {
	global $wpdb;
	/**
	 * 検索キーの作成
	 */
	$transient_key = $prefix . $cache_key;
	/**
	 * キャッシュの削除
	 */
	$delete_transient = $wpdb->query(
		$wpdb->prepare(
			"
			DELETE FROM $wpdb->options
			WHERE option_name LIKE %s
			",
			'%_transient_' . $transient_key . '%'
		)
	);
	/**
	 * キャッシュ有効期限の削除
	 */
	$wpdb->query(
		$wpdb->prepare(
			"
			DELETE FROM $wpdb->options
			WHERE option_name LIKE %s
			",
			'%_transient_timeout_' . $transient_key . '%'
		)
	);
	if ( false === $delete_transient ) {
		$delete_transient = 0;
	}

	return $delete_transient;
}

/**
 * タクソノミーに属する記事一覧に使用しているキャッシュ件数を取得
 *
 * @return int
 */
function ys_setting_cache_get_tax_posts_count() {
	return ys_setting_cache_get_count( 'tax_posts' );
}

/**
 * 人気記事ランキングに使用しているキャッシュ件数を取得
 *
 * @return int
 */
function ys_setting_cache_get_ranking_count() {
	return ys_setting_cache_get_count( 'ranking' );
}

/**
 * 関連記事一覧に使用しているキャッシュ件数を取得
 *
 * @return int
 */
function ys_setting_cache_get_related_posts_count() {
	return ys_setting_cache_get_count( 'related_posts' );
}