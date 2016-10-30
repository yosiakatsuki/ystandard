<?php
//------------------------------------------------------------------------------
//
//	管理画面周り
//
//------------------------------------------------------------------------------




//-----------------------------------------------
//	投稿作成メニューに項目追加
//-----------------------------------------------
if (!function_exists( 'ys_admin_add_post_option')) {
	function ys_admin_add_post_option() {
		// 投稿オプション
		add_meta_box( 'ys_admin_post_option', 'ys投稿オプション', 'ys_admin_post_option', 'post','advanced');
		add_meta_box( 'ys_admin_post_option', 'ys投稿オプション', 'ys_admin_post_option', 'page','advanced');
	}
}
add_action('admin_menu', 'ys_admin_add_post_option');




//-----------------------------------------------
//	投稿作成メニューに追加する内容
//-----------------------------------------------
if( ! function_exists( 'ys_admin_post_option' ) ) {
	function ys_admin_post_option() {
		global $post;

		//noindex設定の追加 --------------------------------------------------------------------------------------
		$noindex = get_post_meta($post->ID,'ys_noindex',true);
		$noindex_c = '';
		if($noindex==1){ $noindex_c='checked="checked"';}

		echo '<p><input type="checkbox" name="ys_noindex" value="1" '.$noindex_c.' /> 「noindex,follow」を設定</p>';
		//noindex設定の追加 --------------------------------------------------------------------------------------
	}
}


//-------------------------------------------------------
// カスタムフィールドの値を保存
//-------------------------------------------------------
if( ! function_exists( 'ys_admin_save_post_option' ) ) {
	function ys_admin_save_post_option( $post_id ) {
		//noindex設定の保存
		if(!empty($_POST['ys_noindex'])){
			update_post_meta($post_id, 'ys_noindex', $_POST['ys_noindex'] );
		} else {
			delete_post_meta($post_id, 'ys_noindex');
		}
	}
}
add_action('save_post', 'ys_admin_save_post_option');




//-------------------------------------------------------
// ユーザー連絡先情報追加
//-------------------------------------------------------
if( ! function_exists( 'ys_admin_add_contactmethods' ) ) {
	function ys_admin_add_contactmethods($wb)
	{
		//項目の追加
		$wb['twitter'] = 'Twitter';
		$wb['facebook'] = 'Facebook';
		$wb['googleplus'] = 'Google+';
		$wb['instargram'] = 'Instargram';
		$wb['tumblr'] = 'Tumblr';
		$wb['youtube'] = 'Youtube';
		$wb['vine'] = 'vine';
		$wb['github'] = 'GitHub';
		$wb['pinterest'] = 'Pinterest';
		$wb['linkedIn'] = 'LinkedIn';

		return $wb;
	}
}
add_filter('user_contactmethods', 'ys_admin_add_contactmethods');




//-------------------------------------------------------
// カスタムユーザー画像を追加
//-------------------------------------------------------
if( ! function_exists( 'ys_admin_show_custom_authorimage' ) ) {
	function ys_admin_show_custom_authorimage($bool){
global $profileuser;
		if ( preg_match( '/^(profile\.php|user-edit\.php)/', basename( $_SERVER['REQUEST_URI'] ) ) ) {

	?>
	<tr>
		<th><label for="ys_admin_show_custom_authorimage">オリジナルプロフィール画像</label></th>
		<td>
			<div id="ys_admin_show_custom_authorimage_preview" class="ys-custom-image-upload-preview">
				<?php
					$authorimage = get_user_meta($profileuser->ID, 'ys_custom_authorimage', true);
					if($authorimage !== ''){
						echo "<img style=\"max-width:100px;height:auto;\" src=\"$authorimage\" />";
					} else {
						echo '画像が選択されてません。';
					}
				?>
			</div>
			<input type="text" id="ys_custom_authorimage" name="ys_custom_authorimage" class="regular-text ys-custom-image-upload-url" value="<?php echo get_user_meta($profileuser->ID, 'ys_custom_authorimage', true);?>" />
			<?php
				$uploadbutton_hidden = '';
				$clearbutton_hidden = 'hidden';
				if($authorimage !== ''):
					$uploadbutton_hidden = 'hidden';
					$clearbutton_hidden = '';
		 		endif;
			?>
				<button id="ys_admin_show_custom_authorimage_upload" class="button ys-custom-image-upload" type="button" <?php echo $uploadbutton_hidden ?>>プロフィール画像をアップロード</button>
				<button id="ys_admin_show_custom_authorimage_clear" class="button ys-custom-image-clear" type="button" <?php echo $clearbutton_hidden ?>>プロフィール画像を削除</button>
			<p class="description">100px×100pxの正方形で表示されます。正方形の画像を用意すると綺麗に表示されます。</p>
		</td>
	</tr>

	<?php
		}
		return $bool;
	}
}
add_action( 'show_password_fields', 'ys_admin_show_custom_authorimage' );




//-------------------------------------------------------
// カスタムユーザー画像の保存
//-------------------------------------------------------
if( ! function_exists( 'ys_admin_save_custom_authorimage' ) ) {
  function ys_admin_save_custom_authorimage( $user_id, $old_user_data ) {
      if ( isset( $_POST['ys_custom_authorimage'] )
            && $old_user_data->ys_custom_authorimage != $_POST['ys_custom_authorimage'] ) {

          $ys_custom_authorimage = sanitize_text_field( $_POST['ys_custom_authorimage'] );
          $ys_custom_authorimage = wp_filter_kses( $ys_custom_authorimage );
          $ys_custom_authorimage = _wp_specialchars( $ys_custom_authorimage );
          update_user_meta( $user_id, 'ys_custom_authorimage', $ys_custom_authorimage );
      }
  }
}
add_action( 'profile_update', 'ys_admin_save_custom_authorimage', 10, 2 );

?>