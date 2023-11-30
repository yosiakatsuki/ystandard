<?php
/**
 * 各種ファイルの読み込み
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */
defined( 'ABSPATH' ) || die();

// ユーティリティー.
require_once __DIR__ . '/utils/index.php';
// テンプレート.
require_once __DIR__ . '/template-function/index.php';

// 初期化.
require_once __DIR__ . '/init/class-init.php';
// テンプレート.
require_once __DIR__ . '/template/index.php';
// CSS.
require_once __DIR__ . '/styles/index.php';
// JS.
require_once __DIR__ . '/scripts/index.php';


// ヘッダー.
require_once __DIR__ . '/head/class-head.php';
// スクリーンリーダー.
require_once __DIR__ . '/screen-reader/class-screen-reader.php';
// メニュー.
require_once __DIR__ . '/nav-menu/class-nav-menu.php';
// ロゴ.
require_once __DIR__ . '/logo/class-logo.php';
// パーツ.
require_once __DIR__ . '/parts/index.php';

// 投稿タイプ.
require_once __DIR__ . '/post-type/index.php';

// パーツ.
require_once __DIR__ . '/parts/class-parts.php';

// タクソノミー.
require_once __DIR__ . '/taxonomy/class-taxonomy.php';
