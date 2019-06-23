<?php
/**
 * 固定ページ・投稿詳細ヘッダーテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 投稿ヘッダー情報無しの場合はスキップ
 */
if ( ys_is_hide_post_header() ) {
	return;
}
?>

<header class="entry-header singular__header <?php ys_the_singular_class( 'header' ); ?>">
	<?php
	do_action( 'ys_singular_header_prepend' );
	/**
	 * ページタイトル
	 */
	the_title(
		'<h1 class="entry-title singular__title ' . ys_get_singular_class( 'title' ) . '">',
		'</h1>'
	);
	/**
	 * アイキャッチ画像
	 * フル幅サムネイル設定以外 & アイキャッチ画像表示
	 */
	if ( ! ys_is_full_width_thumbnail() && ys_is_active_post_thumbnail() ) :
		?>
		<figure class="post-thumbnail singular__thumbnail <?php ys_the_singular_class( 'thumbnail' ); ?> text--center">
			<?php
			the_post_thumbnail(
				'post-thumbnail',
				array(
					'id'    => 'post-thumbnail-img',
					'class' => 'singular__thumbnail-img ' . ys_get_singular_class( 'thumbnail-img' ),
					'alt'   => get_the_title(),
				)
			);
			?>
		</figure><!-- .post-thumbnail -->
		<?php
	endif;
	/**
	 * 投稿日やシェアボタン等のメタ情報表示
	 * template-parts/singular/header-parts 参照
	 */
	ys_get_singular_header_parts();

	do_action( 'ys_singular_header_append' );
	?>
</header>