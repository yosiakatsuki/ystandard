<?php
/**
 * フッターSNS関連
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * フッターSNSフォローリンク用URL取得
 */
function ys_get_footer_sns_list() {
	$sns = ys_get_sns_icons();
	/**
	 * リンク作成用配列作成
	 */
	$list = array();
	foreach ( $sns as $value ) {
		$option = ys_create_footer_sns_link(
			$value['class'],
			'ys_follow_url_' . $value['option_key'],
			$value['icon'],
			$value['title']
		);
		if ( '' !== trim( $option['url'] ) ) {
			$list[] = $option;
		}
	}

	return apply_filters( 'ys_get_footer_sns_list', $list );
}

/**
 * フッターSNSフォローリンク作成用配列作成(汎用)
 *
 * @param string $class      class.
 * @param string $option_key option key.
 * @param string $icon       icon.
 * @param string $title      title.
 *
 * @return array
 */
function ys_create_footer_sns_link( $class, $option_key, $icon = '', $title = '' ) {
	return array(
		'class' => $class,
		'url'   => ys_get_option( $option_key, '' ),
		'icon'  => $icon,
		'title' => $title,
	);
}

/**
 * フッターSNSフォローリンク作成用配列作成(font awesome)
 *
 * @param string $class      class.
 * @param string $option_key option key.
 * @param string $icon_class font awesome class.
 *
 * @return array
 * @deprecated ys_create_footer_sns_linkと同じ作りになったので非推奨
 */
function ys_create_footer_sns_fa_link( $class, $option_key, $icon_class ) {
	return ys_create_footer_sns_link( $class, $option_key, $icon_class );
}
