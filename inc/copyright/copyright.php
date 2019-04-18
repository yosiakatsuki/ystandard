<?php
/**
 * フッター copyright
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * フッターコピーライト表示取得
 *
 * @return string
 */
function ys_get_footer_site_info() {
	$copy      = ys_get_copyright();
	$poweredby = ys_get_poweredby();

	return $copy . $poweredby;
}

/**
 * フッターコピーライト表示
 *
 * @return void
 */
function ys_the_footer_site_info() {
	echo ys_get_footer_site_info();
}

/**
 * Copyright
 *
 * @return string
 */
function ys_get_copyright() {
	$copy = ys_get_copyright_default();
	/**
	 * Copyright
	 */
	$copy = apply_filters( 'ys_copyright', $copy );
	if ( $copy ) {
		$copy = sprintf(
			'<p id="footer-copy" class="copyright flex__col">%s</p>',
			$copy
		);
	}

	return $copy;
}

/**
 * Copyrightのデフォルト文字列を作成
 *
 * @return string
 */
function ys_get_copyright_default() {
	$year = ys_get_option( 'ys_copyright_year' );
	if ( '' === $year ) {
		$year = date_i18n( 'Y' );
	}
	$url       = esc_url( home_url( '/' ) );
	$blog_name = get_bloginfo( 'name' );

	return sprintf(
		'Copyright &copy; %s <a href="%s" rel="home">%s</a> All Rights Reserved.',
		esc_html( $year ),
		$url,
		$blog_name
	);
}


/**
 * Powered By
 *
 * @return string
 */
function ys_get_poweredby() {
	/**
	 * テーマの情報
	 */
	$theme = '<a href="https://wp-ystandard.com" target="_blank" rel="nofollow">yStandard Theme</a> by <a href="https://yosiakatsuki.net/blog/" target="_blank" rel="nofollow">yosiakatsuki</a> ';
	$theme = apply_filters( 'ys_poweredby_theme', $theme );
	/**
	 * WordPress
	 */
	$url      = __( 'https://wordpress.org/' );
	$powerdby = sprintf(
		'Powered by <a href="%s" target="_blank" rel="nofollow">WordPress</a>',
		$url
	);
	/**
	 * Powered By
	 */
	$html = sprintf(
		'<p id="footer-poweredby" class="footer-poweredby flex__col text--lg-right">%s%s</p>',
		$theme,
		$powerdby
	);

	return apply_filters( 'ys_poweredby', $html );
}
