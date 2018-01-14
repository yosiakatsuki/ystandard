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
		<h2>購読ボタン設定</h2>
		<div class="inside">
		<p class="description">※購読ボタンを表示しない場合は空白にしてください</p>
			<table class="form-table">
				<tr valign="top">
					<th scope="row">Twitter</th>
					<td>
						<input type="text" name="ys_subscribe_url_twitter" id="ys_subscribe_url_twitter" value="<?php echo esc_url( get_option('ys_subscribe_url_twitter') ); ?>" placeholder="http://example.com" style="width:100%;" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Facebookページ</th>
					<td>
						<input type="text" name="ys_subscribe_url_facebook" id="ys_subscribe_url_facebook" value="<?php echo esc_url( get_option('ys_subscribe_url_facebook') ); ?>" placeholder="http://example.com" style="width:100%;" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Google+</th>
					<td>
						<input type="text" name="ys_subscribe_url_googleplus" id="ys_subscribe_url_googleplus" value="<?php echo esc_url( get_option('ys_subscribe_url_googleplus') ); ?>" placeholder="http://example.com" style="width:100%;" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Feedly</th>
					<td>
						<input type="text" name="ys_subscribe_url_feedly" id="ys_subscribe_url_feedly" value="<?php echo esc_url( get_option('ys_subscribe_url_feedly') ); ?>" placeholder="http://example.com" style="width:100%;" />
						<p class="description"><a href="https://feedly.com/factory.html" target="_blank">https://feedly.com/factory.html</a>で購読用URLを生成・取得してください。（出来上がったHTMLタグのhref部分）</p>
						<p class="description">おそらく「 <?php echo 'http://cloud.feedly.com/#subscription'.urlencode('/feed/'.get_bloginfo('rss2_url')); ?> 」になると思います。</p>
					</td>
				</tr>
			</table>
			<?php submit_button(); ?>

		</div>
	</div>

	<div class="postbox">
		<h2>サイト表示設定</h2>
		<div class="inside">
			<table class="form-table">
				<tr valign="top">
					<th scope="row">サイドバー表示</th>
					<td>
						<label for="ys_show_sidebar_mobile">
							<input type="checkbox" name="ys_show_sidebar_mobile" id="ys_show_sidebar_mobile" value="1" <?php checked(get_option('ys_show_sidebar_mobile',1),1); ?> />モバイル表示でもサイドバー部分を出力する
						</label>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">絵文字設定</th>
					<td>
						<label for="ys_show_emoji">
							<input type="checkbox" name="ys_show_emoji" id="ys_show_emoji" value="1" <?php checked(get_option('ys_show_emoji',0),1); ?> />絵文字関連スタイルシート・スクリプトを出力する
						</label>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">oembed設定</th>
					<td>
						<label for="ys_show_oembed">
							<input type="checkbox" name="ys_show_oembed" id="ys_show_oembed" value="1" <?php checked(get_option('ys_show_oembed',0),1); ?> />oembed関連スタイルシート・スクリプトを出力する
						</label>
					</td>
				</tr>
			</table>
			<?php submit_button(); ?>

		</div>
	</div>


	<div class="postbox">
		<h2>SNSフォローURL</h2>
		<div class="inside">
			<p class="description">※5種類以上登録すると折り返しが発生し、見た目がイマイチになるかもしれません…</p>
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
			<?php submit_button(); ?>

		</div>
	</div>


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

	<div class="postbox">
		<h2>SEO対策設定</h2>
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
			<?php submit_button(); ?>

		</div><!-- /.inside -->
	</div><!-- /.postbox -->

	<div class="postbox">
		<h2>広告設定</h2>
		<div class="inside">
			<table class="form-table">
				<tr valign="top">
					<th scope="row">記事タイトル下</th>
					<td>
						<p><strong>PC表示用</strong></p>
						<textarea name="ys_advertisement_under_title" rows="8" cols="80"><?php echo esc_textarea(stripslashes(get_option('ys_advertisement_under_title',''))); ?></textarea>
						<p><strong>スマホ表示用</strong></p>
						<textarea name="ys_advertisement_under_title_sp" rows="8" cols="80"><?php echo get_option('ys_advertisement_under_title_sp',''); ?></textarea>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">moreタグ部分</th>
					<td>
						<p><strong>PC表示用</strong></p>
						<textarea name="ys_advertisement_replace_more" rows="8" cols="80"><?php echo esc_textarea(stripslashes(get_option('ys_advertisement_replace_more',''))); ?></textarea>
						<p><strong>スマホ表示用</strong></p>
						<textarea name="ys_advertisement_replace_more_sp" rows="8" cols="80"><?php echo esc_textarea(stripslashes(get_option('ys_advertisement_replace_more_sp',''))); ?></textarea>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">記事本文下（左）</th>
					<td>
						<textarea name="ys_advertisement_under_content_left" rows="8" cols="80"><?php echo esc_textarea(stripslashes(get_option('ys_advertisement_under_content_left',''))); ?></textarea>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">記事本文下（右）</th>
					<td>
						<textarea name="ys_advertisement_under_content_right" rows="8" cols="80"><?php echo esc_textarea(stripslashes(get_option('ys_advertisement_under_content_right',''))); ?></textarea>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">記事本文下（スマホ）</th>
					<td>
						<textarea name="ys_advertisement_under_content_sp" rows="8" cols="80"><?php echo esc_textarea(stripslashes(get_option('ys_advertisement_under_content_sp',''))); ?></textarea>
						<p>※スマホ表示で表示域を覆い尽くすような広告の配置にならないよう、記事下には1つ分しか設定を用意していません。</p>
					</td>
				</tr>
			</table>
		</div><!-- /.inside -->
	</div><!-- /.postbox -->

	<?php submit_button(); ?>
	</form>
</div>
</div><!-- /.warp -->
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/ys-setting-page-style.min.css">