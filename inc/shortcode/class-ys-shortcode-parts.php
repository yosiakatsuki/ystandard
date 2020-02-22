<?php
/**
 * [ys]パーツ表示ヨートコード クラス
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * Class YS_Shortcode_Parts
 */
class YS_Shortcode_Parts extends YS_Shortcode_Base {

	/**
	 * ショートコードパラメーター
	 */
	const SHORTCODE_PARAM = array(
		'title_tag'         => '',
		'wrap_html'         => '',
		'parts_id'          => '',
		'use_entry_content' => '',
	);

	/**
	 * YS_Shortcode_Parts constructor.
	 *
	 * @param array $args ユーザー指定パラメーター.
	 */
	public function __construct( $args = array() ) {
		/**
		 * 初期値セット＆広告ショートコード用パラメーター追加
		 */
		parent::__construct(
			$args,
			self::SHORTCODE_PARAM
		);
	}

	/**
	 * HTML取得
	 *
	 * @param string $content コンテンツ.
	 *
	 * @return string
	 */
	public function get_html( $content ) {
		/**
		 * コンテンツと同じ見た目にするためのクラスを使うか...
		 */
		if ( ys_to_bool( $this->get_param( 'use_entry_content' ) ) ) {
			$this->set_param( 'wrap_html', '<div%s>%s</div>' );
			$this->set_param( 'class', 'entry-content entry__content' );
		}
		/**
		 * パーツIDの変換
		 */
		$parts_id = $this->get_param( 'parts_id' );
		if ( is_numeric( $parts_id ) ) {
			$parts_id = (int) $parts_id;
		} else {
			return '';
		}
		/**
		 * コンテンツ作成
		 */
		$post = get_post( $parts_id );
		if ( ! $post ) {
			return '';
		}
		$content = apply_filters( 'the_content', $post->post_content );
		$content = str_replace( ']]>', ']]&gt;', $content );

		return parent::get_html( $content );
	}
}
