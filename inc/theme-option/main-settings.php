<?php
	if (!current_user_can('manage_options'))
		{
			wp_die( __('You do not have sufficient permissions to access this page.') );
		}
?>

<div class="wrap">
<h2><?php echo get_template(); ?> 基本設定</h2>
<div id="poststuff">
	<form method="post" action="options.php">

	<?php
		settings_fields( 'ys_main_settings' );
		do_settings_sections( 'ys_main_settings' );
	?>

	<div class="postbox">
		<h2 class="hndle">Copyright</h2>
		<div class="inside">
			<table class="form-table">
				<tr valign="top">
					<th scope="row">発行年</th>
					<td>
						<input type="text" name="ys_copyright_year" id="ys_copyright_year" value="<?php echo esc_attr( get_option('ys_copyright_year') ); ?>" placeholder="<?php echo date_i18n('Y'); ?>" />
					</td>
				</tr>
			</table>
		</div>
	</div>

	<div class="postbox">
		<h2 class="hndle">アクセス解析設定</h2>
		<div class="inside">
			<table class="form-table">
				<tr valign="top">
					<th scope="row">Google Analytics トラッキングID</th>
					<td>
						<input type="text" name="ys_ga_tracking_id" id="ys_ga_tracking_id" value="<?php echo esc_attr( get_option('ys_ga_tracking_id') ); ?>" placeholder="UA-00000000-0" />
					</td>
				</tr>
			</table>
		</div>
	</div>

	<div class="postbox">
		<h2 class="hndle">シェアボタン設定</h2>
		<div class="inside">
			<table class="form-table">
				<tr valign="top">
					<th scope="row">Twitterシェアボタン設定</th>
					<td>
						<fieldset>
							<label for="ys_sns_share_tweet_via">
								<input type="checkbox" name="ys_sns_share_tweet_via" id="ys_sns_share_tweet_via" value="1" <?php checked(get_option('ys_sns_share_tweet_via',0),1); ?> />Twitterシェアにviaを付加する（要Twitterアカウント名設定）
							</label><br />
							Twitterアカウント名:@<input type="text" name="ys_sns_share_tweet_via_account" id="ys_sns_share_tweet_via_account" value="<?php echo esc_attr( get_option('ys_sns_share_tweet_via_account') ); ?>" placeholder="" />
						</fieldset>
					</td>
				</tr>
			</table>
		</div>
	</div>

	<div class="postbox">
		<h2 class="hndle">OGP・Twitterカード設定</h2>
		<div class="inside">
			<table class="form-table">
				<tr valign="top">
					<th scope="row">Facebook app_id</th>
					<td>
						<input type="text" name="ys_ogp_fb_app_id" id="ys_ogp_fb_app_id" value="<?php echo esc_attr( get_option('ys_ogp_fb_app_id') ); ?>" placeholder="000000000000000" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Facebook admins</th>
					<td>
						<input type="text" name="ys_ogp_fb_admins" id="ys_ogp_fb_admins" value="<?php echo esc_attr( get_option('ys_ogp_fb_admins') ); ?>" placeholder="000000000000000" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Twitterアカウント名</th>
					<td>
						@<input type="text" name="ys_twittercard_user" id="ys_twittercard_user" value="<?php echo esc_attr( get_option('ys_twittercard_user') ); ?>" placeholder="account" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">OGPデフォルト画像</th>
					<td>
						<div id="ys_ogp_default_image" class="ys-custom-image-upload-preview">
							<?php
								$ys_ogp_default_image = get_option('ys_ogp_default_image','');
								if($ys_ogp_default_image !== ''){
									echo "<img style=\"max-width:600px;height:auto;\" src=\"$ys_ogp_default_image\" />";
								} else {
									echo '画像が選択されてません。';
								}
							?>
						</div>
						<input type="text" id="ys_ogp_default_image" name="ys_ogp_default_image" class="regular-text ys-custom-image-upload-url" value="<?php echo get_option('ys_ogp_default_image');?>" style="display:none;" />
						<?php
							$uploadbutton_hidden = '';
							$clearbutton_hidden = 'style="display:none;"';
							if($ys_ogp_default_image !== ''):
								$uploadbutton_hidden = 'style="display:none;"';
								$clearbutton_hidden = '';
					 		endif;
						?>
							<button id="ys_admin_show_custom_avatar_upload" class="button ys-custom-image-upload" type="button" <?php echo $uploadbutton_hidden ?> data-uploaderpreview-width="600">画像を選択</button>
							<button id="ys_admin_show_custom_avatar_clear" class="button ys-custom-image-clear" type="button" <?php echo $clearbutton_hidden ?>>画像を削除</button>
						<p class="description">TOPページ・記事一覧ページ・投稿に画像が含まれなかった時に表示される画像を設定して下さい。（推奨サイズ：縦630px×横1200px）</p>
					</td>
				</tr>
			</table>
		</div>
	</div>

	<div class="postbox">
		<h2 class="hndle">SNSフォローURL</h2>
		<div class="inside">
			<p>※5種類以上登録すると</p>
			<table class="form-table">

				<tr valign="top">
					<th scope="row">Twitter</th>
					<td>
						<input type="text" name="ys_follow_url_twitter" id="ys_follow_url_twitter" value="<?php echo esc_url( get_option('ys_follow_url_twitter') ); ?>" placeholder="http://example.com" style="width:100%;"/>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row">Facebook</th>
					<td>
						<input type="text" name="ys_follow_url_facebook" id="ys_follow_url_facebook" value="<?php echo esc_url( get_option('ys_follow_url_facebook') ); ?>" placeholder="http://example.com" style="width:100%;" />
					</td>
				</tr>

				<tr valign="top">
					<th scope="row">Google+</th>
					<td>
						<input type="text" name="ys_follow_url_googlepuls" id="ys_follow_url_googlepuls" value="<?php echo esc_url( get_option('ys_follow_url_googlepuls') ); ?>" placeholder="http://example.com" style="width:100%;" />
					</td>
				</tr>

				<tr valign="top">
					<th scope="row">Instagram</th>
					<td>
						<input type="text" name="ys_follow_url_instagram" id="ys_follow_url_instagram" value="<?php echo esc_url( get_option('ys_follow_url_instagram') ); ?>" placeholder="http://example.com" style="width:100%;" />
					</td>
				</tr>

				<tr valign="top">
					<th scope="row">Tumblr</th>
					<td>
						<input type="text" name="ys_follow_url_tumblr" id="ys_follow_url_tumblr" value="<?php echo esc_url( get_option('ys_follow_url_tumblr') ); ?>" placeholder="http://example.com" style="width:100%;" />
					</td>
				</tr>

				<tr valign="top">
					<th scope="row">YouTube</th>
					<td>
						<input type="text" name="ys_follow_url_youtube" id="ys_follow_url_youtube" value="<?php echo esc_url( get_option('ys_follow_url_youtube') ); ?>" placeholder="http://example.com" style="width:100%;" />
					</td>
				</tr>

				<tr valign="top">
					<th scope="row">GitHub</th>
					<td>
						<input type="text" name="ys_follow_url_github" id="ys_follow_url_github" value="<?php echo esc_url( get_option('ys_follow_url_github') ); ?>" placeholder="http://example.com" style="width:100%;" />
					</td>
				</tr>

				<tr valign="top">
					<th scope="row">Pinterest</th>
					<td>
						<input type="text" name="ys_follow_url_pinterest" id="ys_follow_url_pinterest" value="<?php echo esc_url( get_option('ys_follow_url_pinterest') ); ?>" placeholder="http://example.com" style="width:100%;" />
					</td>
				</tr>

				<tr valign="top">
					<th scope="row">LinkedIn</th>
					<td>
						<input type="text" name="ys_follow_url_linkedin" id="ys_follow_url_linkedin" value="<?php echo esc_url( get_option('ys_follow_url_linkedin') ); ?>" placeholder="http://example.com" style="width:100%;" />
					</td>
				</tr>

			</table>
		</div>
	</div>


	<div class="postbox">
		<h2 class="hndle">投稿設定</h2>
		<div class="inside">
			<table class="form-table">
				<tr valign="top">
					<th scope="row">同じカテゴリーの関連記事を出力する</th>
					<td>
						<label for="ys_show_post_related">
							<input type="checkbox" name="ys_show_post_related" id="ys_show_post_related" value="1" <?php checked(get_option('ys_show_post_related',1),1); ?> />同じカテゴリーの関連記事を出力する
						</label>
					</td>
				</tr>
			</table>
		</div>
	</div>

	<div class="postbox">
		<h2 class="hndle">SEO対策設定</h2>
		<div class="inside">
			<table class="form-table">
				<tr valign="top">
					<th scope="row">アーカイブページのnoindex</th>
					<td>
						<fieldset>
							<label for="ys_archive_noindex_category">
								<input type="checkbox" name="ys_archive_noindex_category" id="ys_archive_noindex_category" value="1" <?php checked(get_option('ys_archive_noindex_category',0),1); ?> />カテゴリー一覧ページをnoindexにする
							</label><br />
							<label for="ys_archive_noindex_tag">
								<input type="checkbox" name="ys_archive_noindex_tag" id="ys_archive_noindex_tag" value="1" <?php checked(get_option('ys_archive_noindex_tag',1),1); ?> />タグ一覧ページをnoindexにする
							</label><br />
							<label for="ys_archive_noindex_author">
								<input type="checkbox" name="ys_archive_noindex_author" id="ys_archive_noindex_author"  value="1" <?php checked(get_option('ys_archive_noindex_author',1),1); ?> />投稿者一覧ページをnoindexにする
							</label><br />
							<label for="ys_archive_noindex_date">
								<input type="checkbox" name="ys_archive_noindex_date" id="ys_archive_noindex_date" value="1" <?php checked(get_option('ys_archive_noindex_date',1),1); ?> />月別、年別、日別、時間別一覧ページをnoindexにする
							</label>
						</fieldset>
					</td>
				</tr>
			</table>
		</div>
	</div>

	<?php submit_button(); ?>
	</form>
</div>
</div><!-- /.warp -->