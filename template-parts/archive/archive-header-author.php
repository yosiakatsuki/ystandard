<?php
/**
 * ヒーローエリア : 投稿者
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

?>
<header class="page-header archive__header">
	<div class="archive__header-author author--2col clearfix">
		<?php get_template_part( 'template-parts/author/profile-box' ); ?>
	</div>
	<?php
	the_archive_title( '<h2 class="page-title archive__title clear-headline">', '</h2>' );
	?>
</header><!-- .page-header -->