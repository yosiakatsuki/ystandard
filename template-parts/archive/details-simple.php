<?php
/**
 * 記事一覧テンプレート(カードタイプ デフォルト)
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

defined( 'ABSPATH' ) || die();
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( ys_get_archive_item_class() ); ?>>
	<?php do_action( 'ys_archive_detail_prepend', 'simple' ); ?>
	<div class="archive__meta">
		<?php ys_the_archive_date( false ); ?>
		<?php ys_the_archive_category( false ); ?>
	</div>
	<?php
	the_title(
		'<h2 class="archive__title"><a class="archive__link" href="' . get_the_permalink() . '">',
		'</a></h2>'
	);
	?>
	<?php do_action( 'ys_archive_detail_append', 'simple' ); ?>
</article>
