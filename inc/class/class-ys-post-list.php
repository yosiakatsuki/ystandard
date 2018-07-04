<?php
/**
 * 記事一覧作成クラス
 *
 * @package ystandard
 * @author  yosiakatsuki
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
	 * Class ul
	 *
	 * @var string
	 */
	private $class_list = '';
	/**
	 * Class li
	 *
	 * @var string
	 */
	private $class_item = '';
	/**
	 * Class a
	 *
	 * @var string
	 */
	private $class_link = '';
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
	 * カスタムテンプレートのパス(get_template_partで指定するパス)
	 *
	 * @var string
	 */
	private $template_li = '';
	/**
	 * 表示モード
	 *
	 * @var string
	 */
	private $mode = 'vertical';
	/**
	 * 横並びモードの列数
	 *
	 * @var string
	 */
	private $cols = '1';

	/**
	 * コンストラクタ
	 *
	 * @param array $args パラメータ.
	 */
	public function __construct( $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'id'             => $this->id,
				'class'          => $this->class,
				'thumbnail_size' => $this->thumbnail_size,
				'query'          => $this->query,
				'args_default'   => array(
					'posts_per_page' => 5,
					'offset'         => 0,
					'orderby'        => 'date',
					'order'          => 'DESC',
					'post_type'      => 'post',
					'post_status'    => 'publish',
				),
				'class_list'     => $this->class_list,
				'class_item'     => $this->class_item,
				'class_link'     => $this->class_link,
				'template'       => $this->template_li,
				'no_result_info' => $this->no_result_info,
				'mode'           => $this->mode,
				'cols'           => $this->cols,
			)
		);
		/**
		 * パラメータの展開
		 */
		$this->id             = $args['id'];
		$this->class          = $args['class'];
		$this->thumbnail_size = $args['thumbnail_size'];
		$this->query          = $args['query'];
		$this->args_default   = $args['args_default'];
		$this->class_list     = $args['class_list'];
		$this->class_item     = $args['class_item'];
		$this->class_link     = $args['class_link'];
		$this->template_li    = $args['template'];
		$this->no_result_info = $args['no_result_info'];
		$this->mode           = $args['mode'];
		$this->cols           = $args['cols'];
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
	 * Classのセット
	 *
	 * @param string $class クラス.
	 */
	public function set_class( $class ) {
		$this->class = $class;
	}

	/**
	 * Classのセット : ul
	 *
	 * @param string $class クラス.
	 */
	public function set_class_list( $class ) {
		$this->class_list = $class;
	}

	/**
	 * Classのセット : li
	 *
	 * @param string $class クラス.
	 */
	public function set_class_item( $class ) {
		$this->class_item = $class;
	}

	/**
	 * Classのセット : a
	 *
	 * @param string $class クラス.
	 */
	public function set_class_link( $class ) {
		$this->class_link = $class;
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
	 *
	 * @return void
	 */
	public function set_thumbnail_size( $thumbnail_size ) {
		$this->thumbnail_size = $thumbnail_size;
	}

	/**
	 * 結果なしの時の文言をセット
	 *
	 * @param string $no_result_info 結果なしの時の文言.
	 *
	 * @return void
	 */
	public function set_no_result_info( $no_result_info ) {
		$this->no_result_info = $no_result_info;
	}

	/**
	 * テンプレートパスのセット
	 *
	 * @param string $template カスタムテンプレートのパス.
	 *
	 * @return void
	 */
	public function set_template( $template ) {
		$this->template_li = $template;
	}

	/**
	 * モードのセット
	 *
	 * @param string $mode モード.
	 */
	public function set_mode( $mode ) {
		$this->mode = $mode;
	}

	/**
	 * 列数のセット
	 *
	 * @param string $cols 列数.
	 */
	public function set_cols( $cols ) {
		$this->cols = $cols;
	}

	/**
	 * 投稿一覧作成
	 *
	 * @param array $args パラメーター.
	 *
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
		 * クラス展開
		 */
		$this->set_classes();
		$class_list = '';
		$class_item = $this->combine_class( '', $this->class_item );
		$class_link = $this->combine_class( '', $this->class_link );
		/**
		 * 一覧の作成
		 */
		$html       = '';
		$image_type = 'ys-post-list--no-img';
		while ( $query->have_posts() ) {
			$query->the_post();
			/**
			 * テンプレート取得
			 */
			if ( '' === $this->template_li ) {
				/**
				 * 画像取得
				 */
				$image       = '';
				$has_image   = '';
				$title_class = '';
				if ( '' !== $this->thumbnail_size ) {
					$image      = $this->get_thumbnail( $this->thumbnail_size );
					$image_type = 'ys-post-list--' . $this->thumbnail_size;
				}
				if ( 'vertical' !== $this->mode ) {
					$title_class = ' card__text';
				} else {
					$has_image = ' v-img';
				}
				$li_class = $class_item . $has_image;

				/**
				 * 投稿部分のHTML作成
				 */
				$html_post = sprintf(
					'<li class="ys-post-list__item%s"><a class="image-mask__wrap clearfix%s" href="%s">%s<span class="ys-post-list__title%s">%s</span></a></li>',
					$li_class,
					$class_link,
					get_the_permalink(),
					$image,
					$title_class,
					get_the_title()
				);
			} else {
				ob_start();
				get_template_part( $this->template_li );
				$html_post = ob_get_clean();
			}
			/**
			 * 結合
			 */
			$html .= apply_filters( 'ys_post_list_item', $html_post, $this->id, get_the_ID() );
		}
		wp_reset_postdata();

		/**
		 * クラス
		 */
		$class_list = $this->combine_class( $class_list, $this->class_list );
		if ( '' !== $this->thumbnail_size ) {
			$class_list = $this->combine_class( $class_list, 'list-style--none' );
		}

		return $this->get_wrap(
			sprintf(
				'<ul class="ys-post-list__items%s %s">%s</ul>',
				$class_list,
				$image_type,
				$html
			)
		);
	}

	/**
	 * モードからクラスをセットする
	 */
	private function set_classes() {
		$this->class_list = $this->combine_class( $this->class_list, $this->mode );
		/**
		 * 横並び
		 */
		if ( 'horizon' === $this->mode ) {
			if ( ! is_numeric( $this->cols ) ) {
				$args['cols'] = '1';
			} elseif ( 1 > (int) $this->cols || 4 < (int) $this->cols ) {
				$this->cols = '4';
			}
			$this->class_list = $this->combine_class(
				$this->class_list,
				'row'
			);
			$this->class_item = $this->combine_class(
				$this->class_item,
				'col__' . $this->cols . '--tb'
			);
		}
		/**
		 * スライド
		 */
		if ( 'slide' === $this->mode ) {
			$this->class_list = $this->combine_class(
				$this->class_list,
				'row--slide'
			);
			$this->class_item = $this->combine_class(
				$this->class_item,
				'col__slide'
			);
		}
		/**
		 * 縦以外
		 */
		if ( 'vertical' !== $this->mode ) {
			$this->class_item = $this->combine_class(
				$this->class_item,
				'ys-post-list--col'
			);
			$this->class_link = $this->combine_class(
				$this->class_link,
				'card'
			);
		}
	}

	/**
	 * ラッパーで囲む
	 *
	 * @param string $html 内包するhtml.
	 *
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
	 * @param string $thumbnail_size サムネイルタイプ.
	 *
	 * @return string
	 */
	private function get_thumbnail( $thumbnail_size ) {
		if ( has_post_thumbnail() ) {
			$thumbnail_size = apply_filters( 'ys_post_list_thumbnail_size', $thumbnail_size, $this->id );
			/**
			 * 画像取得
			 */
			$image = get_the_post_thumbnail( get_the_ID(), $thumbnail_size );
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
		$type = 'vertical';
		if ( 'thumbnail' === $thumbnail_size ) {
			$size = '1-1';
			$type = 'horizon';
		}
		$read_more = sprintf(
			'<div class="image-mask flex flex--c-c"><p class="image-mask__text">%s</p></div>',
			ys_get_entry_read_more_text()
		);
		$image     = sprintf(
			'<div class="ys-post-list__img %s"><div class="ratio ratio__%s"><div class="ratio__item">%s</div></div>%s</div>',
			$type,
			$size,
			$image,
			$read_more
		);

		return $image;
	}

	/**
	 * クラス文字列の結合 空白を付けて結合する
	 *
	 * @param string $class     クラス文字列.
	 * @param string $add_class 結合するクラス.
	 *
	 * @return string
	 */
	public function combine_class( $class, $add_class ) {
		$add_class = ltrim( $add_class );
		if ( '' !== $add_class ) {
			$class .= ' ' . $add_class;
		}

		return $class;
	}
}
