<?php
/**
 * Google Analytics analytics.js テンプレート
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
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
ga('create', '<?php echo $ga_tracking_id; ?>', 'auto'<?php echo $ga_option; ?>);
ga('send', 'pageview');
</script>