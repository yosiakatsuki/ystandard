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

				<tr valign="top">
					<th scope="row">Twitter用javascript読み込み設定</th>
					<td>
						<label for="ys_load_script_twitter">
							<input type="checkbox" name="ys_load_script_twitter" id="ys_load_script_twitter" value="1" <?php checked(get_option('ys_load_script_twitter',0),1); ?> />Twitter用javascriptを読み込む
						</label>
						<p class="description">※<code>//platform.twitter.com/widgets.js</code>をページ表示時に読み込みます。</p>
						<p class="description">※Twitterのフォローボタンなどをサイト内で使用する場合、こちらにチェックを入れてください</p>
						<p class="description">※テーマ標準ではTwitter公式の埋め込み機能（タイムラインの表示やフォローボタン等）は使っていません</p>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row">Facebook用javascript読み込み設定</th>
					<td>
						<label for="ys_load_script_facebook">
							<input type="checkbox" name="ys_load_script_facebook" id="ys_load_script_facebook" value="1" <?php checked(get_option('ys_load_script_facebook',0),1); ?> />Facebook用javascriptを読み込む
						</label>
						<p class="description">※<code>//connect.facebook.net/ja_JP/sdk.js#xfbml=1&amp;version=v2.8</code>をページ表示時に読み込みます。</p>
						<p class="description">※FacebookのいいねボタンやPagePluginなどをサイト内で使用する場合、こちらにチェックを入れてください</p>
						<p class="description">※テーマ標準ではFacebook公式の埋め込み機能（いいねボタンの表示等）は使っていません</p>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row">CDNにホストされているjQueryを読み込む<br />（URLを設定）</th>
					<td>
						<input type="text" name="ys_load_cdn_jquery_url" id="ys_load_cdn_jquery_url" value="<?php echo esc_url( get_option('ys_load_cdn_jquery_url','') ); ?>" placeholder="http://example.com/jquery.min.js" style="width:100%;" />
						<p class="description">※WordPress標準のjQueryを読み込む場合は空白にしてください（デフォルト）</p>
						<p class="description">※ホストされているjQueryのURLを入力してください。</p>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row">jQueryを読み込まない</th>
					<td>
						<label for="ys_not_load_jquery">
							<input type="checkbox" name="ys_not_load_jquery" id="ys_not_load_jquery" value="1" <?php checked(get_option('ys_not_load_jquery',0),1); ?> />jQueryを読み込まない
						</label>
						<p class="description">※この設定を有効にするとサイト表示高速化が期待できますが、jQueryを使用している処理が動かなくなります。プラグインの動作に影響が出る恐れがありますのでご注意ください</p>
						<p class="description">※テーマ標準のjavascriptではjQueryを使用する機能は使っていません</p>
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