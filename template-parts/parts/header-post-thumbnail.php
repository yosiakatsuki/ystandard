<?php
/**
 * 投稿サムネイル - サイトヘッダー表示
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */
?>

<figure class="site-header-thumbnail">
	<?php
	the_post_thumbnail(
		'post-thumbnail',
		[
			'id'    => 'site-header-thumbnail__image',
			'class' => 'site-header-thumbnail__image',
			'alt'   => get_the_title(),
		]
	);
	?>
</figure>
