<?php
/**
 * Google Analytics gtag.js テンプレート
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

defined( 'ABSPATH' ) || die();

$ys_tracking_id     = $args['ys_tracking_id'] ?? '';
$ys_tracking_option = $args['ys_tracking_option'] ?? [];

// 呼び出し元の判定を迂回して直接読み込まれても無効なタグを出力しない.
if ( ! preg_match( '/^G-[A-Z0-9]+$/', $ys_tracking_id ) ) {
	return;
}
$ys_ga_config = wp_json_encode( $ys_tracking_id );
// 追加設定がある場合だけgtagの設定オブジェクトを付加する.
if ( ! empty( $ys_tracking_option ) ) {
	$ys_ga_config .= ', ' . wp_json_encode( $ys_tracking_option );
}
?>
<script async src="<?php echo esc_url( 'https://www.googletagmanager.com/gtag/js?id=' . rawurlencode( $ys_tracking_id ) ); ?>"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', <?php echo $ys_ga_config; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>);
</script>
