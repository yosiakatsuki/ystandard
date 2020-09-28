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
if ( empty( $recent_posts ) || empty( $posts_query ) ) {
	return;
}
?>
<div class="ys-posts is-<?php echo $recent_posts['list_type']; ?>">
	<ul class="ys-posts__list <?php echo $recent_posts['class']; ?>">
		<?php while ( $posts_query->have_posts() ) : ?>
			<?php
			$posts_query->the_post();
			$cat = get_the_category();
			?>
			<li class="ys-posts__item">
				<div class="ys-posts__content">
					<?php if ( $recent_posts['show_img'] ) : ?>
						<div class="ys-posts__thumbnail">
							<a href="<?php the_permalink(); ?>" class="ratio is-<?php echo $recent_posts['thumbnail_ratio']; ?>">
								<div class="ratio__item">
									<figure class="ratio__image">
										<?php
										if ( has_post_thumbnail() ) {


											the_post_thumbnail(
												$recent_posts['thumbnail_size'],
												[ 'class' => 'ys-posts__image' ]
											);
										} else {
											ys_the_archive_default_image(
												'ys-post__no-image',
												'ys-posts__image',
												$recent_posts['thumbnail_size']
											);
										}
										?>
									</figure>
								</div>
							</a>
						</div>
					<?php endif; ?>
					<div class="ys-posts__text">
						<?php if ( $recent_posts['show_date'] || ( $cat && $recent_posts['show_category'] ) ) : ?>
							<div class="ys-posts__meta">
								<?php if ( $recent_posts['show_date'] ) : ?>
									<span class="ys-posts__date">
										<?php echo ys_get_icon( 'calendar' ); ?>
										<time class="updated" datetime="<?php the_time( 'Y-m-d' ); ?>"><?php the_time( get_option( 'date_format' ) ); ?></time>
									</span>
								<?php endif; ?>
								<?php if ( $cat && $recent_posts['show_category'] ) : ?>
									<span class="ys-posts__cat category--<?php echo esc_attr( $cat[0]->slug ); ?>">
										<?php echo ys_get_icon( 'folder' ); ?>
										<?php echo $cat[0]->name; ?>
									</span>
								<?php endif; ?>
							</div>
						<?php endif; ?>
						<p class="ys-posts__title">
							<a href="<?php the_permalink(); ?>" class="ys-posts__link">
								<?php the_title(); ?>
							</a>
						</p>
						<?php if ( $recent_posts['show_excerpt'] ) : ?>
							<p class="ys-posts__dscr">
								<?php echo ys_get_custom_excerpt( ' …', 40 ); ?>
							</p>
						<?php endif; ?>
					</div>
				</div>
			</li>
		<?php endwhile; ?>
	</ul>
</div>
