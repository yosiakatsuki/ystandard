<?php
/**
 * 固定ページ・投稿詳細ヘッダーテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

?>
<footer class="singular__footer entry-footer">
	<?php
	/**
	 * 記事フッター
	 *
	 * 1. 記事下ウィジェット(10)
	 * 2. 広告 (20)
	 * 3. SNSシェアボタン (30) parts/sns-share-button
	 * 4. カテゴリー・タグ (40) parts/post-taxonomy
	 * 5. 著者(50) parts/author
	 * 6. 関連記事(60) parts/post-related
	 * 7. コメント(70) parts/post-comment
	 * 8. 前の記事・次の記事(80) parts/post-paging
	 */
	do_action( 'ys_singular_footer' );
	?>
</footer><!-- .entry__footer -->
