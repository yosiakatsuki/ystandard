<?php
/**
 * 記事取得 ショートコード クラス
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * Class YS_Shortcode_Get_Posts
 */
class YS_Shortcode_Get_Posts extends YS_Shortcode_Base {

	/**
	 * ショートコード特有のパラメーター
	 */
	const SHORTCODE_PARAM = array(
		'class'            => 'ys-posts',
		'class_ul'         => '',
		'class_li'         => '',
		'class_link'       => '',
		'list_type'        => 'list', // list , card , slide.
		'col'              => 1,
		'col_sp'           => '',
		'col_tablet'       => '',
		'col_pc'           => '',
		'taxonomy'         => '',
		'term_slug'        => '',
		'post_type'        => 'post',
		'count'            => 5,
		'order'            => 'DESC',
		'orderby'          => 'date',
		'show_img'         => true,
		'show_excerpt'     => false,
		'excerpt_length'   => 50,
		'thumbnail_size'   => '', // thumbnail, medium, large, full, etc...
		'thumbnail_ratio'  => '', // 4-3, 16-9, 3-1, 2-1, 1-1.
		'ranking_type'     => '', // ランキング用 all, d, w, m.
		'filter'           => '', // フィルタリング タクソノミー名を渡す.
		'cache_expiration' => 'none', // 隠しパラメーター.
		'cache_key'        => '', // 隠しパラメーター.
		'class_col_design' => '', // 列の見た目を決めるクラス指定。隠しパラメーター.
		'thumbnail_col'    => '', // auto, 1~6 隠しパラメーター.
		'mode'             => '', // 互換性.
		'period'           => '', // 互換性.
		'post_count'       => '', // 互換性.
		'cols'             => '', // 互換性.
		'class_list'       => '', // 互換性.
		'class_item'       => '', // 互換性.
		'template'         => '', // 廃止.
	);

	/**
	 * Constructor.
	 *
	 * @param array $args ユーザー指定パラメーター.
	 */
	public function __construct( $args = array() ) {
		parent::__construct( $args, self::SHORTCODE_PARAM );
		/**
		 * 旧パラメーターを新パラメーターに対応させる
		 */
		$this->compatible_param();
	}


	/**
	 * HTML取得
	 *
	 * @param string $content コンテンツとなるHTML.
	 *
	 * @return string
	 */
	public function get_html( $content = null ) {
		/**
		 * 各パラメーターのチェック
		 */
		$this->check_param();

		/**
		 * クエリ作成
		 */
		$query = $this->get_query();

		/**
		 * HTML作成
		 */
		$html = $this->get_content( $query );

		/**
		 * テキストの指定があればくっつける
		 */
		if ( is_null( $content ) ) {
			$content = $html;
		} else {
			$content = $content . $html;
		}

		return parent::get_html( $content );
	}

	/**
	 * 各パラメーターのチェック
	 */
	private function check_param() {
		/**
		 * サムネイルサイズのチェック
		 */
		$this->check_thumbnail_size();
		/**
		 * カラム数チェック
		 */
		$this->check_col();
		/**
		 * 列の見た目を決めるクラスのチェック
		 */
		$this->check_col_design();
		/**
		 * キャッシュのチェック
		 */
		$this->check_cache();
	}

	/**
	 * Queryオブジェクト作成
	 *
	 * @return WP_Query
	 */
	private function get_query() {
		/**
		 * 基本パラメーター
		 */
		$query_args = $this->get_base_args();
		/**
		 * フィルターオプション
		 */
		$query_args = array_merge( $query_args, $this->get_filter_args() );
		/**
		 * ランキングオプション
		 */
		$query_args = array_merge( $query_args, $this->get_ranking_args() );
		/**
		 * タクソノミーオプション
		 */
		$query_args = array_merge( $query_args, $this->get_taxonomy_args() );

		/**
		 * クエリ作成
		 */
		if ( '' !== $this->get_param( 'cache_key' ) ) {
			/**
			 * クエリの作成（キャッシュ有効）
			 */
			return YS_Cache::get_query(
				$this->get_param( 'cache_key' ),
				$query_args,
				$this->get_param( 'cache_expiration' )
			);
		} else {
			/**
			 * キャッシュなしの場合
			 */
			return new WP_Query( $query_args );
		}
	}

	/**
	 * サムネイルサイズのセット
	 */
	private function check_thumbnail_size() {
		/**
		 * サムネイル表示設定
		 */
		if ( ! $this->get_param( 'show_img', 'bool' ) ) {
			$this->set_param( 'thumbnail_size', '' );

			return;
		}
		if ( '' === $this->get_param( 'thumbnail_size' ) ) {
			if ( 'list' === $this->get_param( 'list_type' ) ) {
				$this->set_param( 'thumbnail_size', 'thumbnail' );
			} else {
				$this->set_param( 'thumbnail_size', 'full' );
			}
		}
		/**
		 * 指定サイズの存在チェック
		 */
		$size   = get_intermediate_image_sizes();
		$size[] = 'full';
		if ( ! ys_in_array( $this->get_param( 'thumbnail_size' ), $size ) ) {
			$this->set_param( 'thumbnail_size', 'full' );
			$this->set_error_message(
				'存在しない画像サイズ(' . $this->get_param( 'thumbnail_size' ) . ')が指定されています。代替としてfullサイズを使用しています。' . implode( ',', $size ) . ' から指定してください。'
			);

			return;
		}
		/**
		 * 画像表示用クラスの決定
		 */
		if ( '' === $this->get_param( 'thumbnail_col' ) ) {
			if ( 'list' === $this->get_param( 'list_type' ) ) {
				$this->set_param( 'thumbnail_col', 'auto' );
			} else {
				$this->set_param( 'thumbnail_col', '1' );
			}
		}
		/**
		 * 縦横比の指定
		 */
		if ( '' === $this->get_param( 'thumbnail_ratio' ) ) {
			if ( 'thumbnail' === $this->get_param( 'thumbnail_size' ) ) {
				$this->set_param( 'thumbnail_ratio', '1-1' );
			} else {
				$this->set_param( 'thumbnail_ratio', '16-9' );
			}
		}

	}

	/**
	 * 表示カラムチェック
	 */
	private function check_col() {
		/**
		 * グローバル
		 */
		$col = $this->get_param( 'col', 'int' );
		if ( 1 > $col ) {
			$col = 1;
		}
		if ( 6 < $col ) {
			$col = 6;
		}
		/**
		 * スマホ
		 */
		$col_sp = $this->get_param( 'col_sp', 'int' );
		if ( 1 > $col_sp || 6 < $col_sp ) {
			$this->set_param( 'col_sp', $col );
		}
		/**
		 * タブレット
		 */
		$col_tablet = $this->get_param( 'col_tablet', 'int' );
		if ( 1 > $col_tablet || 6 < $col_tablet ) {
			$this->set_param( 'col_tablet', $col );
		}
		/**
		 * PC
		 */
		$col_pc = $this->get_param( 'col_pc', 'int' );
		if ( 1 > $col_pc || 6 < $col_pc ) {
			$this->set_param( 'col_pc', $col );
		}
	}

	/**
	 * 列の見た目を決めるパラメーターチェック
	 */
	private function check_col_design() {
		if ( '' === $this->get_param( 'class_col_design' ) ) {
			if ( 'list' === $this->get_param( 'list_type' ) ) {
				$this->set_param( 'class_col_design', 'ys-posts__design--list' );
			} else {
				$this->set_param( 'class_col_design', 'ys-posts__design--card card' );
			}
		}
	}

	/**
	 * キャッシュのチェック
	 */
	private function check_cache() {
		/**
		 * 日別ランキングはキャッシュしない
		 */
		if ( 'd' === $this->get_param( 'ranking_type' ) ) {
			$this->set_param( 'cache_expiration', 'none' );
		}
	}

	/**
	 * 基本パラメーター
	 *
	 * @return array
	 */
	private function get_base_args() {
		return array(
			'post_type'           => $this->get_param( 'post_type' ),
			'posts_per_page'      => $this->get_param( 'count' ),
			'order'               => $this->get_param( 'order' ),
			'orderby'             => $this->get_param( 'orderby' ),
			'ignore_sticky_posts' => true,
		);
	}

	/**
	 * フィルタリング
	 *
	 * @return array
	 */
	private function get_filter_args() {
		$args = array();
		/**
		 * 3.0.0 カテゴリーのみ対応
		 */
		if ( is_single() ) {
			if ( ( is_single() || is_category() ) && 'category' === $this->get_param( 'filter' ) ) {
				/**
				 * カテゴリーで絞り込む
				 */
				$cat_ids = ys_get_the_category_id_list( true );
				/**
				 * オプションパラメータ作成
				 */
				$args = array( 'category__in' => $cat_ids );
			}
			/**
			 * 投稿ならば表示中の投稿をのぞく
			 */
			global $post;
			$args['post__not_in'] = array( $post->ID );
		}

		return $args;
	}

	/**
	 * ランキング作成パラメーター
	 *
	 * @return array
	 */
	private function get_ranking_args() {
		$args = array();
		/**
		 * ランキング指定チェック
		 */
		if ( '' === $this->get_param( 'ranking_type' ) ) {
			return $args;
		}
		/**
		 * 3.0.0 ランキングは投稿のみサポート
		 */
		$args = array(
			'post_type' => 'post',
			'orderby'   => 'meta_value_num',
			'order'     => 'DESC',
		);
		/**
		 * タイプ別にパラメーターを指定する
		 */
		switch ( $this->get_param( 'ranking_type' ) ) {
			case 'all':
				$args['meta_key'] = YS_METAKEY_PV_ALL;
				break;
			case 'd':
				$args['meta_key']   = YS_METAKEY_PV_DAY_VAL;
				$args['meta_query'] = array(
					array(
						'key'     => YS_METAKEY_PV_DAY_KEY,
						'value'   => date_i18n( 'Y/m/d' ),
						'compare' => '=',
					),
				);
				/**
				 * 日別の場合、キャッシュは強制的に1
				 */
				if ( 'none' !== $this->get_param( 'cache_expiration' ) ) {
					$args['cache_expiration'] = 1;
				}
				break;
			case 'w':
				$args['meta_key']   = YS_METAKEY_PV_WEEK_VAL;
				$args['meta_query'] = array(
					array(
						'key'     => YS_METAKEY_PV_WEEK_KEY,
						'value'   => date_i18n( 'Y-W' ),
						'compare' => '=',
					),
				);
				break;
			case 'm':
				$args['meta_key']   = YS_METAKEY_PV_MONTH_VAL;
				$args['meta_query'] = array(
					array(
						'key'     => YS_METAKEY_PV_MONTH_KEY,
						'value'   => date_i18n( 'Y-m' ),
						'compare' => '=',
					),
				);
				break;
		}

		return $args;
	}

	/**
	 * タクソノミー指定
	 *
	 * @return array
	 */
	private function get_taxonomy_args() {
		$args = array();
		/**
		 * フィルタープロパティとの併用不可
		 */
		if ( '' !== $this->get_param( 'filter' ) ) {
			return $args;
		}
		/**
		 * タクソノミー指定チェック
		 */
		if ( '' === $this->get_param( 'taxonomy' ) || '' === $this->get_param( 'term_slug' ) ) {
			return $args;
		}
		/**
		 * パラメーター追加
		 */
		$args['tax_query'] = array(
			array(
				'taxonomy' => $this->get_param( 'taxonomy' ),
				'field'    => 'slug',
				'terms'    => $this->get_param( 'term_slug' ),
			),
		);

		return $args;
	}

	/**
	 * 表示HTML作成
	 *
	 * @param WP_Query $query query.
	 *
	 * @return string
	 */
	protected function get_content( $query ) {
		/**
		 * 結果なし
		 */
		if ( ! $query->have_posts() ) {
			wp_reset_postdata();

			return sprintf(
				'<p>%s</p>',
				apply_filters( 'ys_get_posts_no_results', '記事が見つかりません。' )
			);
		}
		/**
		 * 一覧作成
		 */
		$html       = '';
		$format_col = apply_filters(
			'ys_posts_format_col',
			'<li class="%s"><div class="%s"><a href="%s" class="%s">%s%s</a></div></li>'
		);
		while ( $query->have_posts() ) {
			$query->the_post();

			/**
			 * 画像を取得
			 */
			$html_image = $this->get_thumbnail();
			/**
			 * 投稿タイトル等を取得
			 */
			$html_title = $this->get_title();
			/**
			 * 列HTML作成
			 */

			$html .= sprintf(
				$format_col,
				$this->get_col_class(),
				$this->get_param( 'class_col_design' ),
				get_the_permalink(),
				$this->get_link_class(),
				$html_image,
				$html_title
			);
		}
		wp_reset_postdata();
		/**
		 * 行HTML作成
		 */
		$format_row = apply_filters(
			'ys_posts_format_row',
			'<ul class="%s">%s</ul>'
		);

		return sprintf(
			$format_row,
			$this->get_row_class(),
			$html
		);
	}

	/**
	 * 画像取得
	 *
	 * @return string
	 */
	private function get_thumbnail() {
		/**
		 * 画像サイズの指定がない場合は空
		 */
		if ( ! $this->get_param( 'thumbnail_size' ) ) {
			return '';
		}
		if ( has_post_thumbnail() ) {
			/**
			 * 画像取得
			 */
			$image = get_the_post_thumbnail( get_the_ID(), $this->get_param( 'thumbnail_size' ) );
			$image = apply_filters( 'ys_posts_image', $image, get_the_ID() );
			$html  = sprintf(
				'<figure class="ratio__image">%s</figure>',
				ys_amp_get_amp_image_tag( $image )
			);
		} else {
			/**
			 * アイキャッチが見つからない場合
			 */
			$html  = '<div class="ys-posts--no-img flex flex--c-c"><i class="far fa-image fa-3x"></i></div>';
			$image = '';
		}
		/**
		 * 画像カラム作成
		 */
		$html = sprintf(
			'<div class="ys-posts__img flex__col--%s"><div class="ratio -r-%s"><div class="ratio__item">%s</div></div></div>',
			$this->get_param( 'thumbnail_col' ),
			$this->get_param( 'thumbnail_ratio' ),
			$html
		);

		return apply_filters(
			'ys_posts_image_html',
			$html,
			$image,
			get_the_ID(),
			$this->get_param( 'thumbnail_col' ),
			$this->get_param( 'thumbnail_ratio' )
		);
	}

	/**
	 * タイトル取得
	 *
	 * @return string
	 */
	private function get_title() {
		/**
		 * タイトル
		 */
		$title = sprintf(
			apply_filters( 'ys_posts_title_wrap', '<span class="ys-posts__title">%s</span>' ),
			get_the_title()
		);
		/**
		 * 抜粋
		 */
		$excerpt = '';
		if ( $this->get_param( 'show_excerpt', 'bool' ) ) {
			$excerpt = sprintf(
				apply_filters( 'ys_posts_excerpt_wrap', '<span class="ys-posts__excerpt text-sub has-x-small-font-size">%s</span>' ),
				ys_get_the_custom_excerpt( ' …', $this->get_param( 'excerpt_length', 'int' ) )
			);
		}

		return sprintf(
			'<div class="ys-posts__content flex__col">%s%s</div>',
			$title,
			$excerpt
		);
	}

	/**
	 * 列クラス取得
	 *
	 * @return string
	 */
	private function get_col_class() {
		$class = array();
		$type  = $this->get_param( 'list_type' );
		/**
		 * 基本クラス
		 */
		$class[] = 'ys-posts__item';
		$class[] = $this->get_param( 'class_li' );
		$class[] = '-' . $type;
		/**
		 * タイプ別
		 */
		if ( 'slide' === $type ) {
			$class[] = 'flex__col';
		} else {
			$class[] = sprintf(
				'flex__col--%s flex__col--md-%s flex__col--lg-%s',
				$this->get_param( 'col_sp' ),
				$this->get_param( 'col_tablet' ),
				$this->get_param( 'col_pc' )
			);
		}

		$class = apply_filters( 'ys_posts_col_class', $class );

		return trim( implode( ' ', $class ) );
	}

	/**
	 * 行クラス取得
	 *
	 * @return string
	 */
	private function get_row_class() {
		$class = array();
		$type  = $this->get_param( 'list_type' );
		/**
		 * 基本クラス
		 */
		$class[] = 'ys-posts__list';
		$class[] = 'li-clear';
		$class[] = 'flex';
		$class[] = 'flex--row';
		$class[] = $this->get_param( 'class_ul' );
		$class[] = '-' . $type;
		if ( 'list' === $type ) {
			$class[] = '-no-gutter';
		}
		$class = apply_filters( 'ys_posts_row_class', $class );

		return trim( implode( ' ', $class ) );
	}

	/**
	 * リンククラス作成
	 *
	 * @return string
	 */
	private function get_link_class() {
		$class = array();
		/**
		 * 基本クラス
		 */
		$class[] = 'ys-posts__link';
		$class[] = 'flex';
		$class[] = 'flex';
		$class[] = 'flex--row';
		$class[] = '-no-gutter';
		$class[] = '-all';
		$class[] = $this->get_param( 'class_link' );
		$class   = apply_filters( 'ys_posts_link_class', $class );

		return trim( implode( ' ', $class ) );

	}

	/**
	 * 旧パラメーターを対応させる
	 *
	 * @param array $params 追加パラメーター.
	 */
	protected function compatible_param( $params = array() ) {
		/**
		 * 対応表
		 */
		$compatible = array(
			'mode'       => array(
				'default' => 'list',
				'new'     => 'list_type',
			),
			'class_list' => array(
				'default' => '',
				'new'     => 'class_ul',
			),
			'class_item' => array(
				'default' => '',
				'new'     => 'class_li',
			),
			'cols'       => array(
				'default' => 1,
				'new'     => 'col',
			),
			'post_count' => array(
				'default' => 5,
				'new'     => 'count',
			),
			'period'     => array(
				'default' => 'all',
				'new'     => 'ranking_type',
			),
		);
		$compatible = array_merge( $compatible, $params );
		/**
		 * 各パラメーターの変換
		 */
		foreach ( $compatible as $key => $value ) {
			if ( isset( $this->args[ $key ] ) && '' !== $this->args[ $key ] ) {
				$this->args[ $value['new'] ] = $this->args[ $key ];
				$this->set_error_message(
					'パラメーター「' . $key . '」は「' . $value['new'] . '」に変更になりました。パラメーター指定を変更してください。'
				);
			}
		}
		/**
		 * 値の変換
		 */
		if ( 'cat' === $this->get_param( 'filter' ) ) {
			$this->set_param( 'filter', 'category' );
			$this->set_error_message(
				'パラメーター「filter="cat"」は「filter="category"」に変更になりました。パラメーター指定を変更してください。'
			);
		}
		if ( 'horizon' === $this->get_param( 'list_type' ) ) {
			$this->set_param( 'list_type', 'list' );
			$this->set_error_message(
				'パラメーター「list_type="horizon"」は「list_type="list"」に変更になりました。パラメーター指定を変更してください。'
			);
		}
		if ( 'vertical' === $this->get_param( 'list_type' ) ) {
			$this->set_param( 'list_type', 'card' );
			$this->set_error_message(
				'パラメーター「list_type="vertical"」は「list_type="card"」に変更になりました。パラメーター指定を変更してください。'
			);
		}
	}

}