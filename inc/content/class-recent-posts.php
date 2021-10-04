<?php
/**
 * 投稿一覧系処理
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

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
	 * ショートコード名(ランキング)
	 */
	const SHORTCODE_RANKING = 'ys_post_ranking';

	/**
	 * ショートコード特有のパラメーター
	 */
	const SHORTCODE_ATTR = [
		'list_type'        => 'list', // list , card.
		'list_type_mobile' => '', // list , card.
		'col'              => 0,
		'col_sp'           => 1,
		'col_tablet'       => 3,
		'col_pc'           => 3,
		'post__in'         => '',
		'post_name__in'    => '',
		'post_parent'      => '',
		'taxonomy'         => '',
		'term_slug'        => '',
		'post_type'        => 'post',
		'count'            => 3,
		'count_mobile'     => 0,
		'order'            => 'DESC',
		'orderby'          => 'date',
		'show_img'         => true,
		'show_date'        => true,
		'show_category'    => true,
		'show_excerpt'     => false,
		'thumbnail_size'   => '', // thumbnail, medium, large, full, etc...
		'thumbnail_ratio'  => '', // 4-3, 16-9, 3-1, 2-1, 1-1.
		'filter'           => '', // category,remove-same-post,sga.
		'run_type'         => 'shortcode', // 上級者向け.
		'post_status'      => 'publish', // 隠しパラメーター.
		'cache'            => 'recent_posts', // 隠しパラメーター。変えるとキャッシュ削除できなくなる可能性があるのでお気をつけください.
	];

	/**
	 * デフォルトテンプレート.
	 */
	const DEFAULT_TEMPLATE = 'template-parts/parts/recent-posts';

	/**
	 * 表示タイプ
	 */
	const LIST_TYPE = [
		'list' => [
			'label'    => 'リスト',
			'template' => self::DEFAULT_TEMPLATE,
		],
		'card' => [
			'label'    => 'カード',
			'template' => self::DEFAULT_TEMPLATE,
		],
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
	 * ランキングデータ取得リミット
	 */
	const RANKING_DATA_LIMIT = 1000;

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
	 * エラーに関する文字列
	 *
	 * @var string
	 */
	private $error = '';

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
		if ( ! shortcode_exists( self::SHORTCODE_RANKING ) ) {
			add_shortcode( self::SHORTCODE_RANKING, [ $this, 'do_shortcode' ] );
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
	 * リストタイプの取得
	 *
	 * @return array
	 */
	public static function get_list_type_config() {
		return apply_filters(
			'ys_get_recent_posts_list_type',
			self::LIST_TYPE
		);
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
		$this->error          = '';
		$this->shortcode_atts = apply_filters(
			'ys_recent_posts_shortcode_atts',
			shortcode_atts( self::SHORTCODE_ATTR, $atts )
		);
		/**
		 * パラメーターのセット
		 */
		$this->set_base_args();
		$this->set_filter_taxonomy();
		$this->set_filter_remove_same_post();
		$this->set_filter_sga();
		$this->set_taxonomy();
		$this->set_post_page();
		/**
		 * キャッシュ
		 */
		$key   = 'ys_query_cache_' . $this->shortcode_atts['cache'];
		$query = Cache::get_query(
			$this->shortcode_atts['cache'],
			apply_filters( 'ys_recent_posts_query_args', $this->query_args ),
			Option::get_option( $key, 'none' )
		);

		/**
		 * 投稿一覧作成
		 */
		if ( ! $query->have_posts() ) {
			wp_reset_postdata();

			return '';
		}
		$list_types = self::get_list_type_config();
		$params     = $this->get_template_param();
		$type       = $params['list_type'];

		$template = self::DEFAULT_TEMPLATE;

		if ( isset( $list_types[ $type ] ) && isset( $list_types[ $type ]['template'] ) ) {
			$template = $list_types[ $type ]['template'];
		}

		ob_start();
		Template::get_template_part(
			$template,
			$this->shortcode_atts['post_type'],
			[
				'recent_posts' => $params,
				'posts_query'  => $query,
			]
		);
		$html = ob_get_clean();
		wp_reset_postdata();

		$html = $this->add_error_message( $html );

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
		 * 列数
		 */
		$col        = $this->parse_col();
		$col_sp     = $col['col_sp'];
		$col_tablet = $col['col_tablet'];
		$col_pc     = $col['col_pc'];
		// クラス作成.
		$param['class'] = "col-sp--${col_sp} col-tablet--${col_tablet} col-pc--${col_pc}";
		/**
		 * 表示タイプ
		 */
		$param['list_type'] = $this->get_list_type();
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
			$atts['thumbnail_size'] = 'list' === $param['list_type'] ? 'thumbnail' : 'full';
		}
		if ( empty( $atts['thumbnail_ratio'] ) ) {
			$atts['thumbnail_ratio'] = 'list' === $param['list_type'] ? '1-1' : '16-9';
		}
		$param['thumbnail_size']  = $atts['thumbnail_size'];
		$param['thumbnail_ratio'] = $atts['thumbnail_ratio'];
		$param['taxonomy']        = empty( $atts['taxonomy'] ) ? false : $atts['taxonomy'];

		return $param;
	}

	/**
	 * 列数設定の展開
	 *
	 * @return array
	 */
	private function parse_col() {
		$atts = $this->shortcode_atts;

		$col_sp     = $this->check_range( $atts['col_sp'], 1 );
		$col_tablet = $this->check_range( $atts['col_tablet'], 3 );
		$col_pc     = $this->check_range( $atts['col_pc'], 3 );
		// 一括設定チェック.
		$col = 0;
		if ( is_numeric( $atts['col'] ) && 0 < $atts['col'] ) {
			$col = $this->check_range( $atts['col'], 0 );
		}
		if ( 0 < $col ) {
			$col_sp     = $col;
			$col_tablet = $col;
			$col_pc     = $col;
		}

		return [
			'col_sp'     => $col_sp,
			'col_tablet' => $col_tablet,
			'col_pc'     => $col_pc,
		];
	}

	/**
	 * 範囲チェック
	 *
	 * @param int $value   値.
	 * @param int $default 初期値.
	 * @param int $min     最小値.
	 * @param int $max     最大値.
	 *
	 * @return int
	 */
	private function check_range( $value, $default, $min = 1, $max = 4 ) {

		if ( ! is_numeric( $value ) ) {
			return $default;
		}
		if ( $value < $min ) {
			return $min;
		}
		if ( $value > $max ) {
			return $max;
		}

		return $value;
	}

	/**
	 * 投稿関連の指定
	 */
	private function set_post_page() {
		if ( ! empty( $this->shortcode_atts['post__in'] ) ) {
			$post_in = $this->shortcode_atts['post__in'];
			if ( ! is_array( $post_in ) ) {
				$post_in = explode( ',', $post_in );
			}
			$this->query_args['post__in'] = $post_in;
		}
		if ( ! empty( $this->shortcode_atts['post_name__in'] ) ) {
			$post_name_in = $this->shortcode_atts['post_name__in'];
			if ( ! is_array( $post_name_in ) ) {
				$post_name_in = explode( ',', $post_name_in );
			}
			$this->query_args['post_name__in'] = $post_name_in;
		}
		if ( ! empty( $this->shortcode_atts['post_parent'] ) ) {
			$this->query_args['post_parent'] = $this->shortcode_atts['post_parent'];
			if ( 'post' === $this->query_args['post_type'] ) {
				$this->query_args['post_type'] = 'page';
			}
		}
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
			'display_count' => $this->get_count(),
			'period'        => 30,
			'post_type'     => $this->shortcode_atts['post_type'],
		];
		// タクソノミー指定.
		if ( '' !== $this->shortcode_atts['taxonomy'] && '' !== $this->shortcode_atts['term_slug'] ) {
			$this->sga_limit = self::RANKING_DATA_LIMIT;
			$key             = $this->shortcode_atts['taxonomy'] . '__in';
			$sga_arg[ $key ] = $this->shortcode_atts['term_slug'];
		} else {
			// 同一カテゴリーのみ.
			if ( false !== strpos( $this->shortcode_atts['filter'], 'tax:' ) ) {
				$taxonomy = $this->get_filter_taxonomy( $this->shortcode_atts['filter'] );
				if ( $taxonomy ) {
					$term_ids = $this->get_the_term_ids( $taxonomy );
					if ( ! empty( $term_ids ) ) {
						$this->sga_limit = self::RANKING_DATA_LIMIT;
						$term_slug       = [];
						foreach ( $term_ids as $term_id ) {
							$term = get_term( $term_id, $taxonomy );
							if ( $term ) {
								$term_slug[] = $term->slug;
							}
						}
						$sga_arg[ $taxonomy . '__in' ] = implode( ',', $term_slug );
					}
				}
			}
		}
		if ( 0 < $this->sga_limit ) {
			add_filter( 'sga_ranking_limit_filter', [ $this, 'sga_limit_filter' ] );
		}
		$sga_arg = apply_filters( 'ys_recent_post_sga_arg', $sga_arg );
		ob_start();
		$ranking_data = sga_ranking_get_date( $sga_arg );
		$dump         = ob_get_clean();
		if ( ! empty( $dump ) ) {
			$this->error = $dump;
		}
		if ( $ranking_data ) {
			$this->query_args['orderby']  = 'post__in';
			$this->query_args['post__in'] = $ranking_data;
		}
	}

	/**
	 * フィルター:閲覧中の投稿除外
	 */
	private function set_filter_remove_same_post() {
		// 後方互換.
		$filter = str_replace(
			'same-post',
			'remove-same-post',
			$this->shortcode_atts['filter']
		);
		if ( false === strpos( $filter, 'remove-same-post' ) ) {
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
	private function set_filter_taxonomy() {
		$filter = $this->shortcode_atts['filter'];
		// 後方互換.
		if ( false !== strpos( $filter, 'category' ) ) {
			$filter = str_replace( 'category', 'tax:category', $filter );
		}
		if ( false === strpos( $filter, 'tax:' ) ) {
			return;
		}
		// SGAフィルターがある場合、SGAを優先.
		if ( false !== strpos( $filter, 'sga' ) ) {
			return;
		}
		$taxonomy = $this->get_filter_taxonomy( $filter );
		if ( ! $taxonomy ) {
			return;
		}
		$term_ids = $this->get_the_term_ids( $taxonomy );
		// tax_queryセット.
		$this->query_args['tax_query']    = [
			[
				'taxonomy' => $taxonomy,
				'field'    => 'term_id',
				'terms'    => $term_ids,
			],
		];
		$this->shortcode_atts['taxonomy'] = $taxonomy;
	}

	/**
	 * 基本パラメーター
	 */
	private function set_base_args() {
		$this->query_args = [
			'post_type'           => $this->shortcode_atts['post_type'],
			'posts_per_page'      => $this->get_count(),
			'order'               => $this->shortcode_atts['order'],
			'orderby'             => $this->shortcode_atts['orderby'],
			'post_status'         => $this->shortcode_atts['post_status'],
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		];
	}

	/**
	 * 現在表示中ページのターム一覧を取得
	 *
	 * @param string $taxonomy Taxonomy.
	 *
	 * @return array
	 */
	private function get_the_term_ids( $taxonomy ) {
		$parent = [];

		if ( is_tax( $taxonomy ) ) {
			$term       = get_queried_object();
			$parent[]   = $term->term_id;
			$term_ids[] = $term->term_id;
		} elseif ( is_single() ) {
			$term = get_the_terms( false, $taxonomy );
			if ( is_wp_error( $term ) || ! $term ) {
				return [];
			}
			foreach ( $term as $term_obj ) {

				$parent[]   = $term_obj->term_id;
				$term_ids[] = $term_obj->term_id;
			}
		}
		foreach ( $parent as $id ) {
			$children = get_term_children( (int) $id, $taxonomy );
			$term_ids = array_merge( $term_ids, $children );
		}

		return $term_ids;
	}

	/**
	 * フィルター指定されたタクソノミーを取得
	 *
	 * @param string $filter Filter.
	 *
	 * @return bool|string
	 */
	private function get_filter_taxonomy( $filter ) {
		if ( ! preg_match( '/tax:.+?(,|$)/', $filter, $match ) ) {
			return false;
		}

		return str_replace( 'tax:', '', rtrim( $match[0], ',' ) );
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

	/**
	 * Countパラメーターの取得.
	 *
	 * @return int
	 */
	private function get_count() {
		$count = $this->shortcode_atts['count'];
		if ( 0 < $this->shortcode_atts['count_mobile'] ) {
			$count = $this->is_mobile() ? $this->shortcode_atts['count_mobile'] : $count;
		}

		return $count;
	}

	/**
	 * 表示タイプの取得.
	 *
	 * @return string
	 */
	private function get_list_type() {
		$type        = $this->shortcode_atts['list_type'];
		$mobile_type = $this->shortcode_atts['list_type_mobile'];
		if ( ! empty( $mobile_type ) && $this->is_mobile() ) {
			return $mobile_type;
		}

		return $type;
	}

	/**
	 * 新着記事一覧ショートコード用モバイル判定.
	 *
	 * @return bool
	 */
	private function is_mobile() {
		return apply_filters( 'ys_recent_posts_is_mobile', ys_is_mobile() );
	}

	/**
	 * エラーメッセージの追加
	 *
	 * @param string $html HTML.
	 *
	 * @return string
	 */
	private function add_error_message( $html ) {
		if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG ) {
			return $html;
		}
		if ( ! is_user_logged_in() ) {
			return $html;
		}
		if ( ! current_user_can( 'manage_options' ) ) {
			return $html;
		}
		if ( empty( $this->error ) ) {
			return $html;
		}

		$error_message = sprintf(
			'<div style="padding: 1em;background: #eee;font-size: 0.8em;"><p style="margin: 0 0 0.5em;">--このメッセージは管理者ユーザーにのみ表示されています--<br>Simple GA Ranking連携機能で以下のメッセージが出力されました。<br>Simple GA Rankingの設定に不備がある場合などにメッセージが出力されることがあるため、まずは設定を再確認してください。</p>%s</div>',
			$this->error
		);
		$this->error   = '';

		return $html . $error_message;
	}
}

$class_recent_posts = new Recent_Posts();
$class_recent_posts->register();
