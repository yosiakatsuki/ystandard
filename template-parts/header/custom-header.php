<?php
/**
 * カスタムヘッダーテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

if ( ! ys_is_active_custom_header() ) {
	return;
}
$type = ys_get_custom_header_type();
?>
<div class="ys-custom-header ys-custom-header--<?php echo esc_attr( $type ); ?>">
	<?php ys_the_custom_header_markup(); ?>
</div>
