<?php
/**
 * 固定ページ・投稿詳細ヘッダーテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

if ( ! ys_is_active_post_header() ) {
	return;
}
?>

<header class="singular-header entry-header">
	<?php
	/**
	 * 記事ヘッダー
	 *
	 * 1. アイキャッチ画像(10) parts/post-thumbnail
	 * 2. タイトル (20)
	 * 3. 投稿日・更新日・カテゴリー (30)
	 * 4. SNSシェアボタン (40) parts/sns-share-button
	 * 5. 広告 (60)
	 * 6. 記事上ウィジェット (50)
	 */
	do_action( 'ys_singular_header' );
	?>
</header>
