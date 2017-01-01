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

		if(get_option('ys_amp_enable',0) == 0){
			return false;
		}

		if(is_single()) {
			$content = $post->post_content;

			// scriptタグの判断
			if(strpos($content,'<script>') !== false && get_option('ys_amp_del_script',0) != 1) {
				$result = false;
			}
			// style属性の判断
			if(preg_match('/style=".+?"/i',$content,$matches) === 1 && get_option('ys_amp_del_style',0) != 1) {
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

		$ogp = ys_option_get_ogp();
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




//------------------------------------------------------------------------------
//
//	画像関連の関数
//
//------------------------------------------------------------------------------
//-----------------------------------------------
//	アイキャッチ画像 or 1番目の画像取得
//-----------------------------------------------
if (!function_exists( 'ys_utilities_get_post_thumbnail')) {
	function ys_utilities_get_post_thumbnail($postid=0,$thumbname='full',$defaultimg='') {

		if($postid == 0){
			$postid = get_the_ID();
		}

		// アイキャッチ画像idの取得
		$post_thumbnail_id = get_post_thumbnail_id( $postid );
		if($post_thumbnail_id != '' && $post_thumbnail_id !== false) {

			// 画像オブジェクト取得
			$image = wp_get_attachment_image_src( $post_thumbnail_id, $thumbname );
			// 取得できたらreturn
			if($image !== false){
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

		return $resultimg;
	}
}




//-----------------------------------------------
//	アイキャッチ画像 or 1番目の画像 の画像URLを取得
//-----------------------------------------------
if (!function_exists( 'ys_utilities_get_post_thumbnail_url')) {
	function ys_utilities_get_post_thumbnail_url($postid=0,$thumbname='full',$defaultimg='') {

		if($postid == 0){
			$postid = get_the_ID();
		}
		// 記事内容
		$img = ys_utilities_get_post_thumbnail($postid,$thumbname,$defaultimg);
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
			$imgurl = $imgmatch[2];

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



?>