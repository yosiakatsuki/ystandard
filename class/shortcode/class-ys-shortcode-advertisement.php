<?php
/**
 * 広告表示ショートコード クラス
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * Class YS_Shortcode_Advertisement
 */
class YS_Shortcode_Advertisement extends YS_Shortcode_Base {

	/**
	 * ショートコードパラメーター
	 */
	const SHORTCODE_PARAM = array(
		'title'       => self::TITLE,
		'title_class' => 'ys-ad-title',
		'wrap_html'   => '<aside%s>%s</aside>',
		'text'        => '',
	);
	/**
	 * タイトル
	 */
	const TITLE = 'スポンサーリンク';

	/**
	 * YS_Shortcode_Advertisement constructor.
	 *
	 * @param array $args ユーザー指定パラメーター.
	 */
	public function __construct( $args = array() ) {
		/**
		 * 初期値セット＆広告ショートコード用パラメーター追加
		 */
		parent::__construct(
			$args,
			array_merge(
				self::SHORTCODE_PARAM,
				array(
					'title' => apply_filters( 'ys_ad_label_text', self::TITLE ),
				)
			)
		);
		parent::add_class( 'ys-ad-block' );
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
		 * 404
		 */
		if ( is_404() ) {
			return '';
		}
		/**
		 * 検索結果なし
		 */
		global $wp_query;
		if ( is_search() && 0 === $wp_query->found_posts ) {
			return '';
		}
		/**
		 * 表示しない設定
		 */
		if ( ! ys_is_active_advertisement() ) {
			return '';
		}
		/**
		 * 広告無しの場合
		 */
		if ( empty( $content ) ) {
			return '';
		}

		/**
		 * HTML作成
		 */
		$ad_content = sprintf(
			'<div class="ys-ad-content">%s</div>',
			$content
		);

		return parent::get_html( $ad_content );
	}
}