<?php
/**
 * 日付表示テンプレート
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

$format = get_option( 'date_format' ); ?>
<div class="entry__date">
<?php
if ( get_the_time( 'Ymd' ) >= get_the_modified_time( 'Ymd' ) ) :
	/**
	 * 公開日のみ
	 */
?>
	<span class="entry__published"><i class="fa fa-calendar entry__date-icon" aria-hidden="true"></i><time datetime="<?php the_time( 'Y-m-d' ); ?>"><?php the_time( $format ); ?></time></span>
<?php
else :
	/**
	 * 公開日・更新日
	 */
?>
	<span class="entry__published"><i class="fa fa-calendar entry__date-icon" aria-hidden="true"></i><?php the_time( $format ); ?></span>
	<span class="entry__update"><i class="fa fa-refresh entry__date-icon" aria-hidden="true"></i><time datetime="<?php the_modified_time( 'Y-m-d' ); ?>"><?php the_modified_time( $format ); ?></time></span>
<?php endif; ?>
</div><!-- .entry__date -->