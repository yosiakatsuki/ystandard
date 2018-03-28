<?php
/**
 * 変数
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * CSS関連のクラス
 */
global $ys_enqueue;
$ys_enqueue = new YS_Enqueue();

/**
 * 設定オブジェクト
 */
global $ys_options;
$ys_options = null;

/**
 * AMP判断用変数
 */
global $ys_amp;
$ys_amp = null;

/**
 * 投稿者id上書き用
 */
global $ys_author;
$ys_author = false;