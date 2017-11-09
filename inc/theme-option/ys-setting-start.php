<?php
	if (!current_user_can('manage_options'))
		{
			wp_die( __('You do not have sufficient permissions to access this page.') );
		}
	$theme_name = get_template();
?>

<div class="wrap">
<h2><?php echo $theme_name; ?>の設定を始める</h2>
<div id="poststuff">
	<div class="postbox">
		<h2>簡単設定</h2>
		<div class="inside">
			<p>Twitterユーザー名やFacebookのAppIDなど、複数箇所に入力する必要がある項目についてまとめて設定します</p>
			<p>※このテーマでは役割ごとに別々のSNSアカウントを設定可能です</p>
			<p><strong><a href="<?php echo get_admin_url().'/admin.php?page=ys_collective_settings'; ?>">設定を始める»</a></strong></p>
		</div>
	</div>

	<div class="postbox">
		<h2>基本設定</h2>
		<div class="inside">
			<p>Google AnalyticsのトラッキングIDやOGP設定など</p>
			<p><strong><a href="<?php echo get_admin_url().'/admin.php?page=ys_main_settings'; ?>">設定を始める»</a></strong></p>
		</div>
	</div>

	<div class="postbox">
		<h2>高度な設定</h2>
		<div class="inside">
			<p>css,javascriptの読み込み設定、AMP有効化設定など</p>
			<p><strong><a href="<?php echo get_admin_url().'/admin.php?page=ys_advanced_settings'; ?>">設定を始める»</a></strong></p>
		</div>
	</div>

	<div class="postbox">
		<h2>AMP設定</h2>
		<div class="inside">
			<p>AMPページ作成の条件や通常ページへのリンク出力設定など</p>
			<?php if( 0 == ys_get_setting( 'ys_amp_enable' ) ): ?>
				<p><strong>※AMPページを生成が有効になっていません。</strong><br><a href="<?php echo get_admin_url().'/admin.php?page=ys_advanced_settings'; ?>">高度な設定</a>から「AMPページを生成する」にチェックを入れて下さい</p>
			<?php else: ?>
				<p><strong><a href="<?php echo get_admin_url().'/admin.php?page=ys_amp_settings'; ?>">設定を始める»</a></strong></p>
			<?php endif; ?>
		</div>
	</div>

</div><!-- /#poststuff -->
</div><!-- /.warp -->