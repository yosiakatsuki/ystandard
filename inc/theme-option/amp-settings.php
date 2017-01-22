<?php
	if (!current_user_can('manage_options'))
		{
			wp_die( __('You do not have sufficient permissions to access this page.') );
		}
?>

<div class="wrap">
<h2>AMP設定（β版）</h2>
<div id="poststuff">
	<form method="post" action="options.php">

	<?php
		settings_fields( 'ys_amp_settings' );
		do_settings_sections( 'ys_amp_settings' );
	?>

	<div class="postbox">
		<h2 class="hndle">AMP設定</h2>
		<div class="inside">
			<table class="form-table">
				<tr valign="top">
					<th scope="row">FacebookシェアボタンのApp ID</th>
					<td>
						<input type="text" name="ys_amp_share_fb_app_id" id="ys_amp_share_fb_app_id" value="<?php echo esc_attr( get_option('ys_amp_share_fb_app_id') ); ?>" placeholder="000000000000000" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">通常ビューへのリンク表示</th>
					<td>
						<p>
							<label for="ys_amp_normal_link">
								<input type="checkbox" name="ys_amp_normal_link" id="ys_amp_normal_link" value="1" <?php checked(get_option('ys_amp_normal_link',1),1); ?> />コンテンツ上部に通常ビューへのリンクを表示する
							</label>
						</p>
						<p>
							<label for="ys_amp_normal_link_share_btn">
								<input type="checkbox" name="ys_amp_normal_link_share_btn" id="ys_amp_normal_link_share_btn" value="1" <?php checked(get_option('ys_amp_normal_link_share_btn',1),1); ?> />シェアボタン下部に通常ビューへのリンクを表示する
							</label>
						</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">AMP記事作成条件設定</th>
					<td>
						<p>
							<label for="ys_amp_del_script">
								<input type="checkbox" name="ys_amp_del_script" id="ys_amp_del_script" value="1" <?php checked(get_option('ys_amp_del_script',0),1); ?> /><code>&lt;script&gt;</code>タグを削除してAMPページを作成する
							</label><br />
							※この設定をONにするとjavascriptを使う必要があるページが正しく表示されなくなる可能性があります。
						</p>
						<p>
							<label for="ys_amp_del_style">
								<input type="checkbox" name="ys_amp_del_style" id="ys_amp_del_style" value="1" <?php checked(get_option('ys_amp_del_style',0),1); ?> /><code>style</code>属性を削除してAMPページを作成する
							</label><br />
							※この設定をONにするとインラインで定義しているスタイルが適用されなくなります。
						</p>
					</td>
				</tr>
			</table>
		</div>
	</div>

	<div class="postbox">
		<h2 class="hndle">広告設定</h2>
		<div class="inside">
			<table class="form-table">
				<tr valign="top">
					<th scope="row">記事タイトル下</th>
					<td>
						<textarea name="ys_amp_advertisement_under_title" rows="8" cols="80"><?php echo get_option('ys_amp_advertisement_under_title',''); ?></textarea>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">moreタグ部分</th>
					<td>
						<textarea name="ys_amp_advertisement_replace_more" rows="8" cols="80"><?php echo get_option('ys_amp_advertisement_replace_more',''); ?></textarea>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">記事本文下</th>
					<td>
						<textarea name="ys_amp_advertisement_under_content" rows="8" cols="80"><?php echo get_option('ys_amp_advertisement_under_content',''); ?></textarea>
					</td>
				</tr>
			</table>
		</div><!-- /.inside -->
	</div><!-- /.postbox -->

	<?php submit_button(); ?>
	</form>
</div>
</div><!-- /.warp -->