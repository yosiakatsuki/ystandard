<?php
/**
 * 記事一覧系基本クラス
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * テーマ内で作成する記事一覧系ウィジェットのベース
 */
class YS_Widget_Post_List extends YS_Widget_Base {
	/**
	 * 表示投稿数
	 *
	 * @var integer
	 */
	protected $default_post_count = 5;
	/**
	 * 画像表示有無
	 *
	 * @var integer
	 */
	protected $default_show_img = 1;
	/**
	 * 画像サイズ
	 *
	 * @var string
	 */
	protected $default_thumbnail_size = 'thumbnail';
	/**
	 * 表示モードデフォルト値
	 *
	 * @var string
	 */
	protected $default_mode = 'vertical';

	/**
	 * 横並び表示デフォルト列数
	 *
	 * @var string
	 */
	protected $default_cols = '2';

	/**
	 * 列数表示のサニタイズ
	 *
	 * @param int $value 列数.
	 *
	 * @return int
	 */
	protected function sanitize_cols( $value ) {
		if ( ! is_numeric( $value ) ) {
			return $this->default_cols;
		}
		$value = (int) $value;
		if ( 1 > $value ) {
			return $this->default_cols;
		}
		if ( 4 < $value ) {
			return 4;
		}

		return $value;
	}
}
