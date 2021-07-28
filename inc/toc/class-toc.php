<?php
/**
 * 目次
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

/**
 * Class TOC
 *
 * @package ystandard
 */
class TOC {

	/**
	 * ショートコード
	 */
	const SHORTCODE = 'ys_toc';

	/**
	 * ショートコードパラメーター
	 */
	const SHORTCODE_ATTR = [
		'title' => '目次',
	];

	/**
	 * 見出しを作成するレベル
	 *
	 * @var array
	 */
	private $levels = [];

	/**
	 * 必要な見出し数
	 *
	 * @var int
	 */
	private $required_count = 3;

	/**
	 * アンカー作成用
	 *
	 * @var array
	 */
	private $anchor_level = [];

	/**
	 * 目次タイトル
	 *
	 * @var string
	 */
	private $toc_title = '目次';

	/**
	 * 見出し一覧
	 *
	 * @var null
	 */
	private $toc_matches = null;

	/**
	 * 目次HTML.
	 *
	 * @var string
	 */
	private $toc_html = '';

	/**
	 * ウィジェット・ショートコードで実行しているか
	 *
	 * @var bool
	 */
	private $is_widget = false;

	/**
	 * TOC constructor.
	 */
	public function __construct() {
		// 見出しレベル.
		$this->levels = [];
		for ( $i = 1; $i <= 6; $i ++ ) {
			$default = $i <= 3 ? true : false;
			if ( Option::get_option_by_bool( 'ys_toc_level_' . $i, $default ) ) {
				$this->levels[] = $i;
			}
		}
		// 必要個数.
		$this->required_count = Option::get_option_by_int( 'ys_toc_required_count', 3 );
		// タイトル.
		$this->toc_title = Option::get_option( 'ys_toc_title', '目次' );
		$this->is_widget = false;
	}

	/**
	 * ウィジェト動作モード
	 *
	 * @param bool $value ウィジェット動作か.
	 */
	public function set_is_widget( $value = true ) {
		$this->is_widget = $value;
	}

	/**
	 * アクション・フィルターの登録
	 */
	public function register() {
		add_filter( 'the_content', [ $this, 'create_toc' ] );
		add_action( 'customize_register', [ $this, 'customize_register' ] );
		if ( ! shortcode_exists( self::SHORTCODE ) ) {
			add_shortcode( self::SHORTCODE, [ $this, 'do_shortcode' ] );
		}
		/**
		 * ウィジェット
		 */
		add_action( 'widgets_init', [ $this, 'register_widget' ] );
	}

	/**
	 * ウィジェット有効化
	 */
	public function register_widget() {
		\YS_Loader::require_file( __DIR__ . '/class-ys-widget-toc.php' );
		register_widget( 'YS_Widget_TOC' );
	}

	/**
	 * ショートコード.
	 *
	 * @param array  $atts    Param.
	 * @param string $content Content.
	 *
	 * @return string
	 */
	public function do_shortcode( $atts, $content = null ) {
		if ( ! is_singular() ) {
			return '';
		}
		$default = array_merge(
			self::SHORTCODE_ATTR,
			[ 'title' => $this->toc_title ]
		);
		$atts    = shortcode_atts( $default, $atts );
		$this->set_is_widget( true );
		if ( empty( $content ) ) {
			$content = '';
			if ( is_singular() ) {
				$content = Utility::get_post_content();
			}
		}
		$this->toc_title = $atts['title'];

		$this->create_toc( $content );

		return $this->toc_html;
	}

	/**
	 * 目次の追加.
	 *
	 * @param string $content Content.
	 *
	 * @return string
	 */
	public function create_toc( $content ) {

		if ( ! $this->is_create_toc() ) {
			return $content;
		}
		$this->toc_matches = apply_filters( 'ys_toc_matches', $this->toc_matches );
		if ( is_null( $this->toc_matches ) ) {
			if ( ! preg_match_all( '/(<h([1-6]{1})[^>]*>).*<\/h\2>/msuU', $content, $matches, PREG_SET_ORDER ) ) {
				return $content;
			}
			// 必要な部分だけ抜き出し.
			$matches = $this->get_required_levels( $matches );
			// 空要素の削除.
			$matches = $this->remove_empty( $matches );
			// 必要個数のチェック.
			if ( count( $matches ) < $this->required_count ) {
				return $content;
			}
			// ID・変換文字列の作成.
			$matches = $this->get_replace_data( $matches );
		} else {
			$matches = $this->toc_matches;
		}

		// 目次の作成.
		$toc = $this->get_toc( $matches );
		// ウィジェット動作の場合は置換不要.
		if ( $this->is_widget ) {
			$toc = '';
		}
		// 表示タイプ.
		if ( 'content' !== Option::get_option( 'ys_toc_display_type', 'content' ) ) {
			$toc = '';
		}
		if ( doing_filter( 'the_content' ) ) {
			// 置換.
			foreach ( $matches as $value ) {
				$search  = preg_quote( $value['search'], '/' );
				$replace = $value['replace'];
				$content = preg_replace( "/${search}/", $replace, $content, 1 );
			}
			$content = preg_replace( '/(<h([1-6]{1})|<div.*?class="ystdb-heading)/mu', $toc . '${1}', $content, 1 );
		}
		// 目次情報の保存.
		$this->toc_matches = $matches;

		return $content;
	}

	/**
	 * 目次作成判断
	 *
	 * @return bool
	 */
	public function is_create_toc() {

		if ( ! is_singular() ) {
			return false;
		}
		if ( is_singular( Parts::POST_TYPE ) ) {
			return false;
		}
		if ( ! apply_filters( 'ys_create_toc', true ) ) {
			return false;
		}
		/**
		 * Post.
		 *
		 * @global \WP_Post
		 */
		global $post;
		// 投稿タイプ.
		if ( Option::get_option_by_bool( 'ys_disable_toc_post_type_' . $post->post_type, false ) ) {
			return false;
		}
		// 表示タイプ.
		if ( 'none' === Option::get_option( 'ys_toc_display_type', 'content' ) ) {
			return false;
		}
		// Post meta.
		if ( Utility::to_bool( Content::get_post_meta( 'ys_hide_toc' ) ) ) {
			return false;
		}

		return true;
	}

	/**
	 * 目次作成
	 *
	 * @param array $data Data.
	 *
	 * @return string
	 */
	private function get_toc( $data ) {
		// 階層データ作成.
		$hierarchy = $this->build_hierarchy( $data );
		// 目次HTML.
		$toc = '<ul class="ys-toc__list">' . PHP_EOL;
		for ( $i = 0; $i < count( $hierarchy[0] ); $i ++ ) {
			$key   = $hierarchy[0][ $i ];
			$next  = isset( $hierarchy[0][ $i + 1 ] ) ? $hierarchy[0][ $i + 1 ] : false;
			$depth = 0;
			// HTML.
			$toc .= $this->get_toc_item_html( $data, $key );
			if ( $key + 1 !== $next ) {
				if ( isset( $hierarchy[ $depth + 1 ] ) ) {
					$this->get_toc_children(
						$toc,
						[
							'data'      => $data,
							'current'   => $key,
							'depth'     => $depth + 1,
							'hierarchy' => $hierarchy,
							'next'      => $next,
						]
					);
				}
			}
			$toc .= '</li>' . PHP_EOL;
		}
		$toc .= '</ul>';

		// タイトル.
		$title = apply_filters( 'ys_toc_title', $this->toc_title, $this->is_widget );
		$title = apply_filters(
			'ys_toc_title_html',
			'<p class="ys-toc__title">' . $title . '</p>',
			$this->is_widget
		);
		// HTML.
		$toc            = apply_filters( 'ys_toc_list_html', $toc, $this->is_widget );
		$this->toc_html = apply_filters(
			'ys_toc_html',
			"<div class=\"ys-toc\">${title}${toc}</div>",
			$this->is_widget
		);

		return $this->toc_html;
	}

	/**
	 * 目次下層HTML作成
	 *
	 * @param string $toc  HTML.
	 * @param array  $args Args.
	 */
	private function get_toc_children( &$toc, $args ) {
		$data        = $args['data'];
		$current     = $args['current'];
		$depth       = $args['depth'];
		$hierarchy   = $args['hierarchy'];
		$parent_next = $args['next'];
		$items       = $hierarchy[ $depth ];
		// 開始.
		$toc .= '<ul class="ys-toc__children">' . PHP_EOL;
		for ( $i = 0; $i < count( $items ); $i ++ ) {
			$key = $items[ $i ];
			// 次の親レベルより大きければ抜ける.
			if ( false !== $parent_next && $parent_next < $key ) {
				continue;
			}
			// 親キーより小さい場合は次へ(処理済み).
			if ( $current > $key ) {
				continue;
			}
			$next = isset( $items[ $i + 1 ] ) ? $items[ $i + 1 ] : false;
			// HTML.
			$toc .= $this->get_toc_item_html( $data, $key );
			if ( $key + 1 !== $next ) {
				if ( isset( $hierarchy[ $depth + 1 ] ) ) {
					$this->get_toc_children(
						$toc,
						[
							'data'      => $data,
							'current'   => $key,
							'depth'     => $depth + 1,
							'hierarchy' => $hierarchy,
							'next'      => $next,
						]
					);
				}
			}
			$toc .= '</li>' . PHP_EOL;
		}
		$toc .= '</ul>';
	}

	/**
	 * 目次アイテム作成(閉じタグは別で作成する)
	 *
	 * @param array $data data.
	 * @param int   $i    number.
	 *
	 * @return string
	 */
	private function get_toc_item_html( $data, $i ) {
		$item = '';
		// リスト作成.
		$item .= '<li class="ys-toc__item">';
		$item .= '<a class="ys-toc__link" href="#' . $data[ $i ]['anchor'] . '">';
		$item .= $data[ $i ]['title'];
		$item .= '</a>' . PHP_EOL;

		return $item;
	}

	/**
	 * 目次の階層を作成
	 *
	 * @param array $data Data.
	 *
	 * @return array
	 */
	private function build_hierarchy( $data ) {
		$hierarchy     = [];
		$start_level   = (int) $data[0][2];
		$current_level = (int) $data[0][2];
		for ( $i = 0; $i < count( $this->levels ); $i ++ ) {
			$memo = [];
			foreach ( $data as $key => $item ) {
				if ( ( $current_level === (int) $item[2] || $start_level > (int) $item[2] ) ) {
					$memo[] = $key;
				}
			}
			if ( ! empty( $memo ) ) {
				$hierarchy[ $i ] = $memo;
			}
			$current_level ++;
		}

		return $hierarchy;
	}

	/**
	 * 空要素の削除
	 *
	 * @param array $matches Matches.
	 *
	 * @return array
	 */
	private function get_replace_data( $matches ) {
		$new_matches        = [];
		$this->anchor_level = [];
		for ( $i = 0; $i < count( $matches ); $i ++ ) {
			if ( preg_match( '/id="(.+)?"/i', $matches[ $i ][0], $id ) ) {
				$anchor  = $id[1];
				$replace = $matches[ $i ][0];
			} else {
				$anchor  = $this->get_anchor( $i, $matches[ $i ][2] );
				$replace = str_replace(
					$matches[ $i ][1],
					str_replace( '>', " id=\"${anchor}\">", $matches[ $i ][1] ),
					$matches[ $i ][0]
				);
			}
			$new_matches[ $i ]            = $matches[ $i ];
			$new_matches[ $i ]['anchor']  = $anchor;
			$new_matches[ $i ]['search']  = $matches[ $i ][0];
			$new_matches[ $i ]['replace'] = $replace;
			$new_matches[ $i ]['title']   = $this->get_toc_title( $matches[ $i ][0] );
		}

		return $new_matches;
	}

	/**
	 * 目次タイトルの取得
	 *
	 * @param string $tag HTML.
	 *
	 * @return string
	 */
	private function get_toc_title( $tag ) {
		// blocksのサブテキスト削除.
		$tag = preg_replace( '/<(span)[^<]+?class="ystdb-heading__subtext[^<]+?>.+?<\/\1>/', '', $tag );

		return trim( strip_tags( $tag ) );
	}

	/**
	 * アンカー文字列取得
	 *
	 * @param int $i     Number.
	 * @param int $level Level.
	 *
	 * @return string
	 */
	private function get_anchor( $i, $level ) {
		if ( array_key_exists( $level, $this->anchor_level ) ) {
			$this->anchor_level[ $level ] += 1;
			for ( $j = 6; $j > $level; $j -- ) {
				unset( $this->anchor_level[ $j ] );
			}
		} else {
			$this->anchor_level[ $level ] = 1;
		}
		$index = '';
		foreach ( $this->anchor_level as $value ) {
			$index .= '-' . $value;
		}

		return 'index' . $index;
	}

	/**
	 * 空要素の削除
	 *
	 * @param array $matches Matches.
	 *
	 * @return array
	 */
	private function remove_empty( $matches ) {
		$new_matches = [];
		for ( $i = 0; $i < count( $matches ); $i ++ ) {
			if ( ! empty( trim( strip_tags( $matches[ $i ][0] ) ) ) ) {
				$new_matches[] = $matches[ $i ];
			}
		}

		return $new_matches;
	}

	/**
	 * 必要なレベルだけ抽出
	 *
	 * @param array $matches Matches.
	 *
	 * @return array
	 */
	private function get_required_levels( $matches ) {
		if ( count( $this->levels ) < 6 ) {
			$new_matches = [];
			for ( $i = 0; $i < count( $matches ); $i ++ ) {
				if ( in_array( (int) $matches[ $i ][2], $this->levels, true ) ) {
					$new_matches[] = $matches[ $i ];
				}
			}
			$matches = $new_matches;
		}

		return $matches;
	}

	/**
	 * 目次設定
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 */
	public function customize_register( $wp_customize ) {
		$customizer = new Customize_Control( $wp_customize );
		$customizer->add_section(
			[
				'section'     => 'ys_design_toc',
				'title'       => '目次',
				'description' => Admin::manual_link( 'manual/table-of-contents' ),
				'priority'    => 115,
				'panel'       => Design::PANEL_NAME,
			]
		);
		$customizer->add_section_label( '目次タイトル' );
		$customizer->add_text(
			[
				'id'      => 'ys_toc_title',
				'label'   => '目次タイトル',
				'default' => '目次',
			]
		);
		$customizer->add_section_label( '目次の表示位置' );
		$customizer->add_select(
			[
				'id'      => 'ys_toc_display_type',
				'default' => 'content',
				'label'   => '目次の表示位置',
				'choices' => [
					'none'    => '表示しない',
					'content' => '最初の見出しの上(投稿内)',
					'widget'  => 'ウィジェット・ショートコードのみ',
				],
			]
		);
		$customizer->add_section_label( '表示条件' );
		$customizer->add_label(
			[
				'id'    => 'ys_toc_level_label',
				'label' => '目次に含める見出しレベル',
			]
		);
		for ( $i = 1; $i <= 6; $i ++ ) {
			$default = $i <= 3 ? 1 : 0;
			$customizer->add_checkbox(
				[
					'id'      => 'ys_toc_level_' . $i,
					'label'   => 'h' . $i,
					'default' => $default,
				]
			);
		}
		$customizer->add_number(
			[
				'id'          => 'ys_toc_required_count',
				'default'     => 3,
				'label'       => '目次の表示に必要な見出しの数',
				'input_attrs' => [
					'min' => 1,
					'max' => 10,
				],
				'description' => '例)設定が「3」の場合、投稿内に3つ以上見出しがある場合に目次が表示されます。',
			]
		);

		$customizer->add_section_label(
			'目次を無効化するタイプ',
			[
				'description' => '目次を<strong>表示しない</strong>投稿タイプにチェックをつけてください。',
			]
		);
		$post_types = Utility::get_post_types( [], [ 'ys-parts' ] );

		foreach ( $post_types as $name => $label ) {
			$customizer->add_checkbox(
				[
					'id'      => 'ys_disable_toc_post_type_' . $name,
					'label'   => $label,
					'default' => 0,
				]
			);
		}
	}
}

$class_toc = new TOC();
$class_toc->register();
