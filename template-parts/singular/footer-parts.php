<?php
/**
 * 記事フッター部分テンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 記事下広告
 */
ys_the_ad_entry_footer();

/**
 * SNSシェアボタン
 */
if ( ys_is_active_sns_share_on_footer() ) {
	ys_the_sns_share_button();
}

/**
 * カテゴリー・タグ
 */
get_template_part( 'template-parts/parts/post-taxonomy' );

/**
 * フォローボックス
 */
ys_get_subscribe_buttons();

/**
 * 投稿者表示
 */
if ( ys_is_display_author_data() ) {
	ys_the_author_box();
}

if ( ys_is_active_related_post() ) {
	/**
	 * 関連記事
	 */
	get_template_part( 'template-parts/parts/post-related' );
}

/**
 * コメント
 */
get_template_part( 'template-parts/parts/post-comments' );

/**
 * 前の記事・次の記事
 */
get_template_part( 'template-parts/parts/post-paging' );
