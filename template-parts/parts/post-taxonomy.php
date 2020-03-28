<?php
/**
 * タクソノミー表示
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

if ( empty( $ys_post_taxonomy ) ) {
	return;
}
?>
<div class="post-taxonomy">
	<?php if ( $ys_post_taxonomy['title'] ) : ?>
		<p class="post-taxonomy__title"><?php echo $ys_post_taxonomy['title']; ?></p>
	<?php endif; ?>
	<?php
	$categories = get_the_category();
	if ( $categories ) :
		?>
		<div class="post-taxonomy__category">
			<ul class="post-taxonomy__items">
				<?php foreach ( $categories as $category ) : ?>
					<li class="post-taxonomy__item">
						<a href="<?php echo esc_url_raw( get_category_link( $category->term_id ) ); ?>" class="post-taxonomy__link"><?php echo $category->name; ?></a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>
	<?php
	$tags = get_the_tags();
	if ( $tags ) :
		?>
		<div class="post-taxonomy__tag">
			<ul class="post-taxonomy__items">
				<?php foreach ( $tags as $tag ) : ?>
					<li class="post-taxonomy__item">
						<a href="<?php echo esc_url_raw( get_tag_link( $tag->term_id ) ); ?>" class="post-taxonomy__link"><?php echo $tag->name; ?></a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>
</div>
