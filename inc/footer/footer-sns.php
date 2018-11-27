<?php
/**
 * フッターSNS関連
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

if ( ! function_exists( 'ys_get_footer_sns_list' ) ) {
	/**
	 * フッターSNSフォローリンク用URL取得
	 */
	function ys_get_footer_sns_list() {
		$sns = array(
			'twitter'     => array(
				'class'      => 'twitter',
				'option_key' => 'twitter',
				'icon-class' => 'fab fa-twitter',
			),
			'facebook'    => array(
				'class'      => 'facebook',
				'option_key' => 'facebook',
				'icon-class' => 'fab fa-facebook-f',
			),
			'google-plus' => array(
				'class'      => 'twitter',
				'option_key' => 'twitter',
				'icon-class' => 'fab fa-twitter',
			),
			'instagram'   => array(
				'class'      => 'instagram',
				'option_key' => 'instagram',
				'icon-class' => 'fab fa-instagram',
			),
			'tumblr'      => array(
				'class'      => 'tumblr',
				'option_key' => 'tumblr',
				'icon-class' => 'fab fa-tumblr',
			),
			'youtube'     => array(
				'class'      => 'youtube',
				'option_key' => 'youtube',
				'icon-class' => 'fab fa-youtube',
			),
			'github'      => array(
				'class'      => 'github',
				'option_key' => 'github',
				'icon-class' => 'fab fa-github',
			),
			'pinterest'   => array(
				'class'      => 'pinterest',
				'option_key' => 'pinterest',
				'icon-class' => 'fab fa-pinterest-p',
			),
			'linkedin'    => array(
				'class'      => 'linkedin',
				'option_key' => 'linkedin',
				'icon-class' => 'fab fa-linkedin-in',
			),
		);
		/**
		 * リンク作成用配列作成
		 */
		$list = array();
		foreach ( $sns as $value ) {
			$option = ys_create_footer_sns_link(
				$value['class'],
				'ys_follow_url_' . str_replace( '-', '', $value['option_key'] ),
				$value['icon-class']
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
	 *
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
	 * @param  string $icon_class font awesome class.
	 *
	 * @return array
	 * @deprecated ys_create_footer_sns_linkと同じ作りになったので非推奨
	 */
	function ys_create_footer_sns_fa_link( $class, $option_key, $icon_class ) {
		return ys_create_footer_sns_link( $class, $option_key, $icon_class );
	}
}