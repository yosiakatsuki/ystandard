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
require_once __DIR__ . '/class-ys-shortcode-base.php';
require_once __DIR__ . '/class-ys-shortcode-text.php';
require_once __DIR__ . '/class-ys-shortcode-share-button.php';
require_once __DIR__ . '/class-ys-shortcode-get-posts.php';
require_once __DIR__ . '/class-ys-shortcode-post-ranking.php';
require_once __DIR__ . '/class-ys-shortcode-recent-posts.php';
require_once __DIR__ . '/class-ys-shortcode-post-paging.php';
require_once __DIR__ . '/class-ys-shortcode-post-taxonomy.php';
require_once __DIR__ . '/class-ys-shortcode-parts.php';



/**
 * 投稿一覧
 *
 * @param array $args    パラメータ.
 * @param null  $content 内容.
 *
 * @return string
 */
function ys_shortcode_get_posts( $args, $content = null ) {
	$sc = new YS_Shortcode_Get_Posts( $args );

	return $sc->get_html( $content );
}

add_shortcode( 'ys_get_posts', 'ys_shortcode_get_posts' );


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
 * 記事ランキング
 *
 * @param array $args    パラメータ.
 * @param null  $content 内容.
 *
 * @return string
 */
function ys_shortcode_post_ranking( $args, $content = null ) {
	$sc = new YS_Shortcode_Post_Ranking( $args );

	return $sc->get_html( $content );
}

add_shortcode( 'ys_post_ranking', 'ys_shortcode_post_ranking' );

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
 * 新着記事一覧
 *
 * @param array $args    パラメーター.
 * @param null  $content 内容.
 *
 * @return string
 */
function ys_shortcode_recent_posts( $args, $content = null ) {
	$sc = new YS_Shortcode_Recent_Posts( $args );

	return $sc->get_html( $content );
}

add_shortcode( 'ys_recent_posts', 'ys_shortcode_recent_posts' );
add_shortcode( 'ys_tax_posts', 'ys_shortcode_recent_posts' ); // 旧ショートコードの互換.


/**
 * シェアボタンショートコード
 *
 * @param array $args パラメーター.
 *
 * @return string
 */
function ys_shortcode_share_button( $args ) {
	$sc = new YS_Shortcode_Share_Button( $args );

	return $sc->get_html();
}

add_shortcode( 'ys_share_button', 'ys_shortcode_share_button' );

/**
 * 汎用テキストショートコード
 *
 * @param array $args    パラメーター.
 * @param null  $content 内容.
 *
 * @return string
 */
function ys_shortcode_text( $args, $content = null ) {
	$sc = new YS_Shortcode_Text( $args );

	return apply_filters(
		'ys_sc_text_shortcode',
		$sc->get_html( $content ),
		$sc->get_args()
	);
}

add_shortcode( 'ys_text', 'ys_shortcode_text' );

/**
 * [ys]パーツショートコード
 *
 * @param array $args    パラメーター.
 * @param null  $content 内容.
 *
 * @return string
 */
function ys_shortcode_parts( $args, $content = null ) {
	$sc = new YS_Shortcode_Parts( $args );

	return apply_filters(
		'ys_sc_parts_shortcode',
		$sc->get_html( $content ),
		$sc->get_args()
	);
}

add_shortcode( 'ys_parts', 'ys_shortcode_parts' );

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
