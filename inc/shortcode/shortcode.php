<?php
/**
 * ショートコード
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

$shortcode_dir = get_template_directory() . '/inc/shortcode/';

/**
 * 汎用テキスト
 */
require_once $shortcode_dir . 'shortcode-text.php';
/**
 * 投稿者表示ショートコード
 */
require_once $shortcode_dir . 'shortcode-author.php';
/**
 * 投稿者一覧表示ショートコード
 */
require_once $shortcode_dir . 'shortcode-author-list.php';
/**
 * 広告表示ショートコード
 */
require_once $shortcode_dir . 'shortcode-ad.php';
/**
 * ランキングショートコード
 */
require_once $shortcode_dir . 'shortcode-post-ranking.php';
/**
 * 新着記事一覧ショートコード
 */
require_once $shortcode_dir . 'shortcode-recent-posts.php';
/**
 * シェアボタン
 */
require_once $shortcode_dir . 'shortcode-share-button.php';
/**
 * 投稿一覧取得
 */
require_once $shortcode_dir . 'shortcode-get-posts.php';
/**
 * 前後の記事
 */
require_once $shortcode_dir . 'shortcode-post-paging.php';
/**
 * ブログカード
 */
require_once $shortcode_dir . 'shortcode-blog-card.php';
/**
 * 投稿カテゴリー・タグ表示
 */
require_once $shortcode_dir . 'shortcode-post-taxonomy.php';

/**
 * ショートコードの作成と実行
 *
 * @param string $name    ショートコード名.
 * @param array  $param   パラメーター.
 * @param mixed  $content コンテンツ.
 * @param bool   $echo    出力.
 *
 * @return string
 */
function ys_do_shortcode( $name, $param = array(), $content = null, $echo = true ) {
	$shortcode = ys_get_shortcode( $name, $param, $content );
	/**
	 * ショートコード実行
	 */
	$result = do_shortcode( $shortcode );
	/**
	 * 表示 or 取得
	 */
	if ( $echo ) {
		echo $result;

		return '';
	} else {
		return $result;
	}
}

/**
 * ショートコードの作成
 *
 * @param string $name    ショートコード名.
 * @param array  $param   パラメーター.
 * @param mixed  $content コンテンツ.
 *
 * @return string
 */
function ys_get_shortcode( $name, $param = array(), $content = null ) {
	$sc_param   = array();
	$param_text = '';
	/**
	 * パラメーター展開
	 */
	if ( ! empty( $param ) ) {
		foreach ( $param as $key => $value ) {
			if ( is_array( $value ) ) {
				$value = implode( ',', $value );
			}
			$sc_param[] = sprintf(
				'%s="%s"',
				$key,
				$value
			);
		}
	}
	if ( ! empty( $sc_param ) ) {
		$param_text = ' ' . implode( ' ', $sc_param );
	}
	/**
	 * ショートコード作成
	 */
	if ( is_null( $content ) ) {
		/**
		 * コンテンツなし
		 */
		return sprintf(
			'[%s%s]',
			$name,
			$param_text
		);
	} else {
		/**
		 * コンテンツあり
		 */
		return sprintf(
			'[%s%s]%s[/%s]',
			$name,
			$param_text,
			$content,
			$name
		);
	}
}