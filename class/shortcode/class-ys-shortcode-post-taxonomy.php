<?php
/**
 * カテゴリー・タグ等の表示 ショートコード クラス
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * Class YS_Shortcode_Post_Taxonomy
 */
class YS_Shortcode_Post_Taxonomy extends YS_Shortcode_Base {

	/**
	 * ショートコードパラメーター
	 */
	const SHORTCODE_PARAM = array(
		'class_base' => 'post-terms',
		'taxonomy'   => 'category|post_tag', // 表示したいタクソノミー名を「|」区切り.
		'icon'       => '<i class="far fa-folder"></i>|<i class="fas fa-hashtag"></i>', // 表示したいアイコンを「|」区切り。タクソノミーと順番合わせる.
	);

	/**
	 * 表示するタクソノミーのリスト
	 *
	 * @var array
	 */
	private $taxonomy_list = array();

	/**
	 * Constructor.
	 *
	 * @param array $args ユーザー指定パラメーター.
	 */
	public function __construct( $args = array() ) {
		parent::__construct( $args, self::SHORTCODE_PARAM );
	}

	/**
	 * HTML取得
	 *
	 * @param string $content コンテンツ.
	 *
	 * @return string
	 */
	public function get_html( $content = null ) {
		/**
		 * チェック
		 */
		if ( ! is_singular() ) {
			return '';
		}

		/**
		 * 準備.
		 */
		$this->set_taxonomy_list();
		/**
		 * HTML作成
		 */
		$content = $this->get_post_taxonomy();

		return parent::get_html( $content );
	}

	/**
	 * タクソノミー名とアイコンをセットする.
	 */
	private function set_taxonomy_list() {
		$taxonomy = explode( '|', $this->get_param( 'taxonomy' ) );
		$icon     = explode( '|', $this->get_param( 'icon' ) );
		for ( $i = 0; $i < count( $taxonomy ); $i ++ ) {
			$icon_temp = '';
			if ( isset( $icon[ $i ] ) ) {
				$icon_temp = $icon[ $i ];
			}
			/**
			 * タクソノミー名とアイコンのセット
			 */
			$this->taxonomy_list[ $taxonomy[ $i ] ] = $icon_temp;
		}
	}

	/**
	 * タクソノミー情報HTMLを作成
	 *
	 * @return string
	 */
	private function get_post_taxonomy() {
		$result = '';
		foreach ( $this->taxonomy_list as $taxonomy => $icon ) {
			/**
			 * ターム取得
			 */
			$terms = get_the_terms( get_the_ID(), $taxonomy );
			if ( ! is_wp_error( $terms ) && false !== $terms ) {
				$items = array();
				foreach ( $terms as $term ) {
					$items[] = sprintf(
						'<li class="%s"><a class="%s" href="%s">%s%s</a></li>' . PHP_EOL,
						'post-term__item ' . $term->slug,
						'post-term__link ' . $term->slug,
						get_term_link( $term->slug, $taxonomy ),
						$icon,
						$term->name
					);
				}
				$result .= sprintf(
					'<ul class="%s li-clear">%s</ul>' . PHP_EOL,
					'post-term__list ' . $taxonomy,
					implode( '', $items )
				);
			}
		}

		return $result;
	}
}