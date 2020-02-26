<?php
/**
 * ランキング ショートコード クラス
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * Class YS_Shortcode_Post_Ranking
 */
class YS_Shortcode_Post_Ranking extends YS_Shortcode_Get_Posts {

	const CACHE_KEY = 'ranking';

	/**
	 * Constructor.
	 *
	 * @param array $args ユーザー指定パラメーター.
	 */
	public function __construct( $args = array() ) {
		if ( ! is_array( $args ) ) {
			$args = array();
		}
		/**
		 * ランキング用のパラメーター指定
		 */
		$args = array_merge(
			array(
				'ranking_type'     => 'all',
				'filter'           => 'category',
				'cache_key'        => self::CACHE_KEY,
				'cache_expiration' => ys_get_option( 'ys_query_cache_ranking', 'none' ),
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
