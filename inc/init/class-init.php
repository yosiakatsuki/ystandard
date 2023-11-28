<?php
/**
 * 初期化関連の処理
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

/**
 * Class Init
 *
 * @package ystandard
 */
class Init {

	/**
	 * Init constructor.
	 */
	public function __construct() {
		/**
		 * head関連の処理はHeadクラスにまとめています。
		 *
		 * @see inc/head/class-head.php
		 */

	}
}

new Init();
