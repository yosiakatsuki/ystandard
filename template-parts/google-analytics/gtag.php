<?php $ga_tracking_id = ys_get_google_anarytics_tracking_id(); ?>
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $ga_tracking_id; ?>"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', '<?php echo $ga_tracking_id; ?>');
</script>