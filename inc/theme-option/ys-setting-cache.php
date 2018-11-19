<?php
/**
 * キャッシュ管理ページ
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
}

/**
 * 関数群呼び出し
 */
require_once get_template_directory() . '/inc/theme-option/ys-setting-cache-function.php';
/**
 * 削除処理等
 */
$result = ys_setting_cache_post();

?>
<div class="wrap">
	<h2>キャッシュ管理</h2>
	<?php if ( $result ) : ?>
		<div class="notice notice-success is-dismissible">
			<p><?php echo $result; ?></p>
		</div>
	<?php endif; ?>
	<div class="ystandard-settings">
		<div class="ystandard-settings__section">
			<form method="post" action="" id="cache-clear">
				<p>テーマ内で作成したキャッシュの件数確認・削除を行います。</p>
				<table class="ystandard-setting-table" border="0">
					<thead>
					<tr>
						<th>種類</th>
						<td>件数</td>
						<td></td>
					</tr>
					</thead>
					<tbody>
					<tr>
						<th>人気記事ランキング</th>
						<td><?php echo ys_setting_cache_get_ranking_count(); ?></td>
						<td><input type="submit" name="delete[ranking]" id="submit" class="button button-primary" value="キャッシュを削除"></td>
					</tr>
					<th>カテゴリー・タグの記事一覧</th>
					<td><?php echo ys_setting_cache_get_tax_posts_count(); ?></td>
						<td><input type="submit" name="delete[tax_posts]" id="submit" class="button button-primary" value="キャッシュを削除"></td>
					</tr>
					<tr>
						<th>記事下エリア「関連記事」</th>
						<td><?php echo ys_setting_cache_get_related_posts_count(); ?></td>
						<td><input type="submit" name="delete[related_posts]" id="submit" class="button button-primary" value="キャッシュを削除"></td>
					</tr>
					<tr>
					<?php do_action( 'ys_settings_cache_table_row' ); ?>
					</tbody>
				</table>
				<p><input type="submit" name="delete_all" id="submit" class="button button-primary" value="すべてのキャッシュを削除"></p>
			</form>
		</div>
	</div>
</div><!-- /.warp -->
