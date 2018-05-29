<?php
/**
 * 記事一覧作成クラス
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 記事一覧作成クラス
 */
class YS_Post_List {
	/**
	 * ID
	 *
	 * @var string
	 */
	private $id = '';
	/**
	 * Class
	 *
	 * @var string
	 */
	private $class = '';
	/**
	 * 記事一覧取得用クエリ(get_posts)
	 *
	 * @var WP_Query
	 */
	private $query = null;
	/**
	 * サムネイルサイズ
	 *
	 * @var string
	 */
	private $thumbnail_size = '';
	/**
	 * 結果なしの場合の文言
	 *
	 * @var string
	 */
	private $no_result_info = '記事がみつかりません';
	/**
	 * 投稿取得パラメータデフォルト値
	 *
	 * @var array
	 */
	private $args_default = array();
	/**
	 * コンストラクタ
	 *
	 * @param string   $id ID.
	 * @param string   $class class.
	 * @param string   $thumbnail_size サムネイルサイズ.
	 * @param WP_Query $query クエリ.
	 */
	public function __construct( $id = '', $class = '', $thumbnail_size = '', $query = null ) {
		$this->id             = $id;
		$this->class          = $class;
		$this->thumbnail_size = $thumbnail_size;
		$this->query          = $query;
		$this->args_default   = array(
			'posts_per_page' => 5,
			'offset'         => 0,
			'orderby'        => 'date',
			'order'          => 'DESC',
			'post_type'      => 'post',
			'post_status'    => 'publish',
		);
	}
	/**
	 * IDのセット
	 *
	 * @param string $id 識別子.
	 */
	public function set_id( $id ) {
		$this->id = $id;
	}
	/**
	 * クエリのセット
	 *
	 * @param WP_Query $query クエリオブジェクト.
	 */
	public function set_query( $query ) {
		$this->query = $query;
	}
	/**
	 * サムネイルサイズのセット
	 *
	 * @param string $thumbnail_size サムネイルのサイズ.
	 * @return void
	 */
	public function set_thumbnail_size( $thumbnail_size ) {
		$this->thumbnail_size = $thumbnail_size;
	}
	/**
	 * 結果なしの時の文言をセット
	 *
	 * @param string $no_result_info 結果なしの時の文言.
	 * @return void
	 */
	public function set_no_result_info( $no_result_info ) {
		$this->no_result_info = $no_result_info;
	}
	/**
	 * 投稿一覧作成
	 *
	 * @param array $args パラメーター.
	 * @return string
	 */
	public function get_post_list( $args = array() ) {
		$args  = wp_parse_args( $args, $this->args_default );
		$query = $this->query;
		if ( is_null( $query ) ) {
			$query = new WP_Query( $args );
		}
		/**
		 * 結果無し
		 */
		if ( ! $query->have_posts() ) {
			return $this->get_wrap( sprintf( '<p>%s</p>', $this->no_result_info ) );
		}
		/**
		 * 一覧の作成
		 */
		$html = '';
		while ( $query->have_posts() ) {
			$query->the_post();
			/**
			 * 画像取得
			 */
			$image      = '';
			$image_type = 'ys-post-list--no-img';
			if ( '' !== $this->thumbnail_size ) {
				$image      = $this->get_thumbnail( $this->thumbnail_size );
				$image_type = 'ys-post-list--' . $this->thumbnail_size;
			}
			/**
			 * 投稿部分のHTML作成
			 */
			$html_post = sprintf(
				'<li class="ys-post-list__item"><a class="image-mask__wrap clearfix" href="%s">%s<span class="ys-post-list__title">%s</span></a></li>',
				get_the_permalink(),
				$image,
				get_the_title()
			);
			/**
			 * 結合
			 */
			$html .= apply_filters( 'ys_post_list_item', $html_post, $this->id, get_the_ID() );
		}
		wp_reset_postdata();
		return $this->get_wrap( sprintf( '<ul class="ys-post-list__items %s">%s</ul>', $image_type, $html ) );
	}
	/**
	 * ラッパーで囲む
	 *
	 * @param string $html 内包するhtml.
	 * @return string
	 */
	private function get_wrap( $html ) {
		$id    = '';
		$class = ' class="ys-post-list"';
		if ( '' !== $this->id ) {
			$id = ' id="' . $this->id . '" ';
		}
		if ( '' !== $this->class ) {
			$class = ' class="ys-post-list ' . $this->class . '" ';
		}
		return sprintf(
			apply_filters( 'ys_post_list_warp', '<div%s%s>%s</div>' ),
			$id,
			$class,
			$html
		);
	}
	/**
	 * 画像タグ作成
	 *
	 * @param string $thmbnail_size サムネイルタイプ.
	 * @return string
	 */
	private function get_thumbnail( $thmbnail_size ) {
		if ( has_post_thumbnail() ) {
			$thmbnail_size = apply_filters( 'ys_post_list_thmbnail_size', $thmbnail_size, $this->id );
			/**
			 * 画像取得
			 */
			$image = get_the_post_thumbnail( get_the_ID(), $thmbnail_size );
			$image = apply_filters( 'ys_post_list_image', $image, get_the_ID() );
			$image = sprintf(
				'<figure class="ratio__image">%s</figure>',
				ys_amp_convert_image( $image )
			);
		} else {
			/**
			 * アイキャッチが見つからない場合
			 */
			$image = '<div class="ys-post-list__no-img flex flex--c-c"><i class="fa fa-picture-o" aria-hidden="true"></i></div>';
		}
		/**
		 * 画像表示比率
		 */
		$size = '16-9';
		if ( 'thumbnail' === $thmbnail_size ) {
			$size = '1-1';
		}
		$read_more = sprintf(
			'<div class="image-mask flex flex--c-c"><p class="image-mask__text">%s</p></div>',
			ys_get_entry_read_more_text()
		);
		$image     = sprintf(
			'<div class="ys-post-list__img"><div class="ratio ratio__%s"><div class="ratio__item">%s</div></div>%s</div>',
			$size,
			$image,
			$read_more
		);
		return $image;
	}
}
