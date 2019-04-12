<?php
/**
 * Google Analytics gtag.js テンプレート
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

$ga_tracking_id = ys_get_google_anarytics_tracking_id();
$ga_option      = '';
if ( ys_is_active_amp_client_id_api() ) {
	$ga_option .= "'useAmpClientId': true";
}
if ( '' !== $ga_option ) {
	$ga_option = ', {' . $ga_option . '}';
}
?>
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $ga_tracking_id; ?>"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', '<?php echo $ga_tracking_id; ?>'<?php echo $ga_option; ?>);
</script>