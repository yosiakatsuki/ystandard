<?php
/**
 * グローバルナビゲーション-検索フォーム
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

defined( 'ABSPATH' ) || die();

if ( ys_is_active_header_search_form() ) : ?>
	<div class="drawer-nav__search">
		<?php get_search_form(); ?>
	</div>
	<?php
endif;
