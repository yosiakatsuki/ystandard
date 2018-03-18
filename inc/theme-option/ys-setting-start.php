<?php
/**
 * 設定スタートページ
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
}
$current_url = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
?>
<div class="wrap">
<h2>yStandardの設定を始める</h2>
<div id="poststuff">
	<div class="postbox">
		<h2>yStandardの設定はテーマカスタマイザーから!</h2>
		<div class="inside">
			<p>yStandardの設定はテーマカスタマイサーから行って下さい</p>
			<p><a href="<?php echo esc_url( add_query_arg( 'return', urlencode( $current_url ), wp_customize_url() ) ); ?>">テーマカスタマイザー</a></p>
		</div>
	</div><!-- ./postbox -->
</div><!-- /#poststuff -->
</div><!-- /.warp -->