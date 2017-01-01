<?php
//------------------------------------------------------------------------------
//
//	画像関連の関数
//
//------------------------------------------------------------------------------









//-----------------------------------------------
//	記事一覧用画像タグの出力
//-----------------------------------------------
if (!function_exists( 'ys_image_the_post_thumbnail')) {
	function ys_image_the_post_thumbnail($postid=0,$thumbname='full',$outputmeta=true,$imgid='',$imgclass='') {

		if($postid == 0){
			$postid = get_the_ID();
		}
		// 画像を取得
		$image = ys_utilities_get_post_thumbnail($postid,$thumbname);

		// id確認
		if($imgid !== ''){
			$imgid = ' id="'.$imgid.'"';
		}
		// class確認
		if($imgclass !== ''){
			$imgclass = ' class="'.$imgclass.'"';
		}

		//imgタグを出力
		if(ys_is_amp()){
			$imgtag = '<amp-img layout="responsive" ';
		} else {
			$imgtag = '<img ';
		}
		echo $imgtag.$imgid.$imgclass.'src="'.$image[0].'" width="'.$image[1].'" height="'.$image[2].'" />';

		//metaタグを出力
		if($outputmeta && !ys_is_amp()){
			echo '<meta itemprop="url" content="'.$image[0].'" />';
			echo '<meta itemprop="width" content="'.$image[1].'" />';
			echo '<meta itemprop="height" content="'.$image[2].'" />';
		}
	}
}




//-----------------------------------------------
//	カスタムロゴURL取得
//-----------------------------------------------
if (!function_exists( 'ys_image_get_custom_logo_image_src')) {
	function ys_image_get_custom_logo_image_src($blog_id=0) {

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
if (!function_exists( 'ys_image_get_the_user_avatar_img')) {
	function ys_image_get_the_user_avatar_img($author_id = null,$size = 96){

		if($author_id == null){
			$author_id = get_the_author_meta( 'ID' );
		}
		$alt =  get_the_author_meta( 'display_name' );
		$user_avatar = get_avatar( $author_id, $size ,'',$alt);
		$custom_avatar = get_user_meta($author_id, 'ys_custom_avatar', true);

		$img = '';

		// オリジナル画像があればそちらを使う
		if($custom_avatar !== '') {
			$img = '<img src="' . $custom_avatar . '" alt="'.$alt.'" width="'.$size.'" height="'.$size.'" itemprop="image" />';
		} elseif($user_avatar !== '') {
			$img = str_replace('<img','<img itemprop="image"', $user_avatar);;
		}

		// amp対応
		if(ys_is_amp()) {
			$img = str_replace('<img','<amp-img layout="responsive"',$img);
		}

		return $img;
	}
}




//-----------------------------------------------
//	ユーザー画像出力
//-----------------------------------------------
if (!function_exists( 'ys_image_the_user_avatar')) {
	function ys_image_the_user_avatar($author_id = null,$size = 96){

		echo ys_image_get_the_user_avatar_img($author_id,$size);
	}
}


?>