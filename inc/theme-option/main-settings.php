<?php
	if (!current_user_can('manage_options'))
		{
			wp_die( __('You do not have sufficient permissions to access this page.') );
		}
		if ( isset( $_GET['updated'] ) && isset( $_GET['page'] ) ) {
			add_settings_error('general', 'settings_updated', __('Settings saved.'), 'updated');
		}
		settings_errors();
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
		<h2>投稿設定</h2>
		<div class="inside">
			<table class="form-table">
				<tr valign="top">
					<th scope="row">同じカテゴリーの関連記事を出力する</th>
					<td>
						<label for="ys_show_post_related">
							<input type="checkbox" name="ys_show_post_related" id="ys_show_post_related" value="1" <?php checked(get_option('ys_show_post_related',1),1); ?> />
						</label>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">次の記事・前の記事のリンクを出力しない</th>
					<td>
						<label for="ys_hide_post_paging">
							<input type="checkbox" name="ys_hide_post_paging" id="ys_hide_post_paging" value="1" <?php checked(get_option('ys_hide_post_paging',0),1); ?> />
						</label>
					</td>
				</tr>
			</table>
			<?php submit_button(); ?>

		</div>
	</div>

	<?php submit_button(); ?>
	</form>
</div>
</div><!-- /.warp -->
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/ys-setting-page-style.min.css">