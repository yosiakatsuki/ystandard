<?php
/**
 * ウィジェット基本クラス
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * テーマ内で作成するウィジェットのベース
 */
class YS_Widget_Base extends WP_Widget {
	/**
	 * テーマ内で使える画像サイズ取得
	 */
	protected function get_image_sizes() {
		global $_wp_additional_image_sizes;
		$sizes = array();
		foreach ( get_intermediate_image_sizes() as $size ) {
			if ( in_array( $size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {
				$sizes[ $size ]['width']  = get_option( "{$size}_size_w" );
				$sizes[ $size ]['height'] = get_option( "{$size}_size_h" );
			} elseif ( isset( $_wp_additional_image_sizes[ $size ] ) ) {
				$sizes[ $size ] = array(
					'width'  => $_wp_additional_image_sizes[ $size ]['width'],
					'height' => $_wp_additional_image_sizes[ $size ]['height'],
				);

			}
		}
		return $sizes;
	}
	/**
	 * チェックボックスのサニタイズ
	 *
	 * @param [type] $value チェックボックスのvalue.
	 * @return bool
	 */
	protected function sanitize_checkbox( $value ) {
		if ( true == $value || 'true' === $value ) {
			return true;
		} else {
			return false;
		}
	}
}
