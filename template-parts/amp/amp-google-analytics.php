<amp-analytics type="googleanalytics" id="analytics1">
	<script type="application/json">
	{
		"vars": {
			"account": "<?php echo $ga_tracking_id_amp; ?>"
		},
		"triggers": {
			"trackPageview": {
				"on": "visible",
				"request": "pageview"
			}
		}
	}
	</script>
</amp-analytics>