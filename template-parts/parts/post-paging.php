<?php
/**
 * 前の記事・次の記事テンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

if ( empty( $paging ) ) {
	return;
}
?>
<div class="paging">
	<div class="paging__container">
		<?php if ( $paging['prev'] ) : ?>
			<div class="paging__item is-prev">
				<div class="paging__arrow">
					<?php echo ys_get_icon( 'chevron-left' ); ?>
				</div>
				<?php if ( has_post_thumbnail( $paging['prev'] ) ) : ?>
					<figure class="paging__image">
						<?php echo get_the_post_thumbnail( $paging['prev'] ); ?>
					</figure>
				<?php endif; ?>
				<p class="paging__title">
					<a href="<?php the_permalink( $paging['prev'] ); ?>">
						<?php echo get_the_title( $paging['prev'] ); ?>
					</a>
				</p>
			</div>
		<?php endif; ?>
		<?php if ( $paging['next'] ) : ?>
			<div class="paging__item is-next">
				<div class="paging__arrow">
					<?php echo ys_get_icon( 'chevron-right' ); ?>
				</div>
				<?php if ( has_post_thumbnail( $paging['next'] ) ) : ?>
					<figure class="paging__image">
						<?php echo get_the_post_thumbnail( $paging['next'] ); ?>
					</figure>
				<?php endif; ?>
				<p class="paging__title">
					<a href="<?php the_permalink( $paging['next'] ); ?>">
						<?php echo get_the_title( $paging['next'] ); ?>
					</a>
				</p>
			</div>
		<?php endif; ?>
	</div>
</div>
