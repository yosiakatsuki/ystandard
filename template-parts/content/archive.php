<?php
/**
 * 記事一覧テンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( array( 'entry-list', 'clearfix' ) ); ?>>
	<div class="entry-list__thumbnail image-mask__wrap">
		<a href="<?php the_permalink(); ?>" class="entry-list__link ratio ratio__4-3">
			<?php if ( has_post_thumbnail() ) : ?>
				<figure class="entry-list__figure ratio__item ratio__image">
					<?php
					the_post_thumbnail(
						'post-thumbnail',
						array(
							'class' => 'entry-list__image',
						)
					);
					?>
				</figure>
			<?php else : ?>
				<div class="entry-list__no-img ratio__item flex flex--c-c">
					<i class="far fa-image"></i>
				</div><!-- .entry-list__no-img -->
			<?php endif; ?>
			<div class="image-mask flex flex--c-c">
				<p class="image-mask__text "><?php ys_the_entry_read_more_text(); ?></p>
			</div><!-- .image-mask -->
		</a>
	</div>
	<div class="entry-list__detail">
		<div class="entry__meta entry-list__meta color__font-sub flex flex--j-between">
			<?php if ( ! empty( ys_get_the_category( 1, false ) ) ) : ?>
				<p class="entry-list__cat"><i class="far fa-folder"></i><?php ys_the_entry_category( false ); ?></p><!-- .entry-list__cat -->
			<?php endif; ?>
			<?php if ( ys_is_active_publish_date() ) : ?>
				<p class="entry-list__date"><i class="far fa-calendar"></i><time class="updated" datetime="<?php the_time( 'Y-m-d' ); ?>"><?php the_time( get_option( 'date_format' ) ); ?></time>
				</p><!-- .entry-list__date -->
			<?php endif; ?>
		</div>
		<?php
		the_title(
			'<h3 class="entry-title entry-list__title clear-headline"><a class="entry-list__link" href="' . get_the_permalink() . '">',
			'</a></h3>'
		);
		?>
		<div class="entry-excerpt entry-list__excerpt color__font-sub">
			<?php the_excerpt(); ?>
		</div><!-- .entry-list__excerpt -->
		<div class="entry__meta">
			<?php get_template_part( 'template-parts/entry/entry-author' ); ?>
		</div><!-- .entry__meta -->
	</div><!-- .entry-list__detail -->
</article><!-- #post-## -->