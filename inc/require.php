<?php
/**
 * もろもろ読み込み
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * クラス読み込み
 */
$class_dir = get_template_directory() . '/inc/class/';
require_once $class_dir . 'class-ys-post-list.php';
require_once $class_dir . 'class-ys-enqueue.php';
require_once $class_dir . 'class-ys-cache.php';
require_once $class_dir . 'class-ys-walker-global-nav-menu.php';
/**
 * ウィジェットクラス
 */
$class_widget_dir = get_template_directory() . '/inc/class/widgets/';
require_once $class_widget_dir . 'class-ys-widget-utility.php';
require_once $class_widget_dir . 'class-ys-widget-base.php';
require_once $class_widget_dir . 'class-ys-widget-post-list.php';
require_once $class_widget_dir . 'class-ys-ad-text-widget.php';
require_once $class_widget_dir . 'class-ys-ranking-widget.php';
require_once $class_widget_dir . 'class-ys-taxonomy-posts-widget.php';
require_once $class_widget_dir . 'class-ys-widget-custom-html.php';
require_once $class_widget_dir . 'class-ys-widget-text.php';
require_once $class_widget_dir . 'class-ys-widget-profile-box.php';
/**
 * ショートコードクラス
 */
$class_sc_dir = get_template_directory() . '/inc/class/shortcode/';
require_once $class_sc_dir . 'class-ys-shortcode-base.php';
require_once $class_sc_dir . 'class-ys-shortcode-text.php';
require_once $class_sc_dir . 'class-ys-shortcode-share-button.php';


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
 * 画像関連の処理
 */
require_once get_template_directory() . '/inc/image/image.php';
/**
 * Sanitize
 */
require_once get_template_directory() . '/inc/sanitize/sanitize.php';
/**
 * 投稿タイプ
 */
require_once get_template_directory() . '/inc/post-type/post-type.php';
/**
 * 条件分岐
 */
require_once get_template_directory() . '/inc/conditional-tag/conditional-tag.php';
/**
 * 初期化
 */
require_once get_template_directory() . '/inc/init/init.php';
/**
 * Enqueue
 */
require_once get_template_directory() . '/inc/enqueue/enqueue-loader.php';
/**
 * Post-meta
 */
require_once get_template_directory() . '/inc/post-meta/post-meta.php';
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
 * Post Class ... post_class
 */
require_once get_template_directory() . '/inc/post-class/post-class.php';
/**
 * 広告
 */
require_once get_template_directory() . '/inc/advertisement/advertisement.php';
/**
 * SNS
 */
require_once get_template_directory() . '/inc/sns/share-button.php';
require_once get_template_directory() . '/inc/sns/subscribe.php';
/**
 * Author
 */
require_once get_template_directory() . '/inc/author/author.php';
require_once get_template_directory() . '/inc/author/author-option-page.php';
/**
 * パンくずリスト
 */
require_once get_template_directory() . '/inc/breadcrumbs/breadcrumbs.php';
/**
 * Copyright
 */
require_once get_template_directory() . '/inc/copyright/copyright.php';
/**
 * Taxonomy関連
 */
require_once get_template_directory() . '/inc/taxonomy/taxonomy.php';
require_once get_template_directory() . '/inc/taxonomy/category.php';
require_once get_template_directory() . '/inc/taxonomy/tag.php';
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
 * 簡易VPカウント
 */
require_once get_template_directory() . '/inc/post-view/post-view.php';
/**
 * ページネーション
 */
require_once get_template_directory() . '/inc/pagination/pagination.php';
/**
 * コメント欄
 */
require_once get_template_directory() . '/inc/comment/comment.php';
/**
 * ページング
 */
require_once get_template_directory() . '/inc/paging/paging.php';
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
require_once get_template_directory() . '/inc/blog-card/blog-card.php';
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
}