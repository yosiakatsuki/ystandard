<?php
//------------------------------------------------------------------------------
//
//	関連記事など、ループで使用するクエリ作成関数
//
//------------------------------------------------------------------------------




//-----------------------------------------------
//	基本形
//-----------------------------------------------
if (!function_exists( 'ys_query_get_query')) {
	function ys_query_get_query(
									$posts_per_page=4,
									$orderby='date',
									$option=null,
									$order='DESC',
									$post_type='post') {

		$args = array(
							'post_type'=>$post_type,
							'posts_per_page'=> $posts_per_page,
							'order'=>$order,
							'orderby' => $orderby,
							'no_found_rows' => true,
							'ignore_sticky_posts'=>true
						);

		//オプションがあれば追加
		if($option!==null){
			$args = array_merge($args,$option);
		}

		return $args;
	}
}




//-----------------------------------------------
//	ランダムに取得
//-----------------------------------------------
if (!function_exists( 'ys_query_get_rand')) {
	function ys_query_get_rand(
											$posts_per_page=4,
											$option=null,
											$order='DESC',
											$post_type='post'){

		// ランダムなクエリを取得
		return ys_query_get_query($posts_per_page,'rand',$option,$order,$post_type);
	}
}


?>