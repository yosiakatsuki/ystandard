<?php
/**
 * フッターSNS関連
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

if ( ! function_exists( 'ys_get_footer_sns_list' ) ) {
	/**
	 * フッターSNSフォローリンク用URL取得
	 */
	function ys_get_footer_sns_list() {
		$sns = array(
			'twitter',
			'facebook',
			'google-plus',
			'instagram',
			'tumblr',
			'youtube',
			'github',
			'pinterest',
			'linkedin',
		);
		/**
		 * リンク作成用配列作成
		 */
		$list = array();
		foreach ( $sns as $value ) {
			$option = ys_create_footer_sns_fa_link(
				$value,
				'ys_follow_url_' . str_replace( '-', '', $value ),
				$value
			);
			if ( '' !== trim( $option['url'] ) ) {
				$list[] = $option;
			}
		}
		return apply_filters( 'ys_get_footer_sns_list', $list );
	}
}

if ( ! function_exists( 'ys_create_footer_sns_link' ) ) {
	/**
	 * フッターSNSフォローリンク作成用配列作成(汎用)
	 *
	 * @param  string $class      class.
	 * @param  string $option_key option key.
	 * @param  string $icon_class icon class.
	 * @return array
	 */
	function ys_create_footer_sns_link( $class, $option_key, $icon_class = '' ) {
		return array(
			'class'      => $class,
			'url'        => ys_get_option( $option_key ),
			'icon-class' => $icon_class,
		);
	}
}

if ( ! function_exists( 'ys_create_footer_sns_fa_link' ) ) {
	/**
	 * フッターSNSフォローリンク作成用配列作成(font awesome)
	 *
	 * @param  string $class      class.
	 * @param  string $option_key option key.
	 * @param  string $fa_class   fontawesome class.
	 * @return array
	 */
	function ys_create_footer_sns_fa_link( $class, $option_key, $fa_class ) {
		return ys_create_footer_sns_link( $class, $option_key, 'fa fa-' . $fa_class );
	}
}