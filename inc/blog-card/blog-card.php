<?php
/**
 * ブログカード関連の処理
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * ブログカード形式に変換する命令追加
 */
function ys_blog_card_embed_register_handler() {
	/**
	 * 変換予約されているもの以外のURLを対象にする
	 */
	wp_embed_register_handler(
		'ys_blog_card',
		ys_blog_card_get_register_pattern(),
		'ys_blog_card_handler'
	);
}

/**
 * ブログカード化する条件パターンを取得
 */
function ys_blog_card_get_register_pattern() {
	/**
	 * Embed 変換されるURLパターンを取得
	 */
	$oembed    = _wp_oembed_get_object();
	$providers = array_keys( $oembed->providers );
	/**
	 * デリミタの削除
	 */
	foreach ( $providers as $key => $value ) {
		$providers[ $key ] = preg_replace( '/^#(.+)#.*$/', '$1', $value );
	}

	return '#^(?!.*(' . implode( '|', $providers ) . '))https?://.*$#i';
}

/**
 * Embedの変換ハンドラ
 *
 * @param [type] $matches matches.
 * @param [type] $attr attr.
 * @param [type] $url url.
 * @param [type] $rawattr rawattr.
 *
 * @return string ブログカード用ショートコード
 */
function ys_blog_card_handler( $matches, $attr, $url, $rawattr ) {
	$blog_card = '[ys_blog_card url="' . $url . '"]';
	/**
	 * ビジュアルエディタ用処理
	 */
	if ( is_admin() && ys_get_option( 'ys_admin_enable_tiny_mce_style' ) ) {
		/**
		 * ビジュアルエディタの中でショートコードを展開する
		 */
		$blog_card = ys_get_admin_blog_card( $url );
	}

	return $blog_card;
}

/**
 * エディタ内で展開するブログカードHTMLを作成する
 *
 * @param string $url URL.
 *
 * @return string
 */
function ys_get_admin_blog_card( $url ) {
	/**
	 * ビジュアルエディタの中でショートコードを展開する
	 */
	add_shortcode( 'ys_blog_card', 'ys_shortcode_blog_card' );
	$blog_card = ys_do_shortcode(
		'ys_blog_card',
		array(
			'url'   => $url,
			'cache' => 'disable',
		),
		null,
		false
	);
	$blog_card = str_replace( '<a ', '<span ', $blog_card );
	$blog_card = str_replace( '</a>', '</span>', $blog_card );

	return $blog_card;
}

/**
 * Embedでのブログカードの展開
 *
 * @param string $return HTML.
 * @param object $data   Data.
 * @param string $url    URL.
 *
 * @return null|string|string[]
 */
function ys_blog_card_oembed_dataparse( $return, $data, $url ) {

	if ( 'rich' === $data->type ) {
		if ( 1 === preg_match( ys_blog_card_get_register_pattern(), $url ) ) {
			/**
			 * ブログカードの展開
			 */
			$return = ys_get_admin_blog_card( $url );
		}
	}

	return $return;
}

add_filter( 'oembed_dataparse', 'ys_blog_card_oembed_dataparse', 11, 3 );