<?php
/**
 * アーカイブヘッダー
 * 
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */
?>
<header class="page-header archive__header">
	<?php
		the_archive_title( '<h2 class="page-title archive__title clear-headline">', '</h2>' );
		the_archive_description( '<div class="taxonomy-description archive__dscr color__font-sub">', '</div>' );
	?>
</header><!-- .page-header -->
