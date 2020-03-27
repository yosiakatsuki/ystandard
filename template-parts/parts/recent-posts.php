<?php
/**
 * 記事一覧ショートコードテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 *  記事一覧ショートコード展開
 */
if ( empty( $ys_recent_posts ) || empty( $ys_posts_query ) ) {
	return;
}
?>
<div class="ys-posts is-<?php echo $ys_recent_posts['list_type']; ?>">
	<ul class="ys-posts__list <?php echo $ys_recent_posts['class']; ?>">
		<?php while ( $ys_posts_query->have_posts() ) : ?>
			<?php
			$ys_posts_query->the_post();
			$cat = get_the_category();
			?>
			<li class="ys-posts__item">
				<?php if ( $ys_recent_posts['show_img'] ) : ?>
					<div class="ys-posts__image ratio is-<?php echo $ys_recent_posts['thumbnail_ratio'] ?>">
						<figure class="ratio__item ratio__image">
							<?php if ( has_post_thumbnail() ) : ?>
								<?php the_post_thumbnail( $ys_recent_posts['thumbnail_size'] ); ?>
							<?php else : ?>
								<span class="ys-post__no-image"><i class="far fa-image fa-2x"></span>
							<?php endif; ?>
						</figure>
					</div>
				<?php endif; ?>
				<div class="ys-posts__meta">
					<?php if ( $cat && $ys_recent_posts['show_category'] ) : ?>
						<span class="ys-posts__cat"><?php echo $cat->name; ?></span>
					<?php endif; ?>
					<?php if ( $ys_recent_posts['show_date'] ) : ?>
						<time class="ys-posts__date updated" datetime="<?php the_time( 'Y-m-d' ); ?>"><?php the_time( get_option( 'date_format' ) ); ?></time>
					<?php endif; ?>
				</div>
				<p class="ys-posts__title"><?php the_title(); ?></p>
				<?php if ( $ys_recent_posts['show_excerpt'] ) : ?>
					<p class="ys-posts__dscr">
						<?php the_excerpt(); ?>
					</p>
				<?php endif; ?>
			</li>
		<?php endwhile; ?>
	</ul>
</div>
