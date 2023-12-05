<?php
/**
 * マニュアルリンクなど
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */

namespace ystandard\utils;

defined( 'ABSPATH' ) || die();

/**
 * Class Manual
 *
 * @package ystandard\utils
 */
class Manual {
	/**
	 * マニュアルリンク作成
	 *
	 * @param string $url URL.
	 * @param string $text Text.
	 * @param bool $inline Inline.
	 * @param string $icon Icon Name.
	 *
	 * @return string
	 */
	public static function manual_link( $url, $text = '', $inline = false, $icon = 'book' ) {

		$url  = apply_filters( 'ys_manual_link_url', $url );
		$text = apply_filters( 'ys_manual_link_text', $text, $url );

		if ( empty( $url ) ) {
			return '';
		}

		// デフォルトのリンクテキスト
		if ( '' === $text ) {
			$text = __( 'マニュアルを見る', 'ystandard' );
		}
		$icon = do_shortcode( "[ys_icon name=\"{$icon}\" class=\"\"]" );

		// URLではなくパスで指定されている場合は公式サイトへリンク.
		if ( false === strpos( $url, 'https://' ) ) {
			$url = "https://wp-ystandard.com/{$url}/";
		}

		$flex = $inline ? 'inline-flex' : 'flex';

		$link = "<a class=\"{$flex} gap-1 items-center text-fz-xs\" href=\"{$url}\" target=\"_blank\">{$icon}{$text}</a>";

		// インライン指定がない場合はラップする.
		if ( ! $inline ) {
			$link = sprintf( '<div class="block">%s</div>', $link );
		}

		return wp_targeted_link_rel( $link );
	}
}
