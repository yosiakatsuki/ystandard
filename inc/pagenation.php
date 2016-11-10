<?php
//------------------------------------------------------------------------------
//
//	ページネーション
//
//------------------------------------------------------------------------------




//-----------------------------------------------
//	ページネーション
//-----------------------------------------------
if (!function_exists( 'ys_pagenation')) {
	function ys_pagenation($mid_size=3) {

		global $wp_query;

		// MAXページ数と現在ページ数を取得
		$total	 = isset( $wp_query->max_num_pages ) ? $wp_query->max_num_pages : 1;
		$current = get_query_var( 'paged' ) ? (int) get_query_var( 'paged' )	: 1;

		// 全部で１ページなら出力しない
		if($total == 1){
			return;
		}

		//--------------------------------------------------------------------------

		//ページャーの開始
		echo '<nav class="pagenation">';
		//タイトル出力
		echo ys_pagenation_get_pagenation_title($current,$total);
		//ページのリンク出力
		echo '<ul class="pagenation-list">';
		//--------------------------------------------------------------------------

		// ページングありの場合、ページャー作成
		if ( $total > 1 ) {

			//「先頭へ」リンクの作成(見た目とかの調整の結果コメントアウト)
			if($current>$mid_size + 1 && ($mid_size * 2) + 1 < $total){
				//echo '<a href="'.get_pagenum_link(1).'">«</a>';
			}
			//「前へ」リンクの作成
			if($current>1){
				echo '<li><a class="previous" href="'.get_pagenum_link($current - 1).'"><i class="fa fa-angle-left" aria-hidden="true"></i></a></li>';
			}

			//各ページへのリンク
			for($i=1;$i<=$total;$i++){
				//リンクの作成範囲判断
				if($i > $current - ($mid_size + 1) && $i < $current + ($mid_size + 1)){
					if($i == $current){
					//現在ページ
					echo '<li><span class="current">'.$i.'</span></li>';
					} else {
					//ページヘのリンク
					echo '<li><a href="'.get_pagenum_link($i).'">'.$i.'</a></li>';
					}
				}
			}

			//「次へ」リンクの作成
			if($current<$total){
				echo '<li><a class="next" href="'.get_pagenum_link($current + 1).'"><i class="fa fa-angle-right" aria-hidden="true"></i></a></li>';
			}
			//「末尾へ」リンクの作成(見た目とかの調整の結果コメントアウト)
			if($current<$total - $mid_size && ($mid_size * 2) + 1 < $total){
				//echo '<a href="'.get_pagenum_link($total - 1).'">»</a>';
			}
		} else {
			//ページングなし
			echo '<li><span class="current">1</span></li>';
		}

		//閉じる
		echo '</ul>';
		echo '</nav>';
	}
}



//-----------------------------------------------
//	ページネーションタイトル
//-----------------------------------------------
if (!function_exists( 'ys_pagenation_get_pagenation_title')) {
	function ys_pagenation_get_pagenation_title($current,$total) {

		return '<p class="pagenation-title">Page '.$current.' of '.$total.'</p>';
	}
}




?>