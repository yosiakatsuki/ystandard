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
	function ys_entry_the_entry_date($show_update = true) {

		$format = get_option( 'date_format' );
		$pubdate = 'pubdate="pubdate"';
		$updatecontent = 'content="'.get_the_modified_time('Y-m-d').'"';

		$entry_date_class = 'entry-date entry-published published';
		$update_date_class = 'entry-updated updated';

		if(ys_is_amp()){
			$pubdate = '';
			$updatecontent = '';
		} else {
			$entry_date_class .= ' entry-date-icon';
			$update_date_class .= ' entry-updated-icon';
		}


		//公開直後に微調整はよくあること。日付で判断
		if(get_the_time('Ymd') === get_the_modified_time('Ymd') || $show_update === false) {
			$entry_date_class .= ' updated';

			echo '<time class="'.$entry_date_class.'" itemprop="dateCreated datePublished dateModified" datetime="'.get_post_time('Y-m-d').'" '.$pubdate.'>'.get_the_time($format).'</time>';
		} else {
			echo '<time class="'.$entry_date_class.'" itemprop="dateCreated datePublished" datetime="'.get_post_time('Y-m-d').'" '.$pubdate.'>'.get_the_time($format).'</time>';
			echo '<span class="'.$update_date_class.'" itemprop="dateModified" '.$updatecontent.'>'.get_the_modified_time($format).'</span>';
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
		echo '<span class="author vcard" itemprop="author editor creator" itemscope itemtype="http://schema.org/Person"><a class="url fn n" itemprop="name" href="'.$author_url.'">'.$author_name.'</a></span>';
	}
}




//-----------------------------------------------
//	筆者のSNSプロフィール
//-----------------------------------------------
if (!function_exists( 'ys_entry_the_author_sns')) {
	function ys_entry_the_author_sns() {

		if(ys_is_amp()) {
			return;
		}

		$sns_list ='';

		// キーがmeta key,valueがfontawesomeのクラス
		$sns_arr = array(
			'ys_twitter' => 'twitter',
			'ys_facebook' => 'facebook',
			'ys_googleplus' => 'google-plus',
			'ys_instagram' => 'instagram',
			'ys_tumblr' => 'tumblr',
			'ys_youtube' => 'youtube-play',
			'ys_vine' => 'vine',
			'ys_github' => 'github',
			'ys_pinterest' => 'pinterest',
			'ys_linkedin' => 'linkedin'
		);

		foreach ($sns_arr as $key => $val) {

			$author_sns = get_the_author_meta( $key );
			if($author_sns != '') {
				$sns_list .= '<li class="color-'.$val.'"><a href="'.esc_url( $author_sns ).'" target="_blank" rel="nofollow" itemprop="url"><i class="fa fa-fw fa-'.$val.'" aria-hidden="true"></i></a></li>'.PHP_EOL;
			}
		}

		if($sns_list !== ''){
			$sns_list = '<ul class="author-sns">'.$sns_list.'</ul>';
		}
		echo $sns_list;
	}
}




//-----------------------------------------------
//	ページング
//-----------------------------------------------
if (!function_exists( 'ys_entry_the_link_pages')) {
	function ys_entry_the_link_pages() {
		wp_link_pages( array(
					'before'      => '<div class="page-links">',
					'after'       => '</div>',
					'link_before' => '<span class="page-text">',
					'link_after'  => '</span>',
					'pagelink'    => '%',
					'separator'   => '',
				) );
	}
}




//-----------------------------------------------
//	投稿抜粋文を作成
//-----------------------------------------------
if( ! function_exists( 'ys_entry_get_the_custom_excerpt' ) ) {
	function ys_entry_get_the_custom_excerpt($content,$length,$sep=' ...'){
		//HTMLタグ削除
		$content = wp_strip_all_tags($content,true);
		//ショートコード削除
		$content = strip_shortcodes($content);
		if(mb_strlen($content) > $length) {
			$content =  mb_substr($content,0,$length - mb_strlen($sep)).$sep;
		}
		return $content;
	}
}




//-----------------------------------------------
//	シェアボタン
//-----------------------------------------------
if( ! function_exists( 'ys_entry_the_sns_share' ) ) {
	function ys_entry_the_sns_share(){

		echo '<div id="sns-share" class="sns-share">';
		echo '<h2 class="sns-share-title">「'.get_the_title().'」をみんなとシェア！</h2>';
		if(!ys_is_amp()){
			// AMP以外

		} else {
			// AMP記事
			$fb_app_id = '254325784911610';
			echo '<amp-social-share type="twitter" width="100" height="44"></amp-social-share>';
			echo '<amp-social-share type="facebook" width="100" height="44" data-param-app_id="'.$fb_app_id.'"></amp-social-share>';
			echo '<amp-social-share type="gplus" width="100" height="44"></amp-social-share>';
			echo '<p class="amp-view-info">その他の方法でシェアする場合は通常表示に切り替えて下さい。<a class="normal-view-link" href="'.get_the_permalink().'#sns-share">通常表示に切り替える »</a></p>';
		}
		echo '</div>';

	}
}


?>