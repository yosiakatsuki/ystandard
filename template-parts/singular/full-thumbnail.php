<?php
/**
 * フル幅アイキャッチ画像テンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

?>

<?php if ( ys_is_active_post_thumbnail() ) : ?>
	<div class="section--full">
		<div class="ratio full-thumbnail">
			<figure class="post-thumbnail singular__thumbnail page__thumbnail">
				<?php
				the_post_thumbnail(
					'post-thumbnail',
					array(
						'id'    => 'post-thumbnail-img',
						'class' => 'singular__thumbnail-img page__thumbnail-img',
					)
				);
				?>
			</figure><!-- .post-thumbnail -->
		</div>
	</div>
<?php endif; ?>