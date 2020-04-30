<?php
/**
 * カスタムヘッダーテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

if ( ! ys_is_active_header_media() ) {
	return;
}
?>
<div class="header-media is-<?php echo esc_attr( ys_get_header_media_type() ); ?>">
	<?php ys_the_header_media_markup(); ?>
</div>
