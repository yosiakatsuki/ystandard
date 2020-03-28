<?php
/**
 * 前の記事・次の記事テンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

if ( empty( $ys_paging ) ) {
	return;
}
?>
<div class="paging">
	<div class="paging__container">
		<?php if ( $ys_paging['prev'] ) : ?>
			<div class="paging__item is-prev">
				<?php if ( has_post_thumbnail( $ys_paging['prev'] ) ) : ?>
					<figure class="paging__image">
						<?php echo get_the_post_thumbnail( $ys_paging['prev'] ); ?>
					</figure>
				<?php endif; ?>
				<p class="paging__title">
					<?php echo get_the_title( $ys_paging['prev'] ); ?>
				</p>
			</div>
		<?php endif; ?>
		<?php if ( $ys_paging['next'] ) : ?>
			<div class="paging__item is-next">
				<?php if ( has_post_thumbnail( $ys_paging['next'] ) ) : ?>
					<figure class="paging__image">
						<?php echo get_the_post_thumbnail( $ys_paging['next'] ); ?>
					</figure>
				<?php endif; ?>
				<p class="paging__title">
					<?php echo get_the_title( $ys_paging['next'] ); ?>
				</p>
			</div>
		<?php endif; ?>
	</div>
</div>
