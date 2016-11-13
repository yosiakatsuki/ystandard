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
		<h2 class="hndle">AMP記事作成条件設定</h2>
		<div class="inside">
			<table class="form-table">
				<tr valign="top">
					<th scope="row">AMP有効化</th>
					<td>
						<label for="ys_amp_enable">
							<input type="checkbox" name="ys_amp_enable" value="1" <?php checked(get_option('ys_amp_enable',0),1); ?> />AMPページを生成する
						</label>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">AMP記事作成条件設定</th>
					<td>
						<fieldset>
							<p>
								<label for="ys_amp_del_script">
									<input type="checkbox" name="ys_amp_del_script" value="1" <?php checked(get_option('ys_amp_del_script',0),1); ?> /><code>&lt;script&gt;</code>タグを削除してAMPページを作成する
								</label><br />
								※この設定をONにするとjavascriptを使う必要があるページが正しく表示されなくなる可能性があります。
							</p>
							<p>
								<label for="ys_amp_del_style">
									<input type="checkbox" name="ys_amp_del_style" value="1" <?php checked(get_option('ys_amp_del_style',0),1); ?> /><code>style</code>属性を削除してAMPページを作成する
								</label><br />
								※この設定をONにするとインラインで定義しているスタイルが適用されなくなります。
							</p>
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