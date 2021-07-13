<?php
/**
 * Google Analytics gtag.js テンプレート
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

defined( 'ABSPATH' ) || die();

if ( ! isset( $ys_tracking_id ) || empty( $ys_tracking_id ) ) {
	return;
}
$ys_ga_option = '';
if ( ! empty( $ys_tracking_option ) ) {
	$ys_ga_option = ' ,' . $ys_tracking_option;
}
?>
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $ys_tracking_id; ?>"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', '<?php echo $ys_tracking_id; ?>'<?php echo $ys_ga_option; ?> );
</script>
