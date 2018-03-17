<?php
/**
 * 関連記事テンプレート
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

if ( ! ys_is_active_related_post() ) {
	return;
}
$categories = ys_get_the_category_id_list();
$args       = array(
	'post__not_in' => array( get_the_ID() ),
	'category__in' => $categories,
);
/**
 * 関連記事データの取得
 */
$related_posts = get_posts( ys_get_posts_args_rand( 5, $args ) );
if ( empty( $related_posts ) ) {
	return;
}
?>
<section class="entry-related entry__footer-section">
	<h2 class="entry__footer-title">関連記事</h2>
	<div class="container">
		<div class="row--slide">
			<?php
			foreach ( $related_posts as $post ) :
				setup_postdata( $post );
				?>
				<article class="entry-related__item col col__slide color_font-main">
					<a class="card image-mask__wrap" href="<?php the_permalink(); ?>">
						<div class="entry-list__thumbnail card__img ratio ratio__16-9">
							<div class="ratio__item">
								<?php if ( has_post_thumbnail() ) : ?>
									<figure class="entry-list__figure ratio__image">
										<?php the_post_thumbnail( 'post-thumbnail', array( 'class' => 'entry-related__image' ) ); ?>
									</figure>
								<?php else : ?>
									<div class="entry-list__no-img flex flex--c-c">
										<i class="fa fa-picture-o" aria-hidden="true"></i>
									</div><!-- .entry-list__no-img -->
								<?php endif; ?>
								<div class="image-mask flex flex--c-c">
									<p class="image-mask__text "><?php ys_the_entry_read_more_text(); ?></p>
								</div><!-- .image-mask -->
							</div><!-- .ratio -->
						</div>
						<div class="card__text">
							<?php the_title( '<h3 class="entry-title card__title clear-headline">', '</h3>' ); ?>
							<p class="entry__meta entry-list__meta entry-list__date color__font-sub">
								<i class="fa fa-calendar" aria-hidden="true"></i><time datetime="<?php the_time( 'Y-m-d' ); ?>"><?php the_time( get_option( 'date_format' ) ); ?></time>
							</p><!-- .entry-list__date -->
						</div>
					</a>
				</article>
			<?php
			endforeach;
			wp_reset_postdata();
			?>
		</div><!-- .row-slide -->
	</div><!-- .container -->
</section><!-- .entry__related -->