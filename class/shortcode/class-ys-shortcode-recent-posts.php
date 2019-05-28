<?php
/**
 * 最近の投稿 ショートコード クラス
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * Class YS_Shortcode_Recent_Posts
 */
class YS_Shortcode_Recent_Posts extends YS_Shortcode_Get_Posts {

	const CACHE_KEY = 'recent_posts';

	/**
	 * Constructor.
	 *
	 * @param array $args ユーザー指定パラメーター.
	 */
	public function __construct( $args = array() ) {
		/**
		 * ランキング用のパラメーター削除
		 */
		unset(
			$args['ranking_type']
		);
		$args = array_merge(
			array(
				'cache_key'        => self::CACHE_KEY,
				'cache_expiration' => ys_get_option( 'ys_query_cache_recent_posts' ),
			),
			$args
		);
		parent::__construct( $args );
	}


	/**
	 * HTML取得
	 *
	 * @param string $content コンテンツとなるHTML.
	 *
	 * @return string
	 */
	public function get_html( $content = null ) {

		return parent::get_html( $content );
	}

}