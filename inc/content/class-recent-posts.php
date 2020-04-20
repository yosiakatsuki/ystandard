<?php
/**
 * 投稿一覧系処理
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

/**
 * Class Posts
 *
 * @package ystandard
 */
class Recent_Posts {

	/**
	 * ショートコード名
	 */
	const SHORTCODE = 'ys_recent_posts';

	/**
	 * キャッシュのキー
	 */
	const CACHE_KEY = 'recent_posts';

	/**
	 * ショートコード特有のパラメーター
	 */
	const SHORTCODE_ATTR = [
		'list_type'       => 'list', // list , card.
		'col'             => 1,
		'col_sp'          => 1,
		'col_tablet'      => 3,
		'col_pc'          => 3,
		'taxonomy'        => '',
		'term_slug'       => '',
		'post_type'       => 'post',
		'count'           => 3,
		'order'           => 'DESC',
		'orderby'         => 'date',
		'show_img'        => true,
		'show_date'       => true,
		'show_category'   => true,
		'show_excerpt'    => false,
		'thumbnail_size'  => '', // thumbnail, medium, large, full, etc...
		'thumbnail_ratio' => '', // 4-3, 16-9, 3-1, 2-1, 1-1.
		'filter'          => '', // category,same-post,sga.
		'cache'           => 'recent_posts',
	];

	/**
	 * 表示タイプ
	 */
	const LIST_TYPE = [
		'list' => 'リスト',
		'card' => 'カード',
	];

	/**
	 * 画像縦横比
	 */
	const RATIO_TYPE = [
		'16-9' => '16-9',
		'4-3'  => '4-3',
		'1-1'  => '1-1',
	];

	/**
	 * クエリパラメーター
	 *
	 * @var array
	 */
	private $query_args = [];

	/**
	 * ショートコードパラメーター
	 *
	 * @var array
	 */
	private $shortcode_atts = [];

	/**
	 * SGAのデータ取得数
	 *
	 * @var int
	 */
	private $sga_limit = 0;

	/**
	 * フックやショートコードの登録
	 */
	public function register() {
		/**
		 * ショートコード
		 */
		if ( ! shortcode_exists( self::SHORTCODE ) ) {
			add_shortcode( self::SHORTCODE, [ $this, 'do_shortcode' ] );
		}
		/**
		 * ウィジェット
		 */
		add_action( 'widgets_init', [ $this, 'register_widget' ] );
	}

	/**
	 * ウィジェット登録
	 */
	public function register_widget() {
		\YS_Loader::require_file( __DIR__ . '/class-ys-widget-recent-posts.php' );
		register_widget( 'YS_Widget_Recent_Posts' );
	}

	/**
	 * ショートコード実行
	 *
	 * @param array $atts    Attributes.
	 * @param null  $content Content.
	 *
	 * @return string
	 */
	public function do_shortcode( $atts, $content = null ) {

		$this->shortcode_atts = shortcode_atts( self::SHORTCODE_ATTR, $atts );
		/**
		 * パラメーターのセット
		 */
		$this->set_base_args();
		$this->set_filter_category();
		$this->set_filter_same_post();
		$this->set_filter_sga();
		$this->set_taxonomy();

		/**
		 * キャッシュ
		 */
		$key   = 'ys_query_cache_' . $this->shortcode_atts['cache'];
		$query = Cache::get_query(
			self::CACHE_KEY,
			$this->query_args,
			Option::get_option( $key, 'none' )
		);

		/**
		 * 投稿一覧作成
		 */
		if ( ! $query->have_posts() ) {
			wp_reset_postdata();

			return '';
		}

		ob_start();
		Template::get_template_part(
			'template-parts/parts/recent-posts',
			$this->shortcode_atts['post_type'],
			[
				'recent_posts' => $this->get_template_param(),
				'posts_query'  => $query,
			]
		);
		$html = ob_get_clean();
		wp_reset_postdata();

		return $html;

	}

	/**
	 * テンプレート用パラメーター
	 *
	 * @return array
	 */
	private function get_template_param() {
		$param = [];
		$atts  = $this->shortcode_atts;
		/**
		 * クラス
		 */
		$col_sp         = empty( $atts['col_sp'] ) ? $atts['col'] : $atts['col_sp'];
		$col_tablet     = empty( $atts['col_tablet'] ) ? $atts['col'] : $atts['col_tablet'];
		$col_pc         = empty( $atts['col_pc'] ) ? $atts['col'] : $atts['col_pc'];
		$param['class'] = "col-sp--${col_sp} col-tablet--${col_tablet} col-pc--${col_pc}";
		/**
		 * 表示タイプ
		 */
		$param['list_type'] = 'list' === $atts['list_type'] ? 'list' : 'card';
		/**
		 * 表示・非表示系
		 */
		$param['show_img']      = Utility::to_bool( $atts['show_img'] );
		$param['show_date']     = Utility::to_bool( $atts['show_date'] );
		$param['show_category'] = Utility::to_bool( $atts['show_category'] );
		$param['show_excerpt']  = Utility::to_bool( $atts['show_excerpt'] );
		/**
		 * 画像設定
		 */
		if ( empty( $atts['thumbnail_size'] ) ) {
			$atts['thumbnail_size'] = 'list' === $atts['list_type'] ? 'thumbnail' : 'full';
		}
		if ( empty( $atts['thumbnail_ratio'] ) ) {
			$atts['thumbnail_ratio'] = 'list' === $atts['list_type'] ? '1-1' : '16-9';
		}
		$param['thumbnail_size']  = $atts['thumbnail_size'];
		$param['thumbnail_ratio'] = $atts['thumbnail_ratio'];

		return $param;
	}

	/**
	 * タクソノミー指定
	 */
	private function set_taxonomy() {
		if ( '' !== $this->shortcode_atts['filter'] ) {
			// フィルターとの併用不可.
			return;
		}
		$taxonomy  = $this->shortcode_atts['taxonomy'];
		$term_slug = $this->shortcode_atts['term_slug'];
		if ( empty( $taxonomy ) || empty( $term_slug ) ) {
			return;
		}
		$this->query_args['tax_query'] = [
			[
				'taxonomy' => $taxonomy,
				'field'    => 'slug',
				'terms'    => $term_slug,
			],
		];
	}

	/**
	 * フィルター:Simple GA Ranking
	 */
	private function set_filter_sga() {
		if ( false === strpos( $this->shortcode_atts['filter'], 'sga' ) ) {
			return;
		}
		if ( ! self::is_active_sga_ranking() ) {
			return;
		}

		/**
		 * SGAパラメーター
		 */
		$this->sga_limit = 0;
		$sga_arg         = [
			'display_count' => $this->shortcode_atts['count'],
			'period'        => 30,
			'post_type'     => $this->shortcode_atts['post_type'],
		];
		// タクソノミー指定.
		if ( '' !== $this->shortcode_atts['taxonomy'] && '' !== $this->shortcode_atts['term_slug'] ) {
			$this->sga_limit = 500;
			$key             = $this->shortcode_atts['taxonomy'] . '__in';
			$sga_arg[ $key ] = $this->shortcode_atts['term_slug'];
		} else {
			// 同一カテゴリーのみ.
			if ( false !== strpos( $this->shortcode_atts['filter'], 'category' ) ) {
				$cat_ids = $this->get_the_category_ids();
				if ( ! empty( $cat_ids ) ) {
					$this->sga_limit = 500;
					$cat_slug        = [];
					foreach ( $cat_ids as $cat_id ) {
						$cat = get_category( $cat_id );
						if ( $cat ) {
							$cat_slug[] = $cat->slug;
						}
					}
					$sga_arg['category__in'] = implode( ',', $cat_slug );
				}
			}
		}
		if ( 0 < $this->sga_limit ) {
			add_filter( 'sga_ranking_limit_filter', [ $this, 'sga_limit_filter' ] );
		}
		$sga_arg      = apply_filters( 'ys_recent_post_sga_arg', $sga_arg );
		$ranking_data = sga_ranking_get_date( $sga_arg );
		if ( $ranking_data ) {
			$this->query_args['orderby']  = 'post__in';
			$this->query_args['post__in'] = $ranking_data;
		}
	}

	/**
	 * フィルター:投稿除外
	 */
	private function set_filter_same_post() {
		if ( false === strpos( $this->shortcode_atts['filter'], 'same-post' ) ) {
			return;
		}
		if ( ! is_single() ) {
			return;
		}
		global $post;
		$this->query_args['post__not_in'] = [ $post->ID ];
	}

	/**
	 * フィルター:カテゴリー
	 */
	private function set_filter_category() {
		if ( false === strpos( $this->shortcode_atts['filter'], 'category' ) ) {
			return;
		}
		// SGAフィルターがある場合、SGAを優先.
		if ( false !== strpos( $this->shortcode_atts['filter'], 'sga' ) ) {
			return;
		}
		$cat_ids = $this->get_the_category_ids();
		if ( empty( $cat_ids ) ) {
			return;
		}
		$this->query_args['category__in'] = $cat_ids;
	}

	/**
	 * 基本パラメーター
	 */
	private function set_base_args() {
		$this->query_args = [
			'post_type'           => $this->shortcode_atts['post_type'],
			'posts_per_page'      => $this->shortcode_atts['count'],
			'order'               => $this->shortcode_atts['order'],
			'orderby'             => $this->shortcode_atts['orderby'],
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		];
	}

	/**
	 * 現在表示中ページのカテゴリーを取得
	 *
	 * @return array
	 */
	private function get_the_category_ids() {
		$cat_ids = [];
		$parent  = [];
		if ( is_category() ) {
			$cat       = get_queried_object();
			$parent[]  = $cat->term_id;
			$cat_ids[] = $cat->term_id;
		} elseif ( is_single() ) {
			$cat = get_the_category();
			if ( ! $cat ) {
				return [];
			}
			foreach ( $cat as $cat_obj ) {
				$parent[]  = $cat_obj->cat_ID;
				$cat_ids[] = $cat_obj->cat_ID;
			}
		}
		foreach ( $parent as $id ) {
			$children = get_term_children( (int) $id, 'category' );
			$cat_ids  = array_merge( $cat_ids, $children );
		}

		return $cat_ids;
	}


	/**
	 * Simple GA Ranking を使っているか
	 *
	 * @return bool
	 */
	public static function is_active_sga_ranking() {
		return function_exists( 'sga_ranking_get_date' );
	}

	/**
	 * SGAのデータ取得件数操作
	 *
	 * @param int $count Limit.
	 *
	 * @return int
	 */
	public function sga_limit_filter( $count ) {
		if ( 0 < $this->sga_limit ) {
			return $this->sga_limit;
		}

		return $count;
	}
}

$class_recent_posts = new Recent_Posts();
$class_recent_posts->register();
