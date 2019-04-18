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
	$sns = array(
		'twitter'   => array(
			'class'      => 'twitter',
			'option_key' => 'twitter',
			'icon'       => '<i class="fab fa-twitter" aria-hidden="true"></i>',
			'title'      => 'twitter',
		),
		'facebook'  => array(
			'class'      => 'facebook',
			'option_key' => 'facebook',
			'icon'       => '<i class="fab fa-facebook-f" aria-hidden="true"></i>',
			'title'      => 'facebook',
		),
		'instagram' => array(
			'class'      => 'instagram',
			'option_key' => 'instagram',
			'icon'       => '<i class="fab fa-instagram" aria-hidden="true"></i>',
			'title'      => 'instagram',
		),
		'tumblr'    => array(
			'class'      => 'tumblr',
			'option_key' => 'tumblr',
			'icon'       => '<i class="fab fa-tumblr" aria-hidden="true"></i>',
			'title'      => 'tumblr',
		),
		'youtube'   => array(
			'class'      => 'youtube',
			'option_key' => 'youtube',
			'icon'       => '<i class="fab fa-youtube" aria-hidden="true"></i>',
			'title'      => 'youtube',
		),
		'github'    => array(
			'class'      => 'github',
			'option_key' => 'github',
			'icon'       => '<i class="fab fa-github" aria-hidden="true"></i>',
			'title'      => 'github',
		),
		'pinterest' => array(
			'class'      => 'pinterest',
			'option_key' => 'pinterest',
			'icon'       => '<i class="fab fa-pinterest-p" aria-hidden="true"></i>',
			'title'      => 'pinterest',
		),
		'linkedin'  => array(
			'class'      => 'linkedin',
			'option_key' => 'linkedin',
			'icon'       => '<i class="fab fa-linkedin-in" aria-hidden="true"></i>',
			'title'      => 'linkedin',
		),
	);
	/**
	 * リンク作成用配列作成
	 */
	$list = array();
	foreach ( $sns as $value ) {
		$option = ys_create_footer_sns_link(
			$value['class'],
			'ys_follow_url_' . $value['option_key'],
			$value['icon']
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
 * @param  string $class      class.
 * @param  string $option_key option key.
 * @param  string $icon       icon.
 * @param  string $title      title.
 *
 * @return array
 */
function ys_create_footer_sns_link( $class, $option_key, $icon = '', $title = '' ) {
	return array(
		'class' => $class,
		'url'   => ys_get_option( $option_key ),
		'icon'  => $icon,
		'title' => $title,
	);
}

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
