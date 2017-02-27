<?php
	if (!current_user_can('manage_options'))
		{
			wp_die( __('You do not have sufficient permissions to access this page.') );
		}
?>

<div class="wrap">
<h2>高度な設定</h2>
<div id="poststuff">
	<form method="post" action="options.php">

	<?php
		settings_fields( 'ys_advanced_settings' );
		do_settings_sections( 'ys_advanced_settings' );
	?>

	<div class="postbox">
		<h2>投稿設定</h2>
		<div class="inside">
			<table class="form-table">
				<tr valign="top">
					<th scope="row">アイキャッチ画像非表示</th>
					<td>
						<label for="ys_hide_post_thumbnail">
							<input type="checkbox" name="ys_hide_post_thumbnail" id="ys_hide_post_thumbnail" value="1" <?php checked(get_option('ys_hide_post_thumbnail',0),1); ?> />投稿ページでアイキャッチ画像を表示しない
						</label>
						<p class="description">※投稿内の先頭にアイキャッチ画像を配置している場合、こちらの設定を有効にすることにより画像が2枚連続で表示されないようにします。（他プログサービスからの引っ越してきた場合に役立つかもしれません）</p>
					</td>
				</tr>
			</table>
		</div>
	</div>

	<div class="postbox">
		<h2>css,javascript設定</h2>
		<div class="inside">
			<table class="form-table">
				<tr valign="top">
					<th scope="row">テーマカスタマイザーでの色変更</th>
					<td>
						<label for="ys_desabled_color_customizeser">
							<input type="checkbox" name="ys_desabled_color_customizeser" id="ys_desabled_color_customizeser" value="1" <?php checked(get_option('ys_desabled_color_customizeser',0),1); ?> />テーマカスタマイザーでの色変更機能を無効にする
						</label>
						<p class="description">※ご自身でCSSを調整する場合はこちらのチェックをいれてください。余分なCSSコードが出力されなくなります</p>
					</td>
				</tr>
			</table>
		</div>
	</div>

	<div class="postbox">
		<h2>AMP有効化</h2>
		<div class="inside">
			<table class="form-table">
				<tr valign="top">
					<th scope="row">AMP有効化</th>
					<td>
						<label for="ys_amp_enable">
							<input type="checkbox" name="ys_amp_enable" id="ys_amp_enable" value="1" <?php checked(get_option('ys_amp_enable',0),1); ?> />AMPページを生成する
						</label>
						<p class="description">※AMPページの生成を保証するものではありません。使用しているプラグインや投稿内のHTMLタグ、インラインCSS、javascriptコードによりAMPフォーマットとしてエラーとなる場合もあります。</p>
					</td>
				</tr>
			</table>
		</div>
	</div>

	<?php submit_button(); ?>
	</form>
</div>
</div><!-- /.warp -->
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/ys-setting-page-style.min.css">