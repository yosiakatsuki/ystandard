<?php
/**
 * Embed テンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

defined( 'ABSPATH' ) || die();

get_header( 'embed' );

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		ys_embed_content();
	endwhile;
else :
	get_template_part( 'embed', '404' );
endif;

get_footer( 'embed' );
