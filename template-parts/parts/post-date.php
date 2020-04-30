<?php
/**
 * 投稿日・更新日表示
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

if ( empty( $post_date ) ) {
	return;
}
?>
<div class="singular-date">
	<?php foreach ( $post_date as $date ) : ?>
		<span class="singular-date__item">
			<?php echo $date['icon']; ?>
			<?php if ( $date['time'] ) : ?>
				<time class="updated" datetime="<?php echo esc_attr( $date['datetime'] ); ?>"><?php echo esc_html( $date['text'] ); ?></time>
			<?php else : ?>
				<?php echo esc_html( $date['text'] ); ?>
			<?php endif; ?>
		</span>
	<?php endforeach; ?>
</div>
