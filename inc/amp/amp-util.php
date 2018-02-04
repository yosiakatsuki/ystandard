<?php
/**
 *
 * AMP関連のユーティリティー
 *
 */
/**
 *	AMP用正規表現置換処理関数
 */
function ys_amp_preg_replace( $pattern, $replacement, $content ) {

	if( 1 === preg_match( $pattern, $content, $matches ) ){
		$content = preg_replace( $pattern, $replacement, $content );
	}
	return $content;
}

/**
 * imgタグの置換
 */
if( ! function_exists( 'ys_amp_convert_image') ) {
	function ys_amp_convert_image( $img, $layout = 'responsive' ) {
		if( ! ys_is_amp() ) {
			return $img;
		}
		$pattern = '/<img(.+?)?>/i';
		$replacement = '<amp-img layout="' . $layout . '"$1></amp-img>';
		$amp_img = ys_amp_preg_replace( $pattern, $replacement, $img );

		return apply_filters( 'ys_amp_convert_image', $amp_img, $img );
	}
}