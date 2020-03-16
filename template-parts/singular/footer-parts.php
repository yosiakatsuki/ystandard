<?php
/**
 * 記事フッター部分テンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * SNSシェアボタン
 */
if ( ys_is_active_sns_share_on_footer() ) {
	ys_the_sns_share_button();
}

/**
 * カテゴリー・タグ
 */
ys_get_template_part( 'template-parts/parts/post-taxonomy' );

/**
 * フォローボックス
 */
ys_get_subscribe_buttons();


if ( ys_is_active_related_post() ) {
	/**
	 * 関連記事
	 */
	ys_get_template_part( 'template-parts/parts/post-related' );
}

/**
 * コメント
 */
ys_get_template_part( 'template-parts/parts/post-comments' );

/**
 * 前の記事・次の記事
 */
ys_get_template_part( 'template-parts/parts/post-paging' );
