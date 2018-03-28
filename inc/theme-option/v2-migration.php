<?php
	if ( ! current_user_can('manage_options') ) {
			wp_die( __('You do not have sufficient permissions to access this page.') );
		}
		if ( isset( $_GET['updated'] ) && isset( $_GET['page'] ) ) {
			add_settings_error('general', 'settings_updated', __('Settings saved.'), 'updated');
		}
		settings_errors();
?>

<div class="wrap">
<h2><?php echo get_template(); ?>Version2へのアップグレード</h2>
<div id="poststuff">
	<form method="post" action="options.php">

	<?php
		settings_fields( 'ys_v2_migration' );
		do_settings_sections( 'ys_v2_migration' );
	?>

	<div class="postbox">
		<h2>Version2へのアップグレード機能を有効化する</h2>
		<div class="inside">
			<p>最新のyStandardへのアップデート機能を有効にします</p>
			<p>※yStandard Version2ではこれまでのyStandardとは構造が大きく変わります。子テーマなどでカスタマイズをしている場合はご注意下さい</p>
			<h3>注意点</h3>
			<ul>
				<li>・OGPデフォルト画像の再設定が必要な場合があります</li>
				<li>・ヘッダーメニュー(グローバルメニュー)の再設定が必要になります</li>
				<li>・Google Analyticsのトラッキングコードタイプ</li>
				<li>・サイドバーのモバイル表示（表示するがデフォルトになる）</li>
				<li>・絵文字関連スタイルシート・スクリプトの無効化（設定項目が変わるので「出力する」にしていた場合再設定が必要）</li>
				<li>・oembed関連スタイルシート・スクリプトの無効化（設定項目が変わるので「出力する」にしていた場合再設定が必要）</li>
				<li>・サイドバーのidが変わるのでサイドバー項目の再設定が必要</li>
			</ul>
			<h3>アップグレード機能を有効化する</h3>
			<label for="ys_v2_migration">
				<input type="checkbox" name="ys_v2_migration" id="ys_v2_migration" value="1" <?php checked(get_option('ys_v2_migration',0),1); ?> />
				<strong>Version2へのアップグレード機能を有効化する</strong>
			</label>
			<p>設定を更新後、「ダッシュボード」→「更新」からテーマの更新を実施して下さい</p>
			<p>（テーマの更新が表示されない場合、少し時間をおいてから再度確認して下さい）</p>
		</div>
	</div>

	<?php submit_button(); ?>
	</form>
</div>
</div><!-- /.warp -->
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/ys-setting-page-style.min.css">
