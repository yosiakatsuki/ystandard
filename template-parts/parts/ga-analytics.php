<?php
/**
 * Google Analytics analytics.js テンプレート
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
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
ga('create', '<?php echo $ys_tracking_id; ?>', 'auto'<?php echo $ys_ga_option; ?>);
ga('send', 'pageview');
</script>
