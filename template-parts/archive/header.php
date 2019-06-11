<?php
/**
 * アーカイブヘッダー
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * Homeテンプレートは出さない
 */
if ( is_home() ) {
	return;
}
?>
<header class="page-header archive__header">
	<?php
	the_archive_title( '<h1 class="page-title clear-h archive__title">', '</h1>' );
	the_archive_description( '<div class="taxonomy-description archive__dscr">', '</div>' );
	?>
</header><!-- .page-header -->