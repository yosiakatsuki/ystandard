<?php
/**
 * 記事一覧ショートコードテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

defined( 'ABSPATH' ) || die();

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
			$term = ys_get_the_term_data( $recent_posts['taxonomy'] );
			?>
			<li class="ys-posts__item">
				<div class="ys-posts__content">
					<div class="ys-posts__text">
						<?php if ( $recent_posts['show_date'] || ( $term && $recent_posts['show_category'] ) ) : ?>
							<div class="ys-posts__meta">
								<?php if ( $recent_posts['show_date'] ) : ?>
									<span class="ys-posts__date">
										<time class="updated" datetime="<?php the_time( 'Y-m-d' ); ?>"><?php the_time( get_option( 'date_format' ) ); ?></time>
									</span>
								<?php endif; ?>
								<?php if ( $term && $recent_posts['show_category'] ) : ?>
									<?php
									$taxonomy_slug  = empty( $term['taxonomy'] ) ? 'category' : $term['taxonomy'];
									$class_taxonomy = $taxonomy_slug . '--' . $term['slug'];
									?>
									<span class="ys-posts__cat <?php echo esc_attr( $class_taxonomy ); ?>">
										<?php echo $term['name']; ?>
									</span>
								<?php endif; ?>
							</div>
						<?php endif; ?>
						<p class="ys-posts__title">
							<a href="<?php the_permalink(); ?>" class="ys-posts__link">
								<?php the_title(); ?>
							</a>
						</p>
					</div>
				</div>
			</li>
		<?php endwhile; ?>
	</ul>
</div>
