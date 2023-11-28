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

// ヘッダー関連.
require_once __DIR__ . '/head/class-head.php';
// スクリーンリーダー関連.
require_once __DIR__ . '/screen-reader/class-screen-reader.php';

// タクソノミー関連.
require_once __DIR__ . '/taxonomy/class-taxonomy.php';
