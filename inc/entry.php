<?php
//------------------------------------------------------------------------------
//
//	投稿の表示関連
//
//------------------------------------------------------------------------------




//-----------------------------------------------
//	投稿・更新日取得
//-----------------------------------------------
if (!function_exists( 'ys_entry_the_entry_date')) {
	function ys_entry_the_entry_date() {

		$postdate = get_the_time('Ymd');
		$moddate = get_the_modified_time('Ymd');
		$pubdate = 'pubdate="pubdate"';
		$updatecontent = 'content="'.get_the_modified_time('Y-m-d').'"';
		if(ys_is_amp()){
			$pubdate = '';
			$updatecontent = '';
		}

		//公開直後に微調整はよくあること。日付で判断
		if($postdate === $moddate) {
			echo '<time class="entry-date entry-published published updated" itemprop="dateCreated datePublished dateModified" datetime="'.get_post_time('Y-m-d').'" '.$pubdate.'>'.$postdate.'</time>';
		} else {
			echo '<time class="entry-date entry-published published" itemprop="dateCreated datePublished" datetime="'.get_post_time('Y-m-d').'" '.$pubdate.'>'.$postdate.'</time>';
			echo '<span class="entry-updated updated" itemprop="dateModified" '.$updatecontent.'>'.$moddate.'</span>';
		}
	}
}




//-----------------------------------------------
//	投稿者取得
//-----------------------------------------------
if (!function_exists( 'ys_entry_the_entry_author')) {
	function ys_entry_the_entry_author() {

		$author_name = get_the_author();
		$author_url = esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) );
		echo '<span class="author vcard"><a class="url fn n" href="'.$author_url.'">'.$author_name.'</a></span>';
	}
}




?>