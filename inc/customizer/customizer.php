<?php
/**
 * テーマカスタマイザー
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * テーマカスタマイザー
 */
require_once dirname( __FILE__ ) . '/control/class-ys-customize-image-label-radio-control.php';
require_once dirname( __FILE__ ) . '/class-ys-customizer.php';
require_once dirname( __FILE__ ) . '/class-ys-customize-register.php';

/**
 * カスタマイザー追加
 */
new YS_Customize_Register();