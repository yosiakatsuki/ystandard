<?php
/**
 * Google Analytics gtag.js テンプレート
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

if ( ! isset( $ys_tracking_id ) || empty( $ys_tracking_id ) ) {
	return;
}
?>
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $ys_tracking_id; ?>"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', '<?php echo $ys_tracking_id; ?>', { 'useAmpClientId': true } );
</script>
