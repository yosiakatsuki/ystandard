<?php
/**
 * 日付表示テンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

if ( ! ys_is_active_publish_date() ) {
	return;
}

$format = get_option( 'date_format' ); ?>
<div class="singular-date">
	<?php if ( get_the_time( 'Ymd' ) >= get_the_modified_time( 'Ymd' ) ) : ?>
		<?php
		/**
		 * 公開日のみ
		 */
		?>
		<span class="singular-date__published"><i class="far fa-calendar icon-l"></i><time class="updated" datetime="<?php the_time( 'Y-m-d' ); ?>"><?php the_time( $format ); ?></time></span>
	<?php else : ?>
		<?php
		/**
		 * 公開日・更新日
		 */
		?>
		<span class="singular-date__published"><i class="far fa-calendar icon-l"></i><?php the_time( $format ); ?></span>
		<span class="singular-date__update"><i class="fas fa-redo-alt icon-l"></i><time class="updated" datetime="<?php the_modified_time( 'Y-m-d' ); ?>"><?php the_modified_time( $format ); ?></time></span>
	<?php endif; ?>
</div><!-- .entry__date -->