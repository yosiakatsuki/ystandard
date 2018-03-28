<?php
/**
 * 記事下シェアボタン
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

if ( ys_is_active_entry_footer_share() ) {
	get_template_part( 'template-parts/sns/share-button' );
}