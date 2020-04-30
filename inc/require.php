<?php
/**
 * もろもろ読み込み
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 変数
 */
require_once get_template_directory() . '/inc/variables/variables.php';
/**
 * 設定
 */
require_once get_template_directory() . '/inc/option/option.php';
/**
 * Utilities
 */
require_once get_template_directory() . '/inc/utility/utility.php';
/**
 * 色関連の管理
 */
require_once get_template_directory() . '/inc/color/class-ys-color.php';
/**
 * Cache
 */
require_once get_template_directory() . '/inc/cache/cache.php';
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
 * テンプレート
 */
require_once get_template_directory() . '/inc/template/template.php';
/**
 * アーカイブ
 */
require_once get_template_directory() . '/inc/archive/archive.php';
/**
 * ヘッダー
 */
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
require_once get_template_directory() . '/inc/content/content.php';
/**
 * Body ... body_class
 */
require_once get_template_directory() . '/inc/body/body.php';
/**
 * 広告
 */
require_once get_template_directory() . '/inc/advertisement/advertisement.php';
/**
 * SNS
 */
require_once get_template_directory() . '/inc/sns/share-button.php';
require_once get_template_directory() . '/inc/sns/follow-box.php';
/**
 * Author
 */
require_once get_template_directory() . '/inc/author/author.php';
require_once get_template_directory() . '/inc/author/author-option-page.php';
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
require_once get_template_directory() . '/inc/amp/amp-loader.php';
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
 * RSS
 */
require_once get_template_directory() . '/inc/rss/rss.php';
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
require_once get_template_directory() . '/inc/blog-card/class-ys-blog-card.php';
/**
 * 互換関連
 */
require_once get_template_directory() . '/inc/compatibility/compatibility.php';
/**
 * 後々削除
 */
require_once get_template_directory() . '/inc/deprecated/deprecated.php';
if ( is_admin() ) {
	/**
	 * テーマ設定画面
	 */
	require_once get_template_directory() . '/inc/theme-option/theme-option-add.php';
	/**
	 * アップデートチェック
	 */
	require_once get_template_directory() . '/inc/update-checker/update-checker.php';
	/**
	 * 新しい設定への変更
	 */
	require_once get_template_directory() . '/inc/migration/option-migration.php';
	/**
	 * 通知
	 */
	require_once get_template_directory() . '/inc/notice/class-ys-notice.php';
	/**
	 * アップグレード
	 */
	require_once get_template_directory() . '/inc/upgrade/class-ys-upgrade.php';
}
