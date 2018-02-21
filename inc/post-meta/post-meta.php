<?php
/**
 * post-meta取得
 */
if( ! function_exists( 'ys_get_post_meta' ) ) {
	function ys_get_post_meta( $key, $post_id = 0 ) {
		if( 0 === $post_id ) $post_id = get_the_ID();
		return apply_filters( 'ys_get_post_meta', get_post_meta( $post_id, $key, true ), $key, $post_id ) ;
	}
}
/**
 *	meta box追加
 */
function ys_add_meta_box() {
	/**
	 * 投稿オプション
	 */
	add_meta_box( 'ys_add_post_option', 'yStandard投稿オプション', 'ys_add_post_option', 'post' );
	add_meta_box( 'ys_add_post_option', 'yStandard投稿オプション', 'ys_add_post_option', 'page' );
}
add_action( 'admin_menu', 'ys_add_meta_box' );
/**
 *	投稿オプション追加
 */
function ys_add_post_option() {
	global $post;
	?>
	<h3 class="meta-box__headline" style="font-size:1em;margin-bottom: -0.5em;">SEO設定</h3>
	<p>
		<label for="ys_noindex">
			<input type="checkbox" id="ys_noindex" name="ys_noindex" value="1" <?php checked( ys_get_post_meta( 'ys_noindex', $post->ID ), '1' , true ); ?> />この記事に「noindex,follow」を設定する
		</label>
		<?php echo ys_get_post_meta( 'ys_noindex', $post->ID ); ?>
	</p>
	<h3 class="meta-box__headline" style="font-size:1em;margin-bottom: -0.5em;">投稿オプション</h3>
	<p>
		<label for="ys_hide_ad">
			<input type="checkbox" id="ys_hide_ad" name="ys_hide_ad" value="1" <?php checked( ys_get_post_meta( 'ys_hide_ad', $post->ID ), '1' , true ); ?> />広告を<strong>非表示</strong>にする
		</label><br />
		<label for="ys_hide_share">
			<input type="checkbox" id="ys_hide_share" name="ys_hide_share" value="1" <?php checked( ys_get_post_meta( 'ys_hide_share', $post->ID ), '1' , true ); ?> />シェアボタンを<strong>非表示</strong>にする
		</label><br />
		<label for="ys_hide_follow">
			<input type="checkbox" id="ys_hide_follow" name="ys_hide_follow" value="1" <?php checked( ys_get_post_meta( 'ys_hide_follow', $post->ID ), '1' , true ); ?> />フォローボタンを<strong>非表示</strong>にする
		</label><br />
		<?php if( ys_get_option( 'ys_amp_enable' ) && ys_is_post_type_on_admin( 'post' ) ): ?>
			<label for="ys_post_meta_amp_desable">
				<input type="checkbox" id="ys_post_meta_amp_desable" name="ys_post_meta_amp_desable" value="1" <?php checked( ys_get_post_meta( 'ys_post_meta_amp_desable', $post->ID ), '1' , true ); ?> />AMPページを<strong>作成しない</strong>
			</label>
		<?php endif; ?>
	</p>
	<?php
}
/**
 * 投稿オプションの保存
 */
function ys_save_post( $post_id ) {
		/**
		 * noindex設定
		 */
		ys_save_post_checkbox( $_POST, $post_id, 'ys_noindex' );
		/**
		 * 広告非表示設定
		 */
		ys_save_post_checkbox( $_POST, $post_id, 'ys_hide_ad' );
		/**
		 * シェアボタン非表示設定
		 */
		ys_save_post_checkbox( $_POST, $post_id, 'ys_hide_share' );
		/**
		 * フォローボタン非表示設定
		 */
		ys_save_post_checkbox( $_POST, $post_id, 'ys_hide_follow' );
		/**
		 * AMPページ作成設定
		 */
		ys_save_post_checkbox( $_POST, $post_id, 'ys_post_meta_amp_desable' );
}
add_action( 'save_post', 'ys_save_post' );
/**
 * 投稿オプションの更新：チェックボックス
 */
function ys_save_post_checkbox( $post, $post_id, $key ) {

	if( isset( $post[$key] ) && ! empty( $post[$key] ) && '1' === $post[$key] ){
		update_post_meta( $post_id, $key, $post[$key] );
	} else {
		delete_post_meta( $post_id, $key );
	}
}