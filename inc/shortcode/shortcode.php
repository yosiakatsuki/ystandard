<?php
/**
 * ショートコード
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * クラス読み込み
 */
require_once __DIR__ . '/class-ys-shortcode-post-paging.php';
require_once __DIR__ . '/class-ys-shortcode-post-taxonomy.php';




/**
 * 前後の記事
 *
 * @param array $args パラメータ.
 *
 * @return string
 */
function ys_shortcode_post_paging( $args ) {
	$sc = new YS_Shortcode_Post_Paging( $args );

	return $sc->get_html();
}

add_shortcode( 'ys_post_paging', 'ys_shortcode_post_paging' );


/**
 * 投稿カテゴリー・タグ表示
 *
 * @param array $args パラメータ.
 *
 * @return string
 */
function ys_shortcode_ys_post_tax( $args ) {
	$sc = new YS_Shortcode_Post_Taxonomy( $args );

	return $sc->get_html();
}

add_shortcode( 'ys_post_tax', 'ys_shortcode_ys_post_tax' );



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
	if ( null === $content ) {
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
