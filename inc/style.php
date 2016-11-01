<?php
//------------------------------------------------------------------------------
//
//	スタイルに関連するクラスの出力等
//
//------------------------------------------------------------------------------




//-----------------------------------------------
//	サイドバーの有り無し
//-----------------------------------------------
if (!function_exists( 'ys_style_content_class')) {
	function ys_style_content_class($classes) {

		$htmlclass = '';
		foreach($classes as $class){
			$htmlclass .= $class.' ';
		}
		if (is_active_sidebar( 'sidebar-main' )  ){
			$htmlclass .= 'has-sidebar ';
		} else {
			$htmlclass .= 'no-sidebar ';
		}
		return rtrim($htmlclass);
	}
}
?>