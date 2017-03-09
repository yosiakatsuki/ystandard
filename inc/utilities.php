<?php
//------------------------------------------------------------------------------
//
//	utilities
//
//------------------------------------------------------------------------------




//------------------------------------------------------------------------------
//
//	条件判定
//
//------------------------------------------------------------------------------


//-----------------------------------------------
//	TOPページ判断（HOMEの1ページ目 or front-page）
//-----------------------------------------------
if (!function_exists( 'ys_is_toppage')) {
	function ys_is_toppage() {
		if((is_home() && !is_paged()) || is_front_page()){
			return true;
		} else {
			return false;
		}
	}
}



//-----------------------------------------------
//	モバイル判定（タブレットはPCとして判断）
//-----------------------------------------------
if( ! function_exists( 'ys_is_mobile' ) ) {
	function ys_is_mobile(){
		$ua = array(
						'^(?!.*iPad).*iPhone',	//iPadとiPhoneが混ざるUAがあるらしい
						'iPod',
						'Android.*Mobile',
						'Mobile.*Firefox',
						'Windows.*Phone',
						'blackberry',
						'dream',
						'CUPCAKE',
						'webOS',
						'incognito',
						'webmate'
						);
		$pattern = '/'.implode('|', $ua).'/i';
		return preg_match($pattern, $_SERVER['HTTP_USER_AGENT']);
	}
}

//-----------------------------------------------
//	AMP判断
//-----------------------------------------------
if( ! function_exists( 'ys_is_amp' ) ) {
	function ys_is_amp(){
		global $ys_amp;

		if($ys_amp !== null){
			return $ys_amp;
		}
		$param_amp = '';
		if(isset($_GET['amp'])){
			$param_amp = $_GET['amp'];
		}

		if($param_amp === '1' && ys_is_amp_enable()){
			$ys_amp = true;
		} else {
			$ys_amp = false;
		}

		return $ys_amp;
	}
}


//-----------------------------------------------
//	AMPページにできるか判断
//-----------------------------------------------
if( ! function_exists( 'ys_is_amp_enable' ) ) {
	function ys_is_amp_enable(){

		global $post;
		$result = true;

		if(ys_get_setting('ys_amp_enable') == 0){
			return false;
		}

		if(is_single()) {
			$content = $post->post_content;

			// scriptタグの判断
			if(strpos($content,'<script') !== false && ys_get_setting('ys_amp_del_script') != 1) {
				$result = false;
			}
			// style属性の判断
			if(preg_match('/style=".+?"/i',$content,$matches) === 1 && ys_get_setting('ys_amp_del_style') != 1) {
				$result = false;
			}

		} else {
			$result = false;
		}
		return $result;
	}
}




//-----------------------------------------------
//	OGP設定が揃っているか
//-----------------------------------------------
if( ! function_exists( 'ys_is_ogp_enable' ) ) {
	function ys_is_ogp_enable(){

		$ogp = ys_settings_get_ogp();
		if($ogp['app_id'] != '' && $ogp['admins'] != '' && $ogp['image'] != ''){
			return true;
		}
		return false;
	}
}




//------------------------------------------------------------------------------
//
//	カテゴリー関連の関数
//
//------------------------------------------------------------------------------
//-----------------------------------------------
//	カテゴリーIDの一覧取得
//-----------------------------------------------
if (!function_exists( 'ys_utilities_get_cat_id_list')) {
	function ys_utilities_get_cat_id_list($sibling=false) {
		// カテゴリー取得
		if(is_category()) {
			$catid = get_query_var('cat');
		} else {
			$postcat = get_the_category();
			// 取得できなければNULL
			if (!$postcat){
				return null;
			}
			$catid = $postcat[0]->cat_ID;
		}

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
						//array_push( $allcats, $sibcat);
						$allcats[] = $sibcat;
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
//	投稿のカテゴリー取得
//-----------------------------------------------
if( ! function_exists( 'ys_utilities_get_the_post_categorys' ) ) {
	function ys_utilities_get_the_post_categorys($number = 0,$link=true,$separator=', ',$postid=0,$itemprop='itemprop="keywords"' ) {

		if($postid==0){
			$postid = get_the_ID();
		}

		$terms ='';
		//投稿カテゴリ取得
		$categories = get_the_category($postid);
		$count = 0;
		if($categories){
			//カテゴリが取得できたら
			foreach( $categories as $category ) {
				$terms .= '<span class="'.$category->slug.'" '.$itemprop.' >';
				//リンクありの場合
				if($link){
					$terms .= '<a href="' . get_category_link( $category->term_id ) . '">';
				}//if($link)

				//カテゴリ名付ける
				$terms .= $category->cat_name;
				//リンクありの場合
				if($link){
					$terms .= '</a>';
				}//if($link)
				$terms .= '</span>';

				//区切り文字
				$terms .= $separator;

				$count += 1;
				if($number != 0 && $number <= $count){
					break;
				}
			}//foreach
		}

		// 投稿のカテゴリーを表示
		return rtrim($terms,$separator);
	}//ys_utilities_get_the_post_categorys
}




//-----------------------------------------------
//	投稿のタグ一覧取得
//-----------------------------------------------
if( ! function_exists( 'ys_utilities_get_the_tag_list' ) ) {
	function ys_utilities_get_the_tag_list($separator=', ',$link=true,$postid=0) {
		if($postid==0){
			$postid = get_the_ID();
		}
		//投稿タグ取得
		$posttags = get_the_tags();
		$result = '';
		if ( $posttags ) {

			//カテゴリが取得できたら
			foreach( $posttags as $posttag ) {
				$result .= '<span class="'.$posttag->slug.'">';
				//リンクありの場合
				if($link){
					$result .= '<a href="' . get_tag_link( $posttag->term_id ) . '">';
				}//if($link)

				//カテゴリ名付ける
				$result .= $posttag->name;
				//リンクありの場合
				if($link){
					$result .= '</a>';
				}//if($link)
				$result .= '</span>';

				//区切り文字
				$result .= $separator;
			}//foreach
			//最後の区切り文字をトリム
			$result = rtrim($result, $separator);
			return $result;

		}
	}//ys_utilities_get_the_tag_list
}



//------------------------------------------------------------------------------
//
//	画像関連の関数
//
//------------------------------------------------------------------------------
//-----------------------------------------------
//	アイキャッチ画像 or 1番目の画像取得
//-----------------------------------------------
if (!function_exists( 'ys_utilities_get_post_thumbnail')) {
	function ys_utilities_get_post_thumbnail($thumbname='full',$defaultimg='',$postid=0) {

		if($postid == 0){
			$postid = get_the_ID();
		}

		// アイキャッチ画像idの取得
		$post_thumbnail_id = get_post_thumbnail_id( $postid );
		if($post_thumbnail_id != '' && $post_thumbnail_id !== false) {

			// 画像オブジェクト取得
			$image = wp_get_attachment_image_src( $post_thumbnail_id, $thumbname );

			// full以外で取得して取得できない場合fullを取り直す
			if($image == false && $thumbname != 'full'){
				$image = wp_get_attachment_image_src( $post_thumbnail_id, 'full' );
			}

			// 取得できたらreturn
			if($image !== false){
				$image[] = trim( strip_tags( get_post_meta( $post_thumbnail_id, '_wp_attachment_image_alt', true ) ) );
				return $image;
			}
		}

		// 存在しない時に表示する画像の指定があればそちらを使用
		if($defaultimg != ''){
			// サイズをセット
			list($width,$height) = getimagesize($defaultimg);
			$resultimg = array($defaultimg, $width, $height);

		} else {
			// 画像取得できなかった場合先頭画像を取得
			$resultimg = ys_utilities_get_post_firstimg($postid);

		}
		$resultimg[] = false;
		$resultimg[] = get_the_title($postid); //alt用


		return $resultimg;
	}
}




//-----------------------------------------------
//	アイキャッチ画像 or 1番目の画像 の画像URLを取得
//-----------------------------------------------
if (!function_exists( 'ys_utilities_get_post_thumbnail_url')) {
	function ys_utilities_get_post_thumbnail_url($thumbname='full',$defaultimg='',$postid=0) {

		if($postid == 0){
			$postid = get_the_ID();
		}
		// 記事内容
		$img = ys_utilities_get_post_thumbnail($thumbname,$defaultimg,$postid);
		return $img[0];
	}
}




//-----------------------------------------------
//	記事先頭の画像を取得
//-----------------------------------------------
if (!function_exists( 'ys_utilities_get_post_firstimg')) {
	function ys_utilities_get_post_firstimg($postid=0) {

		if($postid == 0){
			$postid = get_the_ID();
		}
		// 記事内容
		$post_content = get_post($postid)->post_content;
		$pattern = '/<img.*?src=(["\'])(.+?)\1.*?>/i';

		if(preg_match($pattern,$post_content,$match_img)){

			// 画像取得
			$imgurl = $match_img[2];

		} else {

			// 画像がなかった場合
			if(file_exists(STYLESHEETPATH.'images/noimage.gif')){
				$imgurl = get_stylesheet_directory_uri().'/images/noimage.gif';
			} else {
				$imgurl = get_template_directory_uri().'/images/noimage.gif';
			}
		}

		// サイズをセット
		list($width,$height) = getimagesize($imgurl);
		return array($imgurl, $width, $height);
	}
}




//-----------------------------------------------
//	カスタムロゴURL取得
//-----------------------------------------------
if (!function_exists( 'ys_utilities_get_custom_logo_image_src')) {
	function ys_utilities_get_custom_logo_image_src($blog_id=0) {

		if ( is_multisite() && (int) $blog_id !== get_current_blog_id() ) {
				switch_to_blog( $blog_id );
		}

		$custom_logo_id = get_theme_mod( 'custom_logo' );

		$img = false;
		// We have a logo. Logo is go.
		if ( $custom_logo_id ) {
				$img = wp_get_attachment_image_src( $custom_logo_id, 'full');
		}

		if ( is_multisite() && ms_is_switched() ) {
				restore_current_blog();
		}

		return $img;
	}
}




//-----------------------------------------------
//	ユーザー画像取得
//-----------------------------------------------
if (!function_exists( 'ys_utilities_get_the_user_avatar_img')) {
	function ys_utilities_get_the_user_avatar_img($author_id = false,$size = 96,$args=array()){

		if($author_id == false){
			$author_id = get_the_author_meta( 'ID' );
		}

		/**
		 *	引数デフォルト値セット
		 */
		$defaults = array(
			'itemprop' => false,
		);
		$args = wp_parse_args($args,$defaults);


		$alt =  get_the_author_meta( 'display_name',$author_id );
		$user_avatar = get_avatar( $author_id, $size ,'',$alt);
		$custom_avatar = get_user_meta($author_id, 'ys_custom_avatar', true);

		$img = '';

		/**
		 *	構造化データのタグ出力
		 */
		$itemprop = '';
		if($args['itemprop']){
			$itemprop=' itemprop="image"';
		}

		// オリジナル画像があればそちらを使う
		if($custom_avatar !== '') {
			$img = '<img src="' . $custom_avatar . '" alt="'.$alt.'" '.image_hwstring( $size, $size ).$itemprop.' />';
		} elseif($user_avatar !== '') {
			$img = str_replace('<img','<img '.$itemprop, $user_avatar);;
		}

		$img = apply_filters('ys_user_avatar',$img ,$author_id ,$size);

		// amp対応
		if(ys_is_amp()) {
			$img = str_replace('<img','<amp-img layout="responsive"',$img);
		}

		return $img;
	}
}









//------------------------------------------------------------------------------
//
//	関連記事など、ループで使用するクエリ作成関数
//
//------------------------------------------------------------------------------
//-----------------------------------------------
//	基本形
//-----------------------------------------------
if (!function_exists( 'ys_utilities_get_query')) {
	function ys_utilities_get_query(
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
		// wp_parse_args
		if($option!==null){
			$args = array_merge($args,$option);
		}

		return $args;
	}
}




//-----------------------------------------------
//	ランダムに取得
//-----------------------------------------------
if (!function_exists( 'ys_utilities_get_rand')) {
	function ys_utilities_get_rand(
											$posts_per_page=4,
											$option=null,
											$order='DESC',
											$post_type='post'){

		// ランダムなクエリを取得
		return ys_utilities_get_query($posts_per_page,'rand',$option,$order,$post_type);
	}
}





/**
 *
 *	その他　関数
 *
 */



//------------------------------------------------------------------------------
//
//	サニタイズ
//
//------------------------------------------------------------------------------

//-----------------------------------------------
//	チェックボックスのサニタイズ
//-----------------------------------------------
if (!function_exists( 'ys_utilities_sanitize_checkbox')) {
	function ys_utilities_sanitize_checkbox( $value ) {
		if ( $value == true || $value === 'true' ) {
			return true;
		} else {
			return false;
		}
	}
}


?>