<?php
/**
 * カスタムヘッダーテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

if ( ! ys_is_active_custom_header() && ! ys_is_full_width_thumbnail() ) {
	return;
} else {
	if ( is_singular() && ! ys_is_active_post_thumbnail() ) {
		return;
	}
}
?>
<div class="ys-custom-header ys-custom-header--<?php echo esc_attr( ys_get_custom_header_type() ); ?>">
	<?php ys_the_custom_header_markup(); ?>
</div>
