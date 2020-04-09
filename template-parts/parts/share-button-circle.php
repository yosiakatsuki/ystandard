<?php
/**
 * シェアボタン テンプレート : circle
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

if ( empty( $share_button ) ) {
	return;
}
?>
<div class="sns-share is-circle">
	<?php if ( isset( $share_button['text']['before'] ) && $share_button['text']['before'] ) : ?>
		<p class="sns-share__before"><?php echo esc_html( $share_button['text']['before'] ); ?></p>
	<?php endif; ?>
	<ul class="sns-share__container">
		<?php foreach ( $share_button['sns'] as $sns => $url ) : ?>
			<li class="sns-share__button sns-bg--<?php echo esc_attr( $sns ); ?> is-<?php echo esc_attr( $sns ); ?>">
				<a class="sns-share__link" href="<?php echo esc_url_raw( $url ); ?>" target="_blank">
					<?php echo ys_get_sns_icon( $sns ); ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
	<?php if ( isset( $share_button['text']['after'] ) && $share_button['text']['after'] ) : ?>
		<p class="sns-share__after"><?php echo esc_html( $share_button['text']['after'] ); ?></p>
	<?php endif; ?>
</div>
