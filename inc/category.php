<?php
//------------------------------------------------------------------------------
//
//	カテゴリー関連の関数
//
//------------------------------------------------------------------------------




//-----------------------------------------------
//	カテゴリーIDの一覧取得
//-----------------------------------------------
if (!function_exists( 'ys_category_get_cat_id_list')) {
	function ys_category_get_cat_id_list($sibling=false) {

		// カテゴリー取得
		$postcat = get_the_category();
		// 取得できなければNULL
		if (!$postcat){
			return null;
		}

		$catid = $postcat[0]->cat_ID;
		$allcats = array($catid);

		// 親がなくなるまでループ
		while($catid>0){
			//親カテゴリを取得
			$category = get_category($catid);
			$catid = $category->parent;
			//0以外ならリストに追加
			if($catid>0){
				//兄弟もめぐる場合
				if($sibling){
					//子どもを取得（重複追加されるのでページ出力には向かない）
					$siblist = get_term_children((int)$catid,'category');
					foreach($siblist as $sibcat){
						//array_push( $allcats, $brcat);
						$allcats[] = $brcat;
					}
				}
				//配列におやカテゴリID追加
				//array_push($allcats, $catid);
				$allcats[] = $catid;
			}
		}
		//子から親を辿ったので、順番反転させて返却（兄弟を含める場合、順番に補償なし）
		return	array_reverse($allcats);
	}
}




//-----------------------------------------------
//	投稿のカテゴリー出力
//-----------------------------------------------
if( ! function_exists( 'ys_category_the_post_categorys' ) ) {
	function ys_category_the_post_categorys($postid=0,$link=True,$separator=', ') {

		if($postid=0){
			$postid = get_the_ID();
		}

		$terms ='';
		$taxonomy = 'category';

		// 投稿に付けられたターム（カテゴリー）の ID を取得する。
		$post_terms = wp_get_object_terms( $postid, $taxonomy, array( 'fields' => 'ids' ) );

		if ( !empty( $post_terms ) && !is_wp_error( $post_terms ) ) {

			$term_ids = implode( ',' , $post_terms );
			$terms = wp_list_categories( 'title_li=&style=none&echo=0&taxonomy=' . $taxonomy . '&include=' . $term_ids );
			$terms = rtrim( trim( str_replace( '<br />',  $separator, $terms ) ), $separator );
		}
		// 投稿のカテゴリーを表示
		echo  $terms;
	}//ys_category_get_post_categorys
}


?>