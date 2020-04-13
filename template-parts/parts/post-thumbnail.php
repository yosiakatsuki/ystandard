<?php
/**
 * 投稿サムネイル
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */
?>

<figure class="singular-header__thumbnail post-thumbnail">
	<?php
	the_post_thumbnail(
		'post-thumbnail',
		[
			'id'    => 'singular-header__image',
			'class' => 'singular-header__image',
			'alt'   => get_the_title(),
		]
	);
	?>
</figure>
