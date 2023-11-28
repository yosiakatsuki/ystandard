<?php
/**
 * 各種ファイルの読み込み
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */
defined( 'ABSPATH' ) || die();

// ユーティリティー.
require_once __DIR__ . '/utils/index.php';
// テンプレート関連.
require_once __DIR__ . '/template-function/index.php';

// 初期化関連.
require_once __DIR__ . '/init/class-init.php';
// ヘッダー関連.
require_once __DIR__ . '/head/class-head.php';
// スクリーンリーダー関連.
require_once __DIR__ . '/screen-reader/class-screen-reader.php';
// メニュー関連.
require_once __DIR__ . '/nav-menu/class-nav-menu.php';
// ロゴ.
require_once __DIR__ . '/logo/class-logo.php';

// 投稿タイプ.
require_once __DIR__ . '/post-type/index.php';

// タクソノミー関連.
require_once __DIR__ . '/taxonomy/class-taxonomy.php';
