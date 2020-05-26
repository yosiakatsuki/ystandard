<?php
/**
 * 記事一覧テンプレート(カードタイプ デフォルト)
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( ys_get_archive_item_class() ); ?>>
	<div class="archive__detail">
		<div class="archive__thumbnail is-card">
			<div class="ratio <?php ys_the_archive_image_ratio(); ?>">
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="ratio__item">
						<figure class="ratio__image">
							<?php
							the_post_thumbnail(
								apply_filters( 'ys_archive_thumbnail_size', 'post-thumbnail' ),
								[
									'class' => 'archive__image',
									'alt'   => get_the_title(),
								]
							);
							?>
						</figure>
					</div>
				<?php else : ?>
					<div class="ratio__item">
						<div class="archive__no-img">
							<?php echo ys_get_icon( 'image', 'archive__image' ); ?>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<div class="archive__text">
			<?php
			ys_the_archive_meta();
			the_title(
				'<h2 class="archive__title"><a class="archive__link" href="' . get_the_permalink() . '">',
				'</a></h2>'
			);
			ys_the_archive_description();
			?>
		</div>
	</div>
</article>
