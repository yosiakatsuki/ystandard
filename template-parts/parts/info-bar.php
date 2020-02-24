<?php
/**
 * お知らせバー
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 設定取得
 */
$text   = ys_get_option( 'ys_info_bar_text' );
$url    = ys_get_option( 'ys_info_bar_url' );
$target = ys_get_option_by_bool( 'ys_info_bar_external' ) ? '_blank' : '_self';

if ( empty( $text ) ) {
	return;
}
$info_bar_class = array(
	'info-bar',
);
if ( $url ) {
	$info_bar_class[] = 'has-link';
}
?>
<div class="<?php echo implode( ' ', $info_bar_class ); ?>">
	<?php if ( $url ) : ?>
		<a href="<?php echo esc_url_raw( $url ); ?>" class="info-bar__link container" target="<?php echo esc_attr( $target ); ?>">
			<span class="info-bar__text"><?php echo wp_kses_post( $text ); ?></span>
		</a>
	<?php else : ?>
		<div class="container">
			<span class="info-bar__text"><?php echo wp_kses_post( $text ); ?></span>
		</div>
	<?php endif; ?>
</div>
