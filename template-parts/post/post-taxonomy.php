<?php
/**
 * タクソノミー表示
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

defined( 'ABSPATH' ) || die();

$taxonomies = ys_get_the_taxonomies_data();

if ( ! $taxonomies ) {
	return;
}
?>
<div class="post-taxonomy">
	<?php foreach ( $taxonomies as $name => $data ) : ?>
		<div class="post-taxonomy__container is-<?php echo esc_attr( $name ); ?>">
			<p class="post-taxonomy__title"><?php echo esc_html( $data['label'] ); ?></p>
			<ul class="post-taxonomy__items">
				<?php foreach ( $data['terms'] as $term ) : ?>
					<li class="post-taxonomy__item">
						<a href="<?php echo esc_url_raw( get_term_link( $term->term_id ) ); ?>" class="post-taxonomy__link"><?php echo $term->name; ?></a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endforeach; ?>
</div>
