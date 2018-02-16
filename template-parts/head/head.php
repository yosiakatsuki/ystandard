<html <?php language_attributes(); ?>>
<?php ys_the_head_tag(); ?>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="referrer" content="origin-when-crossorigin">
<meta name="format-detection" content="telephone=no" />
<?php
	/**
	 * インラインCSSのセット
	 */
	ys_set_inline_style( get_template_directory() . '/css/ys-firstview.min.css', true );
	ys_set_inline_style( ys_customizer_inline_css() );
	ys_set_inline_style( locate_template('style-firstview.css') );
	/**
	 * インラインCSSの出力
	 */
	ys_the_inline_style();
	/**
	 * wp_head
	 */
	wp_head();
?>
</head>