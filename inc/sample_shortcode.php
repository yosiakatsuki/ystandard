<?php
//------------------------------------------------------------------------------
//
//	ショートコードサンプル
//
//------------------------------------------------------------------------------




//-----------------------------------------------
//	サンプル
//-----------------------------------------------
function ys_shortcode_sample() {
		return "it's ys_shortcode_sample";
}
add_shortcode('ys_sample', 'ys_shortcode_sample');
//[ys_sample]




//-----------------------------------------------
//	サンプル変数指定
//-----------------------------------------------
function ys_shortcode_sample_2($args) {
		// デフォルト値をセットして、変数に代入
		extract(shortcode_atts(array(
				'arg' => 0,
		), $args));

		return $arg;
}
add_shortcode('ys_sample2', 'ys_shortcode_sample_2');
//[ys_sample2 arg=9999]




//-----------------------------------------------
//	サンプル囲み
//-----------------------------------------------
function ys_shortcode_sample_3($args,$content=null) {

		if(!is_null($content)){
			$content = '<span>'.$content.'</span>';
		} else {
			$content ='';
		}
		return $content;
}
add_shortcode('ys_sample3', 'ys_shortcode_sample_3');
//[ys_sample3]かこみ[/ys_sample3]
?>