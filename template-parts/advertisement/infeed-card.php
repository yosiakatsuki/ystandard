<?php
/**
 * インフィード広告テンプレート
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

?>
<aside class="entry-list <?php echo ys_get_archive_card_col(); ?>">
	<div class="card">
		<?php ys_the_ad_infeed(); ?>
	</div>
</aside>
