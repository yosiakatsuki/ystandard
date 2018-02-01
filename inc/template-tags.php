<?php
/* ***************************************************************************
 *
 *	テンプレート出力する類の関数
 *
 * ************************************************************************ */


//-----------------------------------------------
//	この記事を読む
//-----------------------------------------------
if (!function_exists( 'ys_template_the_entry_more_text')) {
	function ys_template_the_entry_more_text() {

		$read_more = 'この記事を読む';

		echo apply_filters('ys_entry_more_text',$read_more);
	}
}




//-----------------------------------------------
//	投稿者情報
//-----------------------------------------------
if (!function_exists( 'ys_template_get_the_biography')) {
	function ys_template_get_the_biography( $user_id = false,$shortcode=false,$tag='aside') {

		$author_link = esc_url( get_author_posts_url( get_the_author_meta( 'ID',$user_id ) ) );
		$author_name = ys_template_get_the_entry_author(true,$user_id,$shortcode);
		$author_sns = ys_template_get_the_author_sns($user_id);
		$author_dscr = wpautop(str_replace(array("\r\n", "\r", "\n"),"\n\n",get_the_author_meta( 'description' ,$user_id)));

		$avatar_args = $shortcode == false ? array('itemprop'=>true) : array();
		$avatar = ys_utilities_get_the_user_avatar_img($user_id,96,$avatar_args);
		if($avatar !== ''){
			$avatar = '<figure class="author-avatar"><a class="author-link" href="'.$author_link.'" rel="author">'.$avatar.'</a></figure>';
		}
		$class_dscr = $avatar !== '' ? ' show-avatar' : '';
		$class_wrap = 'author-info clearfix';
		$id = 'id="biography"';
		$itemscope = ' itemscope itemtype="http://schema.org/Person" itemprop="author editor creator copyrightHolder"';
		$itemprop = ' itemprop="description"';

		if($shortcode){
			$class_wrap = 'author-info clearfix';
			$id = '';
			$itemscope = '';
			$itemprop = '';
		}


		$template = ys_template_get_the_biography_template();

		return sprintf(
						$template,
						$tag,
						$id,
						$class_wrap,
						$itemscope,
						$avatar,
						$class_dscr,
						$author_name,
						$author_sns,
						$itemprop,
						$author_dscr,
						$tag);

	}
}




//-----------------------------------------------
//	投稿者情報
//-----------------------------------------------
if (!function_exists( 'ys_template_the_biography')) {
	function ys_template_the_biography($user_id = false) {
		echo ys_template_get_the_biography($user_id);
	}
}




//-----------------------------------------------
//	投稿者情報表示テンプレート取得
//-----------------------------------------------
if (!function_exists( 'ys_template_get_the_biography_template')) {
	function ys_template_get_the_biography_template() {

		$template = <<<EOD
		<%s %s class="%s" %s>
			%s
			<div class="author-description%s">
				<h2 class="author-title">
					%s
				</h2>
				%s
				<div class="author-bio" %s>%s</div>
			</div>
		</%s>
EOD;

	return apply_filters('ys_biography_template',$template);

	}
}




//-----------------------------------------------
//	投稿者取得
//-----------------------------------------------
if (!function_exists( 'ys_template_get_the_entry_author')) {
	function ys_template_get_the_entry_author( $link = true, $user_id = false, $hidestracture=false, $ico=false ) {

		$author_name = esc_html( get_the_author_meta( 'display_name',$user_id ) );
		$author_url = esc_url( get_author_posts_url( get_the_author_meta( 'ID',$user_id ) ) );

		$stracture = '';
		$itempropname = '';

		if(!$hidestracture){
			$itempropname = ' itemprop="name"';

			if(!is_singular()){
				$stracture = ' itemscope itemtype="http://schema.org/Person" itemprop="author editor creator"';
			}
		}



		if($link) {
			$tag = 'a';
			$href = 'href="'.$author_url.'" rel="author"';
		} else {
			$tag = 'span';
			$href = '';
		}

		$author = '<span class="vcard author"'.$stracture.'><'.$tag.' class="url fn" '.$href.'><span'.$itempropname.'>'.$author_name.'</span></'.$tag.'></span>';

		if( $ico ){
			$author = '<svg class="entry-meta-ico" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M12.3 3.7l4 4L4 20H0v-4L12.3 3.7zm1.4-1.4L16 0l4 4-2.3 2.3-4-4z"/></svg>'.$author;
		}

		return apply_filters( 'ys_get_the_entry_author', $author, $user_id );
	}
}




//-----------------------------------------------
//	投稿者表示
//-----------------------------------------------
if (!function_exists( 'ys_template_the_entry_author')) {
	function ys_template_the_entry_author( $link = true, $user_id = false, $hidestracture=false, $ico=false ) {

		echo ys_template_get_the_entry_author( $link, $user_id, $hidestracture, $ico );
	}
}


//-----------------------------------------------
//	筆者のSNSプロフィール
//-----------------------------------------------
if (!function_exists( 'ys_template_get_the_author_sns')) {
	function ys_template_get_the_author_sns($user_id = false) {

		$sns_list ='';

		// キーがmeta key,valueがfontawesomeのクラス
		$sns_arr = array(
			'ys_twitter' => 'twitter',
			'ys_facebook' => 'facebook',
			'ys_googleplus' => 'google-plus',
			'ys_instagram' => 'instagram',
			'ys_tumblr' => 'tumblr',
			'ys_youtube' => 'youtube-play',
			'ys_github' => 'github',
			'ys_pinterest' => 'pinterest',
			'ys_linkedin' => 'linkedin'
		);

		foreach ($sns_arr as $key => $val) {

			$author_sns = get_the_author_meta( $key,$user_id );
			if($author_sns != '') {
				$sns_list .= '<li class="color-'.$val.'"><a href="'.esc_url( $author_sns ).'" target="_blank" rel="nofollow" ><i class="fa fa-fw fa-'.$val.'" aria-hidden="true"></i></a></li>'.PHP_EOL;
			}
		}

		if($sns_list !== ''){
			$sns_list = '<ul class="author-sns">'.$sns_list.'</ul>';
		}
		return $sns_list;
	}
}


/**
 * 記事header SNSシェアボタン
 */
if( ! function_exists( 'ys_template_the_entry_header_share' ) ) {
	function ys_template_the_entry_header_share() {

		global $post;

		$html = '';
		$html .= '<aside id="sns-share__entry-header" class="sns-share__header">';

		$html .= ys_template_get_the_sns_share_buttons();
		$html .= '</aside>';

		// シェアボタンが非表示なら表示消す
		if( 0 == ys_get_setting( 'ys_sns_share_on_entry_header' ) ){
			$html = '';
		}

		echo apply_filters( 'ys_template_the_entry_header_share', $html );

	}
}


/**
 *	CTA
 */
if( ! function_exists( 'ys_template_the_entry_foot_cta' ) ) {
	function ys_template_the_entry_foot_cta() {

		// 広告
		$ad = '';// ys_template_the_advertisement_under_content();

		// シェアボタン
		$sns_share = ''; //ys_template_get_the_sns_share();

		// 購読ボタン
		$subscribe = '';//ys_template_get_the_subscribe_buttons();

		// つなげる
		$html = $ad.$sns_share.$subscribe;

		echo apply_filters('ys_the_entry_foot_cta',$html,$ad,$sns_share,$subscribe);

	}
}




//-----------------------------------------------
//	関連記事
//-----------------------------------------------
if( ! function_exists( 'ys_template_the_related_post' ) ) {
	function ys_template_the_related_post() {

		if(ys_get_setting('ys_show_post_related') == 1 && !ys_is_amp()) {
			$cats = ys_utilities_get_cat_id_list();
			$cats = apply_filters('ys_the_related_post_category_in',$cats);
			$option = array(
											'post__not_in' => array(get_the_ID()),  //現在の投稿IDは除く
											'category__in' => $cats, //カテゴリー絞り込み
										);

			$query = new WP_Query(ys_utilities_get_rand(4,$option));

			if ($query->have_posts()) {
				echo '<aside class="entry-post-related entry-footer-container">';
				echo '<h2>関連記事</h2>';
				while ($query->have_posts()) : $query->the_post();
					get_template_part( 'template-parts/content','related' );
				endwhile;
				echo '</aside>';
			}
			wp_reset_postdata();
		}

	}//ys_template_the_related_post
}




//-----------------------------------------------
//	前の記事・次の記事
//-----------------------------------------------
if( ! function_exists( 'ys_template_the_post_paging' ) ) {
	function ys_template_the_post_paging() {

		if(ys_get_setting('ys_hide_post_paging') == 1){
			return;
		}

		$html = '<div class="post-navigation entry-footer-container">';

		$home = '<a href="'.esc_url( home_url( '/' ) ).'">';
		$home .= '<i class="fa fa-home" aria-hidden="true"></i>';
		$home .= '</a>';

		$post_navigation_warp = '<div class="nav-prev">';
		$prevpost = get_previous_post();
		if ($prevpost) {
			$prev_info = apply_filters('ys_the_post_paging_prev_info','<span class="prev-label">«前の投稿</span>');
			$prev_img = '';
			if( has_post_thumbnail( $prevpost->ID ) ) {
				$prev_img = ys_template_get_the_post_thumbnail( 'yslistthumb', false, false, '', 'post-navigation-image', $prevpost->ID);
				$prev_img = apply_filters( 'ys_the_post_paging_prev_image', $prev_img, $prevpost->ID );
				$prev_img = '<span class="post-navigation-image-wrap post-list-thumbnail-center">'.$prev_img.'</span>';
			}
			$prev_link = '<a href="'.esc_url(get_permalink($prevpost->ID)).'">'.$prev_img.get_the_title($prevpost->ID).'</a>';
			$prev_link = apply_filters('ys_the_post_paging_prev_link',$prev_link);
			$html .= $post_navigation_warp.$prev_info.$prev_link;
		} else {
			$post_navigation_warp = '<div class="nav-prev home">';
			$html .= $post_navigation_warp.$home;
		}
		$html .= '</div>';

		$post_navigation_warp = '<div class="nav-next">';
		$nextpost = get_next_post();
		if ($nextpost){

			$next_info = apply_filters('ys_the_post_paging_next_info','<span class="next-label">次の投稿»</span>');
			$next_img = '';
			if( has_post_thumbnail( $nextpost->ID ) ) {
				$next_img = ys_template_get_the_post_thumbnail( 'yslistthumb', false, false, '', 'post-navigation-image', $nextpost->ID);
				$next_img = apply_filters( 'ys_the_post_paging_next_image', $next_img, $nextpost->ID );
				$next_img = '<span class="post-navigation-image-wrap post-list-thumbnail-center">'.$next_img.'</span>';
			}
			$next_link = '<a href="'.esc_url(get_permalink($nextpost->ID)).'">'.$next_img.get_the_title($nextpost->ID).'</a>';
			$next_link = apply_filters('ys_the_post_paging_next_link',$next_link);
			$html .= $post_navigation_warp.$next_info.$next_link;
		} else {
			$post_navigation_warp = '<div class="nav-next home">';
			$html .= $post_navigation_warp.$home;
		}
		$html .= '</div>';



		$html .= '</div>';

		$html = apply_filters('ys_post_paging',$html);

		echo $html;
	}
}








//------------------------------------------------------------------------------
//
//	フッター関連
//
//------------------------------------------------------------------------------



//-----------------------------------------------
//	フッターウィジットエリア
//-----------------------------------------------
if( ! function_exists( 'ys_template_the_fotter_widget' ) ) {
	function ys_template_the_fotter_widget() {

		if ( !ys_is_amp()
					&& (is_active_sidebar( 'footer-left' )
							|| is_active_sidebar( 'footer-center' )
							|| is_active_sidebar( 'footer-right' ) ) ) {

			echo '<div class="footer-widget-wrapper">';

				// 左ウィジェット
				if ( is_active_sidebar( 'footer-left' )){
					echo '<div class="footer-widget-left">';
					dynamic_sidebar( 'footer-left' );
					echo '</div>';
				}

				// 中央ウィジェット
				if ( is_active_sidebar( 'footer-center' )) {
					echo '<div class="footer-widget-center">';
					dynamic_sidebar( 'footer-center' );
					echo '</div>';
				}

				// 右ウィジェット
				if ( is_active_sidebar( 'footer-right' )) {
					echo '<div class="footer-widget-right">';
					dynamic_sidebar( 'footer-right' );
					echo '</div>';
				}

			echo '</div>';
		}
	}//ys_template_the_fotter_widget
}




//-----------------------------------------------
//	AMPページ用メニュー出力
//-----------------------------------------------
if( ! function_exists( 'ys_template_the_amp_menu' ) ) {
	function ys_template_the_amp_menu() {

		if(ys_is_amp()):
		?>
			<amp-sidebar id='sidebar' layout="nodisplay" side="right" class="amp-slider">
				<button class="menu-toggle-label" on='tap:sidebar.close'>
					<span class="top"></span>
					<span class="middle"></span>
					<span class="bottom"></span>
				</button>
				<nav id="site-navigation" class="main-navigation">
		<?php
			wp_nav_menu( array(
				'theme_location' => 'gloval',
				'menu_class'		 => 'gloval-menu',
				'container_class' => 'menu-global-container',
				'depth'          => 2
			 ) );
		?>
			</nav><!-- .main-navigation -->
		</amp-sidebar>
		<?php
		endif;
	}
}




//-----------------------------------------------
//	copyright
//-----------------------------------------------
if( ! function_exists( 'ys_template_the_copyright' ) ) {
	function ys_template_the_copyright() {

		$copyright = '<p class="copy">Copyright &copy; '.ys_get_setting('ys_copyright_year').' <a href="'. esc_url( home_url( '/' ) ) . '" rel="home">' . get_bloginfo('name') . '</a> All Rights Reserved.</p>';
		$powered = '<p id="powered">Powered by <a href="https://ja.wordpress.org/" target="_blank" rel="nofollow">WordPress</a> &amp; ';
		$poweredtheme = '<a href="https://wp-ystandard.com" target="_blank" rel="nofollow">yStandard Theme</a> by <a href="https://yosiakatsuki.net/blog/" target="_blank" rel="nofollow">yosiakatsuki</a></p>';

		$copyright = apply_filters('ys_copyright',$copyright);
		$powered = apply_filters('ys_poweredby',$powered);
		$poweredtheme = apply_filters('ys_poweredby_theme',$poweredtheme);

		echo $copyright.$powered.$poweredtheme;
	}
}







//------------------------------------------------------------------------------
//
//	タクソノミー関連の関数
//
//------------------------------------------------------------------------------
//-----------------------------------------------
//	投稿のカテゴリー出力
//-----------------------------------------------
if( ! function_exists( 'ys_template_the_post_categorys' ) ) {
	function ys_template_the_post_categorys($number = 0,$link=true,$separator=', ',$postid=0) {
		echo '<svg class="entry-meta-ico" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 20 20"><path d="M0 4c0-1.1.9-2 2-2h7l2 2h7a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2 2v10h16V6H2z"/></svg>'.ys_utilities_get_the_post_categorys($number,$link,$separator,$postid,array('itemprop'=>true));
	}
}




//-----------------------------------------------
//	投稿のカテゴリー一覧出力
//-----------------------------------------------
if( ! function_exists( 'ys_template_the_category_list' ) ) {
	function ys_template_the_category_list($before,$after,$separator=', ',$link=true,$postid=0,$args=array()) {

		$categorys = ys_utilities_get_the_post_categorys(0,$link,$separator,$postid,$args);

		echo $before;
		echo $categorys;
		echo $after;
	}
}




//-----------------------------------------------
//	投稿のタグ一覧出力
//-----------------------------------------------
if( ! function_exists( 'ys_template_the_tag_list' ) ) {
	function ys_template_the_tag_list($before,$after,$separator=', ',$link=true,$postid=0) {

		$tags = ys_utilities_get_the_tag_list($separator,$link,$postid);

		if($tags != ''){
			echo $before;
			echo $tags;
			echo $after;
		}
	}
}




//-----------------------------------------------
//	投稿のタグ一覧出力
//-----------------------------------------------
if( ! function_exists( 'ys_template_the_taxonomy_list' ) ) {
	function ys_template_the_taxonomy_list() {

		echo '<div class="entry-footer-container">';

		// カテゴリー
		ys_template_the_category_list('<aside class="entry-category-list"><h2>カテゴリー</h2>','</aside>','',true,0);
		// タグ
		ys_template_the_tag_list('<aside class="entry-tag-list"><h2>タグ</h2>','</aside>','');

		echo '</div>';

	}
}




//------------------------------------------------------------------------------
//
//	画像関連の関数
//
//------------------------------------------------------------------------------

if (function_exists( 'ys_template_get_the_post_thumbnail')) {
	function ys_template_get_the_post_thumbnail(
																	$thumbname='full',
																	$viewsize=false,
																	$outputmeta=true,
																	$imgid='',
																	$imgclass='',
																	$postid=0
																) {

		if($postid == 0){
			$postid = get_the_ID();
		}

		$html = '';
		$image = null;
		$thumbname = apply_filters( 'ys_the_post_thumbnail_thumbname', $thumbname, $postid );
		$imgid = apply_filters( 'ys_the_post_thumbnail_id', $imgid, $thumbname, $postid );
		$imgclass = apply_filters( 'ys_the_post_thumbnail_class', $imgclass, $thumbname, $postid );

		if( has_post_thumbnail( $postid ) ) {

			$post_thumbnail_id = get_post_thumbnail_id( $postid );

			if( $post_thumbnail_id ) {
				$image = wp_get_attachment_image_src( $post_thumbnail_id, $thumbname );
			}

			$attr = array();
			if( $imgid ) $attr = wp_parse_args( array( 'id'=>$imgid ), $attr );
			if( $imgclass ) $attr = wp_parse_args( array( 'class'=>$imgclass ), $attr );

			$html = get_the_post_thumbnail( $postid, $thumbname, $attr );

		}

		if( '' == $html ) {

			// 画像を取得
			$image = ys_utilities_get_post_thumbnail($thumbname,'',$postid);

			// id確認
			if($imgid !== ''){
				$imgid = ' id="'.$imgid.'"';
			}
			// class確認
			if($imgclass !== ''){
				$imgclass = ' class="'.$imgclass.'"';
			}

			if( !is_array( $viewsize ) ){
				$viewsize = array($image[1],$image[2]);
			} else {
				if( 0 == $viewsize[0] ){
					$viewsize[0] = $image[1];
				}
				if( 0 == $viewsize[1] ){
					$viewsize[1] = $image[2];
				}
			}

			$html = '<img '.$imgid.$imgclass.'src="'.$image[0].'" '.image_hwstring($viewsize[0],$viewsize[1]).' alt="" />';

		}

		$html = apply_filters( 'ys_the_post_thumbnail', $html, $image, $thumbname, $postid );
		$html =  ys_utilities_get_the_convert_amp_img($html);

		//metaタグを出力
		if($outputmeta){
			$html .= ys_utilities_get_the_image_object_meta($image);
		}

		return $html;
	}
}

/**
 *	画像取得（出力）
 */
// if (!function_exists( 'ys_template_the_post_thumbnail')) {
// 	function ys_template_the_post_thumbnail(
// 																	$thumbname='full',
// 																	$viewsize=false,
// 																	$outputmeta=true,
// 																	$imgid='',
// 																	$imgclass='',
// 																	$postid=0
// 																) {
//
// 		echo ys_template_get_the_post_thumbnail( $thumbname, $viewsize, $outputmeta, $imgid, $imgclass, $postid );
// 	}
// }



//-----------------------------------------------
//	ユーザー画像出力
//-----------------------------------------------
if (!function_exists( 'ys_template_the_user_avatar')) {
	function ys_template_the_user_avatar($author_id = null,$size = 96,$args=array()){

		echo ys_utilities_get_the_user_avatar_img($author_id,$size,$args);
	}
}








//------------------------------------------------------------------------------
//
//	広告関連
//
//------------------------------------------------------------------------------

//-----------------------------------------------
//	広告:記事タイトル下
//-----------------------------------------------
// if( ! function_exists( 'ys_template_the_advertisement_under_title' ) ) {
// 	function ys_template_the_advertisement_under_title() {
//
// 		$key = 'ys_advertisement_under_title';
// 		if(ys_is_mobile()){
// 			$key = 'ys_advertisement_under_title_sp';
// 		}
// 		if(ys_is_amp()){
// 			$key = 'ys_amp_advertisement_under_title';
// 		}
//
// 		$ad = '';
// 		$ad = ys_get_setting($key);
// 		echo apply_filters('ys_advertisement_under_title',ys_template_get_the_advertisement_format($ad));
//
// 	}
// }





//------------------------------------------------------------------------------
//
//	カスタム属性
//
//------------------------------------------------------------------------------
//-----------------------------------------------
//	フッター
//-----------------------------------------------
if( ! function_exists( 'ys_template_the_footer_attr' ) ) {
	function ys_template_the_footer_attr() {
		echo apply_filters('ys_the_footer_attr','');
	}
}



?>