<?php
/**
 * 固定ページ・投稿詳細ヘッダーテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

defined( 'ABSPATH' ) || die();

if ( ! ys_is_active_post_footer() ) {
	return;
}
?>
<footer class="singular__footer entry-footer">
	<?php
	/**
	 * 記事フッター
	 *
	 * 1. 記事下ウィジェット(10)
	 *      \ystandard\Widget::singular_footer_widget
	 * 2. 広告 (20)
	 *      \ystandard\Advertisement::footer_ad
	 * 3. SNSシェアボタン (30)
	 *      template-parts/parts/sns-share-button
	 *      \ystandard\Share_Button::footer_share_button
	 * 4. カテゴリー・タグ (40)
	 *      template-parts/parts/post-taxonomy
	 *      \ystandard\Taxonomy::post_taxonomy
	 * 5. 著者(50)
	 *      template-parts/parts/author
	 *      \ystandard\Author::post_author
	 * 6. 関連記事(60) parts/post-related
	 *      \ystandard\Content::related_posts
	 * 7. コメント(70)
	 *      \ystandard\Comment::post_comment
	 * 8. 前の記事・次の記事(80)
	 *      template-parts/parts/post-paging
	 *      \ystandard\Paging::post_paging
	 */
	do_action( 'ys_singular_footer' );
	?>
</footer><!-- .entry__footer -->
