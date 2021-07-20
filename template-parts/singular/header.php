<?php
/**
 * 固定ページ・投稿詳細ヘッダーテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

defined( 'ABSPATH' ) || die();

if ( ! ys_is_active_post_header() ) {
	return;
}
?>

<header class="singular-header entry-header">
	<?php
	/**
	 * 記事ヘッダー
	 *
	 * 1. アイキャッチ画像(10)
	 *      template-parts/parts/post-thumbnail
	 *      \ystandard\Content::post_thumbnail_default
	 * 2. タイトル (20)
	 *      template-parts/parts/post-title
	 *      \ystandard\Content::singular_title
	 * 3. 投稿日・更新日・カテゴリー (30)
	 *      \ystandard\Content::singular_meta
	 * 4. SNSシェアボタン (40)
	 *      template-parts/parts/sns-share-button
	 *      \ystandard\Share_Button::header_share_button
	 * 5. 広告 (60)
	 *      \ystandard\Advertisement::header_ad
	 * 6. 記事上ウィジェット (50)
	 *      \ystandard\Widget::singular_header_widget
	 */
	do_action( 'ys_singular_header' );
	?>
</header>
