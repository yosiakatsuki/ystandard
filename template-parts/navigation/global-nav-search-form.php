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
	<div id="global-nav__search" class="global-nav__search">
		<?php get_search_form(); ?>
		<button id="global-nav__search-close" class="global-nav__search-close">
			<?php echo ys_get_icon( 'x' ); ?><?php _ex( 'close', 'global nav', 'ystandard' ); ?>
		</button>
	</div>
	<?php
endif;
