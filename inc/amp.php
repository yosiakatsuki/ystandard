<?php
//------------------------------------------------------------------------------
//
//	AMPページに関する処理
//
//------------------------------------------------------------------------------


// ----------------------------------------------
// amp用フォーマットに変換
// ----------------------------------------------
if(!function_exists( 'ys_amp_convert_amp')) {
	function ys_amp_convert_amp($the_content) {

		$content = $the_content;

		if(ys_is_amp()) {
			$content = str_replace( ']]>', ']]&gt;', $content );

			// imgの置換
			$content = ys_amp_replace_image($content);
			// iframeの置換
			$content = ys_amp_replace_iframe($content);

			// scriptの削除
			if(get_option('ys_amp_del_script',0) == 1){
				$content = ys_amp_delete_script($content);
			}
			// styleの削除
			if(get_option('ys_amp_del_style',0) == 1){
				$content = ys_amp_delete_style($content);
			}

		}

		return apply_filters('ys_amp_convert_amp',$content);
	}
}
add_filter('the_content','ys_amp_convert_amp',11);




// ----------------------------------------------
// imgタグの置換
// ----------------------------------------------
if(!function_exists( 'ys_amp_replace_image')) {
	function ys_amp_replace_image($content) {

		$pattern = '/<img/i';
		if(preg_match($pattern,$content,$matches) === 1){
			$append = $matches[0];
			$append = '<amp-img layout="responsive"';
			$content = preg_replace($pattern, $append, $content);
		}
		return $content;
	}
}




// ----------------------------------------------
// iframeタグの置換
// ----------------------------------------------
if(!function_exists( 'ys_amp_replace_iframe')) {
	function ys_amp_replace_iframe($content) {

		$pattern = '/<iframe/i';
		if(preg_match($pattern,$content,$matches) === 1){
			$append = $matches[0];
			$append = '<amp-iframe';
			$content = preg_replace($pattern, $append, $content);
		}
		return $content;
	}
}



// ----------------------------------------------
// scriptタグの置換
// ----------------------------------------------
if(!function_exists( 'ys_amp_delete_script')) {
	function ys_amp_delete_script($content) {

		$pattern = '/<script>.+?<\/script>/i';
		if(preg_match($pattern,$content,$matches) === 1){
			$append = $matches[0];
			$append = '';
			$content = preg_replace($pattern, $append, $content);
		}
		return $content;
	}
}




// ----------------------------------------------
// styleタグの置換
// ----------------------------------------------
if(!function_exists( 'ys_amp_delete_style')) {
	function ys_amp_delete_style($content) {

		$pattern = '/style=".+?"/i';
		if(preg_match($pattern,$content,$matches) === 1) {
			$append = $matches[0];
			$append = '';
			$content = preg_replace($pattern, $append, $content);
		}

		return $content;
	}
}


?>