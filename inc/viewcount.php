<?php
//------------------------------------------------------------------------------
//
//	簡易的なビュー数カウント
//
//------------------------------------------------------------------------------


//-----------------------------------------------
// 定数
//-----------------------------------------------
define('YS_METAKEY_PV_ALL',		 'ys_pv_all');
define('YS_METAKEY_PV_DAY_KEY',		 'ys_pv_key_d');
define('YS_METAKEY_PV_DAY_VAL',		 'ys_pv_val_d');
define('YS_METAKEY_PV_WEEK_KEY',		 'ys_pv_key_w');
define('YS_METAKEY_PV_WEEK_VAL',		 'ys_pv_val_w');
define('YS_METAKEY_PV_MONTH_KEY',		 'ys_pv_key_m');
define('YS_METAKEY_PV_MONTH_VAL',		 'ys_pv_val_m');



//-----------------------------------------------
//	ビュー数の取得
//-----------------------------------------------
if( ! function_exists( 'ys_viewcount_get_view_count' ) ) {
	function ys_viewcount_get_view_count($postid,$type='all') {

		if($type==='all'){
			//全期間の値を取得
			$strKey = YS_PV_ALL;
		} elseif($type==='d') {
			//日別の値を取得
			$strKey = YS_METAKEY_PV_DAY_KEY;
		} elseif($type==='w') {
			//週別の値を取得
			$strKey = YS_METAKEY_PV_WEEK_KEY;
		} elseif($type==='m') {
			//月別の値を取得
			$strKey = YS_METAKEY_PV_MONTH_KEY;
		} else {
			return 0;
		}

		//ビュー数取得
		$strviews = get_post_meta($postid,$strKey,TRUE);

		if(is_numeric($strviews)){
			return (int)$strviews;
		} else {
			return 0;
		}

	}
}


//-----------------------------------------------
//	ビュー数の取得(文字列)
//-----------------------------------------------
if( ! function_exists( 'ys_viewcount_get_view_count_format' ) ) {
	function ys_viewcount_get_view_count_format($postid,$type='all') {

		// ビュー数取得
		$viewcount = ys_viewcount_get_view_count($postid,$type);

		if($viewcount >= 1000000) {
			// 100万以上の値を「M」に変換
			$strviews = sprintf('%.2f',$viewcount/1000000).'M';
		} elseif ($viewcount >= 1000) {
			$strviews = sprintf('%.2f',$viewcount/1000).'K';
		} else {
			$strviews = (string)$viewcount;
		}
	}
}


//-----------------------------------------------
//	簡易ビュー数カウント
//-----------------------------------------------
if( ! function_exists( 'ys_viewcount_update_views' ) ) {
	function ys_viewcount_update_views() {
		global $post, $posts;

		$intpv_cnt = 1;
		$getmetakey = '';
		if ( is_single()) {
		//-------------------------
		//全アクセスカウント
		//-------------------------
		$intpv_cnt = 1;
		$getmetakey = get_post_meta($post->ID,YS_METAKEY_PV_ALL,TRUE);
		if( is_numeric($getmetakey) ) {
		$intpv_cnt = (int) $getmetakey + 1;
		}
		//カスタムフィールド更新
		update_post_meta($post->ID,YS_METAKEY_PV_ALL,$intpv_cnt);

		//-------------------------
		//日別アクセスカウント
		//-------------------------
		$intpv_cnt = 1;
		//キー取得
		$getmetakey = get_post_meta($post->ID,YS_METAKEY_PV_DAY_KEY,TRUE);
		//日付変更判断
		if( $getmetakey === date_i18n("Y/m/d")) {
		//日付が変わってなければPV数加算
		$getmetakey = get_post_meta($post->ID,YS_METAKEY_PV_DAY_VAL,TRUE);
		if( is_numeric($getmetakey) ) {
		$intpv_cnt = (int) $getmetakey + 1;
		}
		update_post_meta($post->ID,YS_METAKEY_PV_DAY_VAL,$intpv_cnt);
		} else {
		//日付が変わっていたら、キーとPV数をを更新
		update_post_meta($post->ID,YS_METAKEY_PV_DAY_KEY,date_i18n("Y/m/d"));
		update_post_meta($post->ID,YS_METAKEY_PV_DAY_VAL,$intpv_cnt);
		}

		//-------------------------
		//週別アクセスカウント
		//-------------------------
		$intpv_cnt = 1;
		$getmetakey = get_post_meta($post->ID,YS_METAKEY_PV_WEEK_KEY,TRUE);
		if( $getmetakey === date_i18n("Y-W")) {

		$getmetakey = get_post_meta($post->ID,YS_METAKEY_PV_WEEK_VAL,TRUE);
		if( is_numeric($getmetakey) ) {
		$intpv_cnt = (int) $getmetakey + 1;
		}
		update_post_meta($post->ID,YS_METAKEY_PV_WEEK_VAL,$intpv_cnt);
		} else {
		update_post_meta($post->ID,YS_METAKEY_PV_WEEK_KEY,date_i18n("Y-W"));
		update_post_meta($post->ID,YS_METAKEY_PV_WEEK_VAL,$intpv_cnt);
		}
		//-------------------------
		//月別アクセスカウント
		//-------------------------
		$intpv_cnt = 1;
		$getmetakey = get_post_meta($post->ID,YS_METAKEY_PV_MONTH_KEY,TRUE);
		if( $getmetakey === date_i18n("Y-m")) {

		$getmetakey = get_post_meta($post->ID,YS_METAKEY_PV_MONTH_VAL,TRUE);
		if( is_numeric($getmetakey) ) {
		$intpv_cnt = (int) $getmetakey + 1;
		}
		update_post_meta($post->ID,YS_METAKEY_PV_MONTH_VAL,$intpv_cnt);
		} else {
		update_post_meta($post->ID,YS_METAKEY_PV_MONTH_KEY,date_i18n("Y-m"));
		update_post_meta($post->ID,YS_METAKEY_PV_MONTH_VAL,$intpv_cnt);
		}
		}
		return '';
	}//ys_viewcount_update_views
}
add_filter('wp_head','ys_viewcount_update_views');




//-----------------------------------------------
//	ランキング表示用query作成
//-----------------------------------------------
if (!function_exists( 'ys_viewcount_get_query_base')) {
	function ys_viewcount_get_query_base($posts_per_page,$order,$meta,$option){

		$args = array(
						 'post_type'=>'post',
						 'posts_per_page'=> $posts_per_page,
						 'order'=>$order,
						 'no_found_rows' => true,
						 'ignore_sticky_posts'=>true
					 );

		// ランキングの条件部分をマージ
		$args = array_merge($args,$meta);

		if($option!==null){
			$args = array_merge($args,$option);
		}
		// WP_Queryを作成
		return New WP_Query($args);
	}
}




//-----------------------------------------------
//	全ランキング表示用query作成
//-----------------------------------------------
if (!function_exists( 'ys_viewcount_get_query_all')) {
	function ys_viewcount_get_query_all($posts_per_page=4,$order='DESC',$option=null){

		// ランキングの条件部分を作成
		$meta = array(
							'orderby' => 'meta_value_num',
							'meta_key' => YS_METAKEY_PV_ALL
						);

		// WP_Queryを作成
		return ys_viewcount_get_query_base($posts_per_page,$order,$meta,$option);
	}
}




//-----------------------------------------------
//	日別ランキング表示用query作成
//-----------------------------------------------
if (!function_exists( 'ys_viewcount_get_query_day')) {
	function ys_viewcount_get_query_day($posts_per_page=4,$order='DESC',$option=null){

		// ランキングの条件部分を作成
		$meta = array(
							'meta_key' => YS_METAKEY_PV_DAY_VAL,
							'meta_query' => array(
																array(
																	'key' => YS_METAKEY_PV_DAY_KEY,
																	'value' => date_i18n("Y/m/d"),
																	'compare' => '='
																)
															)
						);

		// WP_Queryを作成
		return ys_viewcount_get_query_base($posts_per_page,$order,$meta,$option);
	}
}




//-----------------------------------------------
//	週別ランキング表示用query作成
//-----------------------------------------------
if (!function_exists( 'ys_viewcount_get_query_week')) {
	function ys_viewcount_get_query_week($posts_per_page=4,$order='DESC',$option=null){

		// ランキングの条件部分を作成
		$meta = array(
							'meta_key' => YS_METAKEY_PV_WEEK_VAL,
							'meta_query' => array(
																array(
																	'key' => YS_METAKEY_PV_WEEK_KEY,
																	'value' => date_i18n("Y-W"),
																	'compare' => '='
																)
															)
						);

		// WP_Queryを作成
		return ys_viewcount_get_query_base($posts_per_page,$order,$meta,$option);
	}
}




//-----------------------------------------------
//	月別ランキング表示用query作成
//-----------------------------------------------
if (!function_exists( 'ys_viewcount_get_query_month')) {
	function ys_viewcount_get_query_month($posts_per_page=4,$order='DESC',$option=null){

		// ランキングの条件部分を作成
		$meta = array(
							'meta_key' => YS_METAKEY_PV_MONTH_VAL,
							'meta_query' => array(
																array(
																	'key' => YS_METAKEY_PV_MONTH_KEY,
																	'value' => date_i18n("Y-m"),
																	'compare' => '='
																)
															)
						);

		// WP_Queryを作成
		return ys_viewcount_get_query_base($posts_per_page,$order,$meta,$option);
	}
}
?>