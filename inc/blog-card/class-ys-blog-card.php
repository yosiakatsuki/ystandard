<?php
/**
 * ブログカード クラス
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * Class YS_Blog_Card
 */
class YS_Blog_Card {
	/**
	 * YS_Blog_Card constructor.
	 */
	public function __construct() {

		require_once dirname( __FILE__ ) . '/class-ys-shortcode-blog-card.php';

		add_action( 'after_setup_theme', array( $this, 'embed_register_handler' ) );
		add_filter( 'oembed_dataparse', array( $this, 'oembed_dataparse' ), 11, 3 );
	}

	/**
	 * ブロクカードの展開処理登録
	 */
	public function embed_register_handler() {
		wp_embed_register_handler(
			'ys_blog_card',
			$this->get_register_pattern(),
			array( $this, 'blog_card_handler' )
		);
	}

	/**
	 * ブログカード化する条件パターンを取得
	 */
	private function get_register_pattern() {
		/**
		 * Embed 変換されるURLパターンを取得
		 */
		$oembed    = _wp_oembed_get_object();
		$providers = array_keys( $oembed->providers );
		/**
		 * デリミタの削除
		 */
		foreach ( $providers as $key => $value ) {
			$providers[ $key ] = preg_replace( '/^#(.+)#.*$/', '$1', $value );
		}

		return '#^(?!.*(' . implode( '|', $providers ) . '))https?://.*$#i';
	}

	/**
	 * Embedの変換ハンドラ
	 *
	 * @param [type] $matches matches.
	 * @param [type] $attr attr.
	 * @param [type] $url url.
	 * @param [type] $rawattr rawattr.
	 *
	 * @return string ブログカード用ショートコード
	 */
	public function blog_card_handler( $matches, $attr, $url, $rawattr ) {
		$blog_card = '[ys_blog_card url="' . $url . '"]';
		/**
		 * ビジュアルエディタ用処理
		 */
		if ( is_admin() && ys_get_option_by_bool( 'ys_admin_enable_tiny_mce_style', false ) ) {
			/**
			 * ビジュアルエディタの中でショートコードを展開する
			 */
			$blog_card = $this->get_admin_blog_card( $url );
		}

		return $blog_card;
	}

	/**
	 * エディタ内で展開するブログカードHTMLを作成する
	 *
	 * @param string $url URL.
	 *
	 * @return string
	 */
	public function get_admin_blog_card( $url ) {
		/**
		 * ビジュアルエディタの中でショートコードを展開する
		 */
		add_shortcode( 'ys_blog_card', 'ys_shortcode_blog_card' );
		$blog_card = ys_do_shortcode(
			'ys_blog_card',
			array(
				'url'   => $url,
				'cache' => 'disable',
			),
			null,
			false
		);
		$blog_card = str_replace( '<a ', '<span ', $blog_card );
		$blog_card = str_replace( '</a>', '</span>', $blog_card );

		return $blog_card;
	}

	/**
	 * Embedでのブログカードの展開
	 *
	 * @param string $return HTML.
	 * @param object $data   Data.
	 * @param string $url    URL.
	 *
	 * @return null|string|string[]
	 */
	public function oembed_dataparse( $return, $data, $url ) {

		if ( 'rich' === $data->type ) {
			if ( 1 === preg_match( $this->get_register_pattern(), $url ) ) {
				/**
				 * ブログカードの展開
				 */
				$return = $this->get_admin_blog_card( $url );
			}
		}

		return $return;
	}
}

new YS_Blog_Card();
