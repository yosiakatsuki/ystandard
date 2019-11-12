<?php
/**
 * 投稿者表示 ショートコード クラス
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * Class YS_Shortcode_Post_Paging
 */
class YS_Shortcode_Post_Paging extends YS_Shortcode_Base {

	/**
	 * ショートコードパラメーター
	 */
	const SHORTCODE_PARAM = array(
		'class_base' => 'post-paging',
		'title'      => '',
	);

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
	 * @param string $content コンテンツとなるHTML.
	 *
	 * @return string
	 */
	public function get_html( $content = '' ) {

		$paging = $this->get_paging_data();
		if ( ! $paging ) {
			return '';
		}
		$prev = $this->get_link( $paging['prev'], 'prev' );
		$next = $this->get_link( $paging['next'], 'next' );

		$content = sprintf(
			'<div class="flex flex--row flex--j-between -no-gutter -all">%s</div>',
			$prev . $next
		);

		return parent::get_html( $content );
	}

	/**
	 * リンク取得
	 *
	 * @param array  $data      ページングデータ.
	 * @param string $next_prev next or prev.
	 *
	 * @return string
	 */
	private function get_link( $data, $next_prev ) {
		$link      = home_url( '/' );
		$row_class = '';
		if ( $data ) {
			/**
			 * データありの場合
			 */
			$image = '';
			if ( $data['image'] ) {
				$image = sprintf(
					'<figure class="post-paging__image">%s</figure>',
					$data['image']
				);
			}

			$content = sprintf(
				'<div class="post-paging__detail %s">%s<span class="post-paging__title">%s</span></div>',
				'-' . $next_prev,
				$image,
				$data['title']
			);
			$link    = $data['url'];
		} else {
			$content = '<span class="flex flex--c-c post-paging__home"><i class="fas fa-home fa-2x"></i></span>';
		}

		return sprintf(
			'<a class="post-paging__item flex__col--1 flex__col--md-2" href="%s">%s</a>',
			$link,
			$content
		);
	}

	/**
	 * ページング用データ取得
	 *
	 * @return array|bool
	 */
	public function get_paging_data() {
		$paging = array();
		if ( ! ys_is_active_post_paging() ) {
			return false;
		}
		if ( ys_is_amp() ) {
			return false;
		}
		/**
		 * 前後の記事を取得
		 */
		$in_same_term = apply_filters( 'ys_paging_in_same_term', false );
		$post_prev    = apply_filters( 'ys_paging_get_previous_post', get_previous_post( $in_same_term ) );
		$post_next    = apply_filters( 'ys_paging_get_next_post', get_next_post( $in_same_term ) );
		if ( empty( $post_prev ) && empty( $post_next ) ) {
			return false;
		}
		/**
		 * 前の記事
		 */
		$image_prev = '';
		if ( $post_prev ) {
			if ( has_post_thumbnail( $post_prev->ID ) ) {
				$image_prev = get_the_post_thumbnail(
					$post_prev->ID,
					'thumbnail',
					array( 'class' => 'entry-paging__image' )
				);
			}
			$paging['prev'] = array(
				'url'   => esc_url( get_permalink( $post_prev->ID ) ),
				'title' => get_the_title( $post_prev->ID ),
				'image' => $image_prev,
				'text'  => '«前の記事',
			);
		} else {
			$paging['prev'] = false;
		}
		$image_next = '';
		if ( $post_next ) {
			if ( has_post_thumbnail( $post_next->ID ) ) {
				$image_next = get_the_post_thumbnail(
					$post_next->ID,
					'thumbnail',
					array( 'class' => 'entry-paging__image' )
				);
			}
			$paging['next'] = array(
				'url'   => esc_url( get_permalink( $post_next->ID ) ),
				'title' => get_the_title( $post_next->ID ),
				'image' => $image_next,
				'text'  => '次の記事»',
			);
		} else {
			$paging['next'] = false;
		}

		return $paging;
	}
}