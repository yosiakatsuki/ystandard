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

<header class="entry-header singular__header">
	<?php
	/**
	 * アイキャッチ画像
	 * フル幅サムネイル設定以外 & アイキャッチ画像表示
	 */
	if ( ! ys_is_full_width_thumbnail() && ys_is_active_post_thumbnail() ) :
		?>
		<figure class="post-thumbnail singular__thumbnail">
			<?php
			the_post_thumbnail(
				'post-thumbnail',
				[
					'id'    => 'post-thumbnail-img',
					'class' => 'singular__thumbnail-img ',
					'alt'   => get_the_title(),
				]
			);
			?>
		</figure><!-- .post-thumbnail -->
	<?php
	endif;
	do_action( 'ys_singular_before_title' );
	/**
	 * ページタイトル
	 */
	the_title(
		'<h1 class="entry-title singular__title">',
		'</h1>'
	);
	do_action( 'ys_singular_after_title' );
	/**
	 * 投稿日やシェアボタン等のメタ情報表示
	 * 1. 日付とカテゴリー(10)
	 * 2. シェアボタン(20)
	 * 3. 広告(30)
	 */
	do_action( 'ys_singular_header' );
	?>
</header>
