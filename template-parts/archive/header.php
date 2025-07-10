<?php
/**
 * アーカイブヘッダー
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

defined( 'ABSPATH' ) || die();

/**
 * TOPページでは出さない.
 */
if ( is_front_page() ) {
	return;
}
?>
<header class="archive__header page-header">
	<?php
	the_archive_title(
		'<h1 class="archive__page-title page-title">',
		'</h1>'
	);
	?>
	<?php
	the_archive_description(
		'<div class="archive__description taxonomy-description">',
		'</div>'
	);
	?>
</header>

