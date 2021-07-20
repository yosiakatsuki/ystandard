<?php
/**
 * お知らせバー
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

defined( 'ABSPATH' ) || die();

if ( empty( $info_bar_data ) ) {
	return;
}

?>
<div class="<?php echo esc_attr( $info_bar_data['class'] ); ?>">
	<?php if ( $info_bar_data['url'] ) : ?>
		<a href="<?php echo esc_url_raw( $info_bar_data['url'] ); ?>" class="info-bar__link container" target="<?php echo esc_attr( $info_bar_data['target'] ); ?>">
			<span class="info-bar__text"><?php echo wp_kses_post( $info_bar_data['text'] ); ?></span>
		</a>
	<?php else : ?>
		<div class="container">
			<span class="info-bar__text"><?php echo wp_kses_post( $info_bar_data['text'] ); ?></span>
		</div>
	<?php endif; ?>
</div>
