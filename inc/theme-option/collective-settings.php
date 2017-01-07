<?php
	if (!current_user_can('manage_options'))
		{
			wp_die( __('You do not have sufficient permissions to access this page.') );
		}
?>

<div class="wrap">
<h2>簡単設定</h2>
<div id="poststuff">
	<form method="post" action="options.php">

	<?php
		settings_fields( 'ys_advanced_settings' );
		do_settings_sections( 'ys_advanced_settings' );
	?>

	<div class="postbox">
		<h2 class="hndle">AMP設定</h2>
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
			</table>
		</div>
	</div>

	<?php submit_button(); ?>
	</form>
</div>
</div><!-- /.warp -->