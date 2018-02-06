<?php
//------------------------------------------------------------------------------
//
//	管理画面周り
//
//------------------------------------------------------------------------------

//-------------------------------------------------------
// ユーザー連絡先情報追加
//-------------------------------------------------------
if( ! function_exists( 'ys_admin_add_contactmethods' ) ) {
	function ys_admin_add_contactmethods($wb)
	{
		//項目の追加
		$wb['ys_twitter'] = 'Twitter';
		$wb['ys_facebook'] = 'Facebook';
		$wb['ys_googleplus'] = 'Google+';
		$wb['ys_instagram'] = 'Instargram';
		$wb['ys_tumblr'] = 'Tumblr';
		$wb['ys_youtube'] = 'Youtube';
		$wb['ys_github'] = 'GitHub';
		$wb['ys_pinterest'] = 'Pinterest';
		$wb['ys_linkedin'] = 'LinkedIn';

		return $wb;
	}
}
add_filter('user_contactmethods', 'ys_admin_add_contactmethods');




//-------------------------------------------------------
// カスタムユーザー画像を追加
//-------------------------------------------------------
if( ! function_exists( 'ys_admin_show_custom_avatar' ) ) {
	function ys_admin_show_custom_avatar($bool){
global $profileuser;
		if ( preg_match( '/^(profile\.php|user-edit\.php)/', basename( $_SERVER['REQUEST_URI'] ) ) ) {

	?>
	<tr>
		<th><label for="ys_admin_show_custom_avatar">オリジナルプロフィール画像</label></th>
		<td>
			<div id="ys_admin_show_custom_avatar_preview" class="ys-custom-image-upload-preview">
				<?php
					$custom_avatar = get_user_meta($profileuser->ID, 'ys_custom_avatar', true);
					if($custom_avatar !== ''){
						echo "<img style=\"max-width:96px;height:auto;\" src=\"$custom_avatar\" />";
					} else {
						echo '画像が選択されてません。';
					}
				?>
			</div>
			<input type="text" id="ys_custom_avatar" name="ys_custom_avatar" class="regular-text ys-custom-image-upload-url" value="<?php echo get_user_meta($profileuser->ID, 'ys_custom_avatar', true);?>" style="display:none;" />
			<?php
				$uploadbutton_hidden = '';
				$clearbutton_hidden = 'style="display:none;"';
				if($custom_avatar !== ''):
					$uploadbutton_hidden = 'style="display:none;"';
					$clearbutton_hidden = '';
		 		endif;
			?>
				<button id="ys_admin_show_custom_avatar_upload" class="button ys-custom-image-upload" type="button" <?php echo $uploadbutton_hidden ?>>プロフィール画像をアップロード</button>
				<button id="ys_admin_show_custom_avatar_clear" class="button ys-custom-image-clear" type="button" <?php echo $clearbutton_hidden ?>>プロフィール画像を削除</button>
			<p class="description">96px×96pxの正方形で表示されます。正方形の画像を用意すると綺麗に表示されます。</p>
		</td>
	</tr>

	<?php
		}
		return $bool;
	}
}
add_action( 'show_password_fields', 'ys_admin_show_custom_avatar' );




//-------------------------------------------------------
// カスタムユーザー画像の保存
//-------------------------------------------------------
if( ! function_exists( 'ys_admin_save_custom_avatar' ) ) {
  function ys_admin_save_custom_avatar( $user_id, $old_user_data ) {
      if ( isset( $_POST['ys_custom_avatar'] )
            && $old_user_data->ys_custom_avatar != $_POST['ys_custom_avatar'] ) {

          $ys_custom_avatar = sanitize_text_field( $_POST['ys_custom_avatar'] );
          $ys_custom_avatar = wp_filter_kses( $ys_custom_avatar );
          $ys_custom_avatar = _wp_specialchars( $ys_custom_avatar );
          update_user_meta( $user_id, 'ys_custom_avatar', $ys_custom_avatar );
      }
  }
}
add_action( 'profile_update', 'ys_admin_save_custom_avatar', 10, 2 );



//-------------------------------------------------------
// テーマの更新確認
//-------------------------------------------------------
$theme_update_checker = new ThemeUpdateChecker(
																					'ystandard',
																					'https://wp-ystandard.com/download/ystandard/ystandard-info.json'
																				);


?>