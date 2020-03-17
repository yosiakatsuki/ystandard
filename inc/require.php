<?php
/**
 * もろもろ読み込み
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * テンプレート
 */
require_once __DIR__ . '/template/template.php';

/**
 * 設定
 */
require_once get_template_directory() . '/inc/option/option.php';
/**
 * Utilities
 */
require_once get_template_directory() . '/inc/utility/utility.php';
/**
 * デザイン関連
 */
require_once __DIR__ . '/design/design.php';
/**
 * Cache
 */
require_once __DIR__ . '/cache/class-cache.php';
/**
 * Menu
 */
require_once get_template_directory() . '/inc/menu/menu.php';
/**
 * 画像関連の処理
 */
require_once get_template_directory() . '/inc/image/image.php';
/**
 * 条件分岐
 */
require_once get_template_directory() . '/inc/conditional-tag/conditional-tag.php';
/**
 * 初期化
 */
require_once get_template_directory() . '/inc/init/init.php';
/**
 * Post
 */
require_once get_template_directory() . '/inc/post/post.php';
/**
 * Enqueue
 */
require_once get_template_directory() . '/inc/enqueue/enqueue.php';
/**
 * テーマカスタマイザー
 */
require_once get_template_directory() . '/inc/customizer/customizer.php';
/**
 * ヘッダー
 */
require_once __DIR__ . '/header/class-head.php';
require_once get_template_directory() . '/inc/header/head.php';
require_once get_template_directory() . '/inc/header/header.php';
require_once get_template_directory() . '/inc/header/custom-header.php';
require_once get_template_directory() . '/inc/header/class-ys-info-bar.php';
/**
 * フッター
 */
require_once get_template_directory() . '/inc/footer/footer.php';
require_once get_template_directory() . '/inc/footer/footer-sns.php';
/**
 * コンテンツ関連
 */
require_once __DIR__ . '/content/class-content.php';
/**
 * Body
 */
require_once __DIR__ . '/body/class-body.php';
/**
 * 広告
 */
require_once __DIR__ . '/advertisement/class-advertisement.php';
/**
 * SNS
 */
require_once __DIR__ . '/sns/class-sns.php';
require_once get_template_directory() . '/inc/sns/share-button.php';
require_once get_template_directory() . '/inc/sns/follow-box.php';
/**
 * Author
 */
require_once __DIR__ . '/author/class-author.php';
require_once __DIR__ . '/author/class-ys-widget-author-box.php';
/**
 * パンくずリスト
 */
require_once get_template_directory() . '/inc/breadcrumbs/class-ys-breadcrumbs.php';
/**
 * Copyright
 */
require_once get_template_directory() . '/inc/copyright/copyright.php';
/**
 * Taxonomy関連
 */
require_once get_template_directory() . '/inc/taxonomy/taxonomy.php';
/**
 * AMP
 */
require_once __DIR__ . '/amp/class-amp.php';
/**
 * ショートコード
 */
require_once get_template_directory() . '/inc/shortcode/shortcode.php';
/**
 * ウィジェット
 */
require_once get_template_directory() . '/inc/widgets/widgets.php';
/**
 * ページネーション
 */
require_once get_template_directory() . '/inc/pagination/pagination.php';
/**
 * コメント欄
 */
require_once get_template_directory() . '/inc/comment/comment.php';
/**
 * Json-LD
 */
require_once get_template_directory() . '/inc/json-ld/json-ld.php';
/**
 * OGP
 */
require_once get_template_directory() . '/inc/ogp/ogp.php';
/**
 * ブログカード
 */
require_once __DIR__ . '/blog-card/class-blog-card.php';
/**
 * 互換関連
 */
require_once get_template_directory() . '/inc/compatibility/compatibility.php';
if ( is_admin() ) {
	require_once __DIR__ . '/admin/admin.php';
	/**
	 * テーマ設定画面
	 */
	require_once get_template_directory() . '/inc/theme-option/theme-option-add.php';
}
