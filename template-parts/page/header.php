<?php
/**
 * 固定ページヘッダーテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

?>

<header class="entry-header singular__header page__header">
	<?php do_action( 'ys_singular_header_prepend' ); ?>
	<?php if ( ys_is_full_width_thumbnail() ) : ?>
		<?php
		/**
		 * フル幅サムネイルテンプレート表示
		 */
		get_template_part( 'template-parts/singular/full-thumbnail' );
		/**
		 * ページタイトル
		 */
		the_title( '<h1 class="entry-title singular__title page__title">', '</h1>' );
		?>
	<?php else : ?>
		<?php
		/**
		 * ページタイトル
		 */
		the_title( '<h1 class="entry-title singular__title page__title">', '</h1>' );
		/**
		 * アイキャッチ画像
		 */
		if ( ys_is_active_post_thumbnail() ) :
			?>
			<figure class="post-thumbnail singular__thumbnail page__thumbnail text--center">
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
		<?php endif; ?>
	<?php endif; ?>
	<?php
	/**
	 * 投稿日やシェアボタン等のメタ情報表示
	 */
	ys_get_singular_header_parts();
	?>
	<?php do_action( 'ys_singular_header_append' ); ?>
</header>