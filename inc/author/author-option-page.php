<?php
/**
 * 投稿者 管理画面関連処理
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * SNS関連の情報入力域追加
 *
 * @param array $wb wb.
 *
 * @return array
 */
function ys_add_contact_methods( $wb ) {

	$sns = ys_get_sns_icons();
	foreach ( $sns as $key => $value ) {
		$wb[ 'ys_' . $key ] = $value['label'];
	}

	return $wb;
}

add_filter( 'user_contactmethods', 'ys_add_contact_methods' );

/**
 * カスタムユーザー画像追加
 *
 * @param bool $bool bool.
 *
 * @return bool
 */
function ys_add_custom_avatar_option( $bool ) {
	global $profileuser;
	if ( preg_match( '/^(profile\.php|user-edit\.php)/', basename( $_SERVER['REQUEST_URI'] ) ) ) :
		?>
		<tr>
			<th><label for="ys_admin_show_custom_avatar">オリジナルプロフィール画像</label></th>
			<td>
				<div id="ys_admin_show_custom_avatar_preview" class="ys-custom-image-upload-preview">
					<?php
					$custom_avatar = get_user_meta( $profileuser->ID, 'ys_custom_avatar', true );
					if ( '' !== $custom_avatar ) {
						echo "<img style=\"max-width:96px;height:auto;\" src=\"$custom_avatar\" />";
					} else {
						echo '画像が選択されてません。';
					}
					?>
				</div>
				<input type="text" id="ys_custom_avatar" name="ys_custom_avatar" class="regular-text ys-custom-image-upload-url" value="<?php echo get_user_meta( $profileuser->ID, 'ys_custom_avatar', true ); ?>" style="display:none;"/>
				<?php
				$uploadbutton_hidden = '';
				$clearbutton_hidden  = 'style="display:none;"';
				if ( '' !== $custom_avatar ) :
					$uploadbutton_hidden = 'style ="display:none;"';
					$clearbutton_hidden  = '';
				endif;
				?>
				<button id="ys_admin_show_custom_avatar_upload" class="button ys-custom-image-upload" type="button" <?php echo $uploadbutton_hidden; ?>>プロフィール画像をアップロード
				</button>
				<button id="ys_admin_show_custom_avatar_clear" class="button ys-custom-image-clear" type="button" <?php echo $clearbutton_hidden; ?>>プロフィール画像を削除
				</button>
				<p class="description">96px×96pxの正方形で表示されます。正方形の画像を用意すると綺麗に表示されます。</p>
			</td>
		</tr>
	<?php
	endif;

	return $bool;
}

add_action( 'show_password_fields', 'ys_add_custom_avatar_option' );

/**
 * カスタムユーザー画像の保存
 *
 * @param int $user_id       ユーザーID.
 * @param int $old_user_data 変更前ユーザーID.
 *
 * @return void
 */
function ys_save_custom_avatar_option( $user_id, $old_user_data ) {
	if ( isset( $_POST['ys_custom_avatar'] ) && $old_user_data->ys_custom_avatar !== $_POST['ys_custom_avatar'] ) {
		$ys_custom_avatar = sanitize_text_field( $_POST['ys_custom_avatar'] );
		$ys_custom_avatar = wp_filter_kses( $ys_custom_avatar );
		$ys_custom_avatar = _wp_specialchars( $ys_custom_avatar );
		update_user_meta( $user_id, 'ys_custom_avatar', $ys_custom_avatar );
	}
}

add_action( 'profile_update', 'ys_save_custom_avatar_option', 10, 2 );