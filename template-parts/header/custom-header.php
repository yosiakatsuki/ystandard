<?php
/**
 * カスタムヘッダーテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 条件がややこしくなってごめんなさい。
 */
if ( ! \ystandard\Custom_Header::is_active_custom_header() ) {
	/**
	 * カスタムヘッダーじゃなければ通常アイキャッチ
	 */
	if ( ! ys_is_full_width_thumbnail() || ! ys_is_active_post_thumbnail() ) {
		/**
		 * フル幅アイキャッチじゃない → ださない
		 * アイキャッチの設定がない → ださない
		 */
		return;
	}
}
?>
<div class="ys-custom-header ys-custom-header--<?php echo esc_attr( ys_get_custom_header_type() ); ?>">
	<?php ys_the_custom_header_markup(); ?>
</div>
