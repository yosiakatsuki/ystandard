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
	<h3>簡単設定</h3>
	<p>Twitterユーザー名やFacebookのAppIDなど、複数箇所に入力する必要がある項目についてまとめて設定します</p>
	<p>※このテーマでは役割ごとに別々のSNSアカウントを設定可能です</p>
	<p><strong><a href="<?php echo get_admin_url().'/admin.php?page=ys_collective_settings'; ?>">設定を始める»</a></strong></p>

	<h3>基本設定</h3>
	<p>Google AnalyticsのトラッキングIDやOGP設定など</p>
	<p><strong><a href="<?php echo get_admin_url().'/admin.php?page=ys_main_settings'; ?>">設定を始める»</a></strong></p>

	<h3>高度な設定</h3>
	<p>AMP有効化設定など</p>
	<p><strong><a href="<?php echo get_admin_url().'/admin.php?page=ys_advanced_settings'; ?>">設定を始める»</a></strong></p>

	<h3>AMP設定</h3>
	<p>AMPページ作成の条件や通常ページへのリンク出力設定など</p>
	<p><strong><a href="<?php echo get_admin_url().'/admin.php?page=ys_amp_settings'; ?>">設定を始める»</a></strong></p>
</div>
</div><!-- /.warp -->