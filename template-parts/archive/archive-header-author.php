<?php
/**
 * ヒーローエリア : 投稿者
 * TODO:テンプレート削除
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

?>
<header class="page-header archive__header">
	<?php if ( ys_is_display_author_data() ) : ?>
		<div class="archive__header-author author--2col clearfix">
			<?php get_template_part( 'template-parts/parts/author-box' ); ?>
		</div>
	<?php endif; ?>
	<?php the_archive_title( '<h2 class="page-title archive__title clear-headline">', '</h2>' ); ?>
</header><!-- .page-header -->