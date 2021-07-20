<?php
/**
 * 投稿サムネイル - サイトヘッダー表示
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

defined( 'ABSPATH' ) || die();

if ( empty( $header_thumbnail ) ) {
	return;
}
?>

<figure class="site-header-thumbnail">
	<?php echo $header_thumbnail; ?>
</figure>
