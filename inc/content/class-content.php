<?php
/**
 * コンテンツ部分の処理
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

/**
 * Class Content
 *
 * @package ystandard
 */
class Content {

	/**
	 * フッターコンテンツの優先順位
	 */
	const FOOTER_PRIORITY = [
		'widget'    => 10,
		'ad'        => 20,
		'sns-share' => 30,
		'taxonomy'  => 40,
		'author'    => 50,
		'related'   => 60,
		'comment'   => 70,
		'paging'    => 80,
	];

	/**
	 * ヘッダーコンテンツの優先順位
	 */
	const HEADER_PRIORITY = [
		'post-thumbnail' => 10,
		'title'          => 20,
		'meta'           => 30,
		'sns-share'      => 40,
		'ad'             => 50,
		'widget'         => 60,
	];

	/**
	 * アクション・フィルターの登録
	 */
	public function register() {
		add_filter( 'post_class', [ $this, 'post_class' ] );
		add_filter( 'the_content', [ $this, 'replace_more' ] );
		add_filter( 'the_content', [ $this, 'responsive_iframe' ] );
		add_filter( 'get_the_excerpt', [ '\ystandard\Content', 'get_custom_excerpt' ] );
		add_filter( 'widget_text', [ $this, 'responsive_iframe' ] );
		add_filter( 'get_the_archive_title', [ $this, 'archive_title' ] );
		add_filter( 'document_title_separator', [ $this, 'title_separator' ] );
		add_filter( 'excerpt_length', [ $this, 'excerpt_length' ], 999 );
		add_action( 'customize_register', [ $this, 'customize_register_post' ] );
		add_action( 'customize_register', [ $this, 'customize_register_page' ] );
		add_action( 'customize_register', [ $this, 'customize_register_archive' ] );
		add_action( 'ys_after_site_header', [ $this, 'header_post_thumbnail' ] );

		add_filter(
			'ys_singular_header',
			[ $this, 'post_thumbnail_default' ],
			self::get_header_priority( 'post-thumbnail' )
		);
		add_filter(
			'ys_singular_header',
			[ $this, 'singular_title' ],
			self::get_header_priority( 'title' )
		);
		add_filter(
			'ys_singular_header',
			[ $this, 'singular_meta' ],
			self::get_header_priority( 'meta' )
		);
		add_filter(
			'ys_singular_footer',
			[ $this, 'related_posts' ],
			self::get_footer_priority( 'related' )
		);


	}


	/**
	 * コンテンツヘッダーの優先順位取得
	 *
	 * @param string $key Key.
	 *
	 * @return int
	 */
	public static function get_header_priority( $key ) {

		$list = apply_filters(
			'ys_get_content_header_priority',
			self::HEADER_PRIORITY
		);

		if ( isset( $list[ $key ] ) ) {
			return $list[ $key ];
		}

		return 10;
	}

	/**
	 * コンテンツフッターの優先順位取得
	 *
	 * @param string $key Key.
	 *
	 * @return int
	 */
	public static function get_footer_priority( $key ) {

		$list = apply_filters(
			'ys_get_content_footer_priority',
			self::FOOTER_PRIORITY
		);

		if ( isset( $list[ $key ] ) ) {
			return $list[ $key ];
		}

		return 10;
	}

	/**
	 * Post Type
	 *
	 * @return false|string
	 * @global \WP_Query
	 */
	public static function get_post_type() {
		global $wp_query;
		$post_type = get_post_type();
		if ( ! $post_type ) {
			if ( isset( $wp_query->query['post_type'] ) ) {
				$post_type = $wp_query->query['post_type'];
			}
		}

		return $post_type;
	}

	/**
	 * アイキャッチ画像を表示するか
	 *
	 * @param int $post_id 投稿ID.
	 *
	 * @return bool
	 */
	public static function is_active_post_thumbnail( $post_id = null ) {
		$result = true;
		if ( ! is_singular() ) {
			return false;
		}
		if ( ! has_post_thumbnail( $post_id ) ) {
			$result = false;
		}
		/**
		 * 投稿ページ
		 */
		if ( is_single() ) {
			if ( ! Option::get_option_by_bool( 'ys_show_post_thumbnail', true ) ) {
				$result = false;
			}
		}
		/**
		 * 固定ページ
		 */
		if ( is_page() ) {
			if ( ! Option::get_option_by_bool( 'ys_show_page_thumbnail', true ) ) {
				$result = false;
			}
		}

		return apply_filters( 'ys_is_active_post_thumbnail', $result );
	}

	/**
	 * フル幅サムネイル設定か
	 *
	 * @return bool
	 */
	public static function is_full_post_thumbnail() {
		if ( is_single() && 'full' === Option::get_option( 'ys_post_post_thumbnail_type', 'default' ) ) {
			return true;
		}
		if ( is_page() && 'full' === Option::get_option( 'ys_page_post_thumbnail_type', 'default' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * 関連記事の表示が有効か
	 *
	 * @return bool
	 */
	public static function is_active_related_posts() {
		if ( ! is_single() ) {
			return false;
		}
		if ( ! Option::get_option_by_bool( 'ys_show_post_related', true ) ) {
			return false;
		}

		return true;
	}

	/**
	 * 投稿日・更新日データ取得
	 *
	 * @return array|bool
	 */
	public static function get_post_date_data() {
		$text_format     = get_option( 'date_format' );
		$datetime_format = 'Y-m-d';
		$result          = [];
		/**
		 * 設定取得
		 */
		$option = is_page() ? 'page' : 'post';
		$option = Option::get_option( "ys_show_${option}_publish_date", 'both' );
		if ( 'none' === $option ) {
			return false;
		}
		// 更新日取得.
		if ( 'publish' !== $option ) {
			if ( get_the_time( 'Ymd' ) < get_the_modified_time( 'Ymd' ) ) {
				$icon     = 'update' === $option ? 'calendar' : 'rotate-cw';
				$result[] = [
					'text'     => get_the_modified_time( $text_format ),
					'datetime' => get_the_modified_time( $datetime_format ),
					'time'     => true,
					'icon'     => Icon::get_icon( $icon ),
				];
			}
		}
		// 投稿日取得.
		if ( 'update' !== $option || empty( $result ) ) {
			$time     = empty( $result ) ? true : false;
			$result[] = [
				'text'     => get_the_time( $text_format ),
				'datetime' => get_the_time( $datetime_format ),
				'time'     => $time,
				'icon'     => Icon::get_icon( 'calendar' ),
			];
		}

		return array_reverse( $result );
	}

	/**
	 * 投稿ヘッダーのカテゴリー情報を取得
	 */
	public static function get_post_header_category() {

		if ( ! Option::get_option_by_bool( 'ys_show_post_header_category', true ) ) {
			return '';
		}

		$taxonomy = apply_filters( 'ys_get_post_header_taxonomy', 'category' );
		$term     = get_the_terms( false, $taxonomy );
		if ( is_wp_error( $term ) || empty( $term ) ) {
			return '';
		}

		return sprintf(
			'<div class="singular-header__category">%s%s</div>',
			Icon::get_icon( 'folder' ),
			$term[0]->name
		);
	}

	/**
	 * 関連記事表示
	 */
	public function related_posts() {
		if ( ! self::is_active_related_posts() ) {
			return;
		}
		$related = new Recent_Posts();
		$content = $related->do_shortcode(
			[
				'count'     => 6,
				'filter'    => 'category,same-post',
				'list_type' => 'card',
				'orderby'   => 'rand',
			]
		);
		if ( $content ) {
			echo sprintf(
				'<div class="post-related">%s%s</div>',
				'<p class="post-related__title">関連記事</p>',
				$content
			);
		}
	}

	/**
	 * アイキャッチ画像の表示
	 */
	public function post_thumbnail_default() {
		if ( self::is_full_post_thumbnail() ) {
			return;
		}
		if ( ! self::is_active_post_thumbnail() ) {
			return;
		}
		ob_start();
		Template::get_template_part( 'template-parts/parts/post-thumbnail' );
		echo ob_get_clean();
	}

	/**
	 * アイキャッチ画像の表示 - ヘッダー
	 */
	public function header_post_thumbnail() {
		if ( ! self::is_full_post_thumbnail() ) {
			return;
		}
		if ( ! self::is_active_post_thumbnail() ) {
			return;
		}
		ob_start();
		Template::get_template_part( 'template-parts/parts/header-post-thumbnail' );
		echo ob_get_clean();
	}


	/**
	 * 投稿タイトル
	 */
	public function singular_title() {
		do_action( 'ys_singular_before_title' );
		the_title(
			'<h1 class="singular-header__title entry-title">',
			'</h1>'
		);
		do_action( 'ys_singular_after_title' );
	}

	/**
	 * 投稿メタ情報
	 */
	public function singular_meta() {
		$date = '';
		$cat  = '';
		// 投稿日・更新日.
		$post_date = self::get_post_date_data();
		if ( ! empty( $post_date ) ) {
			ob_start();
			Template::get_template_part(
				'template-parts/parts/post-date',
				'',
				[ 'post_date' => $post_date ]
			);
			$date = ob_get_clean();
		}
		// カテゴリー.
		$cat = self::get_post_header_category();

		printf(
			'<div class="singular-header__meta">%s%s</div>',
			$date,
			$cat
		);
	}


	/**
	 * Post Classを操作する
	 *
	 * @param array $classes Classes.
	 *
	 * @return array
	 */
	public function post_class( $classes ) {
		/**
		 * [hentryの削除]
		 */
		if ( apply_filters( 'ystd_remove_hentry', true ) ) {
			$classes = array_diff( $classes, [ 'hentry' ] );
		}
		/**
		 * アイキャッチ画像の有無
		 */
		if ( is_singular() ) {
			if ( self::is_active_post_thumbnail() ) {
				$classes[] = 'has-thumbnail';
			}
		}

		return $classes;
	}

	/**
	 * Moreタグの置換
	 *
	 * @param string $content Content.
	 *
	 * @return string
	 */
	public function replace_more( $content ) {

		$replace = apply_filters( 'ys_more_content', '' );
		if ( '' !== $replace ) {
			$content = preg_replace(
				'/<p><span id="more-[0-9]+"><\/span><\/p>/',
				$replace,
				$content
			);
			/**
			 * 「remove_filter( 'the_content', 'wpautop' )」対策
			 */
			$content = preg_replace(
				'/<span id="more-[0-9]+"><\/span>/',
				$replace,
				$content
			);
		}

		return $content;
	}

	/**
	 * 投稿内のiframeレスポンシブ対応
	 *
	 * @param string $content Content.
	 *
	 * @return string
	 */
	public function responsive_iframe( $content ) {
		if ( AMP::is_amp() ) {
			return $content;
		}
		/**
		 * マッチさせたいiframeのURLをリスト化
		 */
		$list = [
			[
				'url'    => 'https:\/\/www\.google\.com\/maps\/embed',
				'aspect' => '4-3',
			],
		];
		$list = apply_filters( 'ys_responsive_iframe_pattern', $list );
		/**
		 * 置換する
		 */
		foreach ( $list as $value ) {
			if ( isset( $value['url'] ) && isset( $value['aspect'] ) ) {
				$replace = '<div class="wp-embed-aspect-' . $value['aspect'] . ' wp-has-aspect-ratio"><div class="wp-block-embed__wrapper">${0}</div></div>';
				$pattern = '/<iframe[^>]+?' . $value['url'] . '[^<]+?<\/iframe>/is';
				$content = preg_replace( $pattern, $replace, $content );
			}
		}

		return $content;
	}

	/**
	 * 投稿オプション(post-meta)取得
	 *
	 * @param string  $key     設定キー.
	 * @param integer $post_id 投稿ID.
	 *
	 * @return string
	 */
	public static function get_post_meta( $key, $post_id = 0 ) {
		if ( 0 === $post_id ) {
			$post_id = get_the_ID();
		}

		return get_post_meta( $post_id, $key, true );
	}

	/**
	 * アーカイブタイトル
	 *
	 * @param string $title title.
	 *
	 * @return string
	 */
	public function archive_title( $title ) {
		/**
		 * 標準フォーマット
		 */
		$title_format = apply_filters( 'ys_archive_title_format', '%s' );
		/**
		 * ページング
		 */
		$paged = get_query_var( 'paged' );

		if ( is_category() ) {
			$title = sprintf( $title_format, single_cat_title( '', false ) );
		} elseif ( is_tag() ) {
			$title = sprintf( $title_format, single_tag_title( '', false ) );
		} elseif ( is_author() ) {
			$title = sprintf( $title_format, get_the_author() );
		} elseif ( is_search() ) {
			$title_format = '「%s」の検索結果';
			$title        = sprintf( $title_format, esc_html( get_search_query( false ) ) );
		} elseif ( is_post_type_archive() ) {
			$title = sprintf( $title_format, post_type_archive_title( '', false ) );
		} elseif ( is_tax() ) {
			$object = get_queried_object();
			$tax    = get_taxonomy( $object->taxonomy );
			/* translators: 1: Taxonomy singular name, 2: Current taxonomy term */
			$title = sprintf(
				'%1$s : %2$s',
				$tax->labels->singular_name,
				single_term_title( '', false )
			);
		} elseif ( is_home() ) {
			if ( 'page' === get_option( 'show_on_front' ) && get_option( 'page_for_posts' ) ) {
				$title = get_the_title( get_option( 'page_for_posts' ) );
			} else {
				$title = '';
			}
		}

		return apply_filters( 'ys_get_the_archive_title', $title, $paged );
	}

	/**
	 * アーカイブURL
	 */
	public static function get_archive_url() {
		$url            = '';
		$queried_object = get_queried_object();
		if ( is_category() ) {
			$url = get_category_link( $queried_object->term_id );
		} elseif ( is_tag() ) {
			$url = get_tag_link( $queried_object->term_id );
		} elseif ( is_author() ) {
			$author_id = get_query_var( 'author' );
			$url       = esc_url( get_author_posts_url( get_the_author_meta( 'ID', $author_id ) ) );
		} elseif ( is_search() ) {
			$url = home_url( '/?s=' . urlencode( get_search_query( false ) ) );
			$url = get_post_type_archive_link( esc_url_raw( $url ) );
		} elseif ( is_post_type_archive() ) {
			$post_type = self::get_post_type();
			$url       = get_post_type_archive_link( $post_type );
		} elseif ( is_tax() ) {
			$tax = get_taxonomy( $queried_object->taxonomy );
			$url = get_term_link( $queried_object->term_id, $tax->name );
		}

		return apply_filters( 'ys_get_the_archive_url', $url );
	}

	/**
	 * アーカイブアイテムクラス作成
	 *
	 * @return array
	 */
	public static function get_archive_item_class() {
		$class = [];
		/**
		 * 共通でセットするクラス
		 */
		$class[] = 'archive__item';
		$class[] = 'is-' . Option::get_option( 'ys_archive_type', 'card' );

		return $class;
	}


	/**
	 * 投稿抜粋文を作成
	 *
	 * @param string  $sep     抜粋最後の文字.
	 * @param integer $length  抜粋長さ.
	 * @param integer $post_id 投稿ID.
	 *
	 * @return string
	 */
	public static function get_custom_excerpt( $sep = ' …', $length = 0, $post_id = 0 ) {
		$length  = 0 === $length ? Option::get_option_by_int( 'ys_option_excerpt_length', 80 ) : $length;
		$content = self::get_custom_excerpt_raw( $post_id );
		/**
		 * 長さ調節
		 */
		if ( mb_strlen( $content ) > $length ) {
			$content = mb_substr( $content, 0, $length - mb_strlen( $sep ) ) . $sep;
		}

		return apply_filters( 'ys_get_the_custom_excerpt', $content, $post_id );
	}

	/**
	 * 切り取らない投稿抜粋文を作成
	 *
	 * @param integer $post_id 投稿ID.
	 *
	 * @return string
	 */
	public static function get_custom_excerpt_raw( $post_id = 0 ) {
		$post_id = 0 === $post_id ? get_the_ID() : $post_id;
		$post    = get_post( $post_id );
		if ( post_password_required( $post ) ) {
			return __( 'There is no excerpt because this is a protected post.' );
		}
		$content = $post->post_excerpt;
		if ( '' === $content ) {
			/**
			 * Excerptが無ければ本文から作る
			 */
			$content = $post->post_content;
			/**
			 * Moreタグ以降を削除
			 */
			$content = preg_replace( '/<!--more-->.+/is', '', $content );
			$content = Utility::get_plain_text( $content );
		}

		return $content;
	}

	/**
	 * 投稿抜粋文字数
	 *
	 * @param int $length 抜粋文字数.
	 *
	 * @return int
	 */
	public function excerpt_length( $length = null ) {

		return ys_get_option_by_int( 'ys_option_excerpt_length', 80 );
	}

	/**
	 * ブログ名区切り文字設定
	 *
	 * @param string $sep 区切り文字.
	 *
	 * @return string
	 */
	public function title_separator( $sep ) {
		$sep_option = ys_get_option( 'ys_title_separate', '' );
		if ( '' !== $sep_option ) {
			$sep = $sep_option;
		}

		return $sep;
	}

	/**
	 * 投稿設定
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 */
	public function customize_register_post( $wp_customize ) {
		$customizer = new Customize_Control( $wp_customize );
		$customizer->add_section(
			[
				'section'  => 'ys_design_post',
				'title'    => '投稿ページ',
				'priority' => 100,
				'panel'    => Design::PANEL_NAME,
			]
		);
		$customizer->add_section_label( 'レイアウト' );
		// 表示カラム数
		$col1 = Customizer::get_assets_dir_uri( '/design/column-type/col-1.png' );
		$col2 = Customizer::get_assets_dir_uri( '/design/column-type/col-2.png' );
		$img  = '<img src="%s" alt="" width="100" height="100" />';
		$customizer->add_image_label_radio(
			[
				'id'          => 'ys_post_layout',
				'default'     => '2col',
				'label'       => 'ページレイアウト',
				'description' => '投稿ページの表示レイアウト',
				'choices'     => [
					'2col' => sprintf( $img, $col2 ),
					'1col' => sprintf( $img, $col1 ),
				],
			]
		);
		// アイキャッチ.
		$default = Customizer::get_assets_dir_uri( '/design/eye-catch/default.png' );
		$full    = Customizer::get_assets_dir_uri( '/design/eye-catch/full.png' );
		$img     = '<img src="%s" alt="" width="100" height="100" />';
		$customizer->add_image_label_radio(
			[
				'id'      => 'ys_post_post_thumbnail_type',
				'default' => 'default',
				'label'   => 'アイキャッチ画像の表示タイプ',
				'choices' => [
					'default' => sprintf( $img, $default ),
					'full'    => sprintf( $img, $full ),
				],
			]
		);
		$customizer->add_section_label( '記事上部' );
		// 投稿日時を表示する.
		$customizer->add_select(
			[
				'id'      => 'ys_show_post_publish_date',
				'default' => 'both',
				'label'   => '投稿日・更新日の表示タイプ',
				'choices' => [
					'both'    => '投稿日・更新日',
					'publish' => '投稿日のみ',
					'update'  => '更新日のみ',
					'none'    => '表示しない',
				],
			]
		);
		$customizer->add_label(
			[
				'id'    => 'ys_show_post_header_category_label',
				'label' => 'カテゴリー情報の表示設定',
			]
		);
		// カテゴリー表示.
		$customizer->add_checkbox(
			[
				'id'          => 'ys_show_post_header_category',
				'default'     => 1,
				'label'       => 'カテゴリー情報を表示する',
				'description' => '※ページ上部では1件のみ表示されます',
			]
		);
		$customizer->add_section_label( '記事下部' );
		$customizer->add_label(
			[
				'id'          => 'ys_after_contents_section_label',
				'label'       => '記事下コンテンツの表示・非表示設定',
				'description' => '※シェアボタンの表示は「[ys]SNS」→「SNSシェアボタン」から設定できます',
			]
		);
		// カテゴリー・タグ.
		$customizer->add_checkbox(
			[
				'id'      => 'ys_show_post_category',
				'default' => 1,
				'label'   => 'カテゴリー・タグ',
			]
		);
		// 著者情報を表示する.
		$customizer->add_checkbox(
			[
				'id'      => 'ys_show_post_author',
				'default' => 1,
				'label'   => '著者情報',
			]
		);
		// 関連記事.
		$customizer->add_checkbox(
			[
				'id'      => 'ys_show_post_related',
				'default' => 1,
				'label'   => '関連記事',
			]
		);
		// 次の記事・前の記事.
		$customizer->add_checkbox(
			[
				'id'      => 'ys_show_post_paging',
				'default' => 1,
				'label'   => '次の記事・前の記事',
			]
		);
	}

	/**
	 * 固定ページ設定
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 */
	public function customize_register_page( $wp_customize ) {
		$customizer = new Customize_Control( $wp_customize );
		$customizer->add_section(
			[
				'section'  => 'ys_design_page',
				'title'    => '固定ページ',
				'priority' => 110,
				'panel'    => Design::PANEL_NAME,
			]
		);
		$customizer->add_section_label( 'レイアウト' );
		// 表示カラム数
		$col1 = Customizer::get_assets_dir_uri( '/design/column-type/col-1.png' );
		$col2 = Customizer::get_assets_dir_uri( '/design/column-type/col-2.png' );
		$img  = '<img src="%s" alt="" width="100" height="100" />';
		$customizer->add_image_label_radio(
			[
				'id'          => 'ys_page_layout',
				'default'     => '2col',
				'label'       => 'ページレイアウト',
				'description' => '固定ページの表示レイアウト',
				'choices'     => [
					'2col' => sprintf( $img, $col2 ),
					'1col' => sprintf( $img, $col1 ),
				],
			]
		);
		// アイキャッチ.
		$default = Customizer::get_assets_dir_uri( '/design/eye-catch/default.png' );
		$full    = Customizer::get_assets_dir_uri( '/design/eye-catch/full.png' );
		$img     = '<img src="%s" alt="" width="100" height="100" />';
		$customizer->add_image_label_radio(
			[
				'id'      => 'ys_page_post_thumbnail_type',
				'default' => 'default',
				'label'   => 'アイキャッチ画像の表示タイプ',
				'choices' => [
					'default' => sprintf( $img, $default ),
					'full'    => sprintf( $img, $full ),
				],
			]
		);
		$customizer->add_section_label( '記事上部' );
		// 投稿日時を表示する.
		$customizer->add_select(
			[
				'id'      => 'ys_show_page_publish_date',
				'default' => 'both',
				'label'   => '投稿日・更新日の表示タイプ',
				'choices' => [
					'both'    => '投稿日・更新日',
					'publish' => '投稿日のみ',
					'update'  => '更新日のみ',
					'none'    => '表示しない',
				],
			]
		);
		$customizer->add_section_label( '記事下部' );
		$customizer->add_label(
			[
				'id'          => 'ys_after_contents_section_label',
				'label'       => '記事下コンテンツの表示・非表示設定',
				'description' => '※シェアボタンの表示は「[ys]SNS」→「SNSシェアボタン」から設定できます',
			]
		);
		// 著者情報を表示する.
		$customizer->add_checkbox(
			[
				'id'      => 'ys_show_page_author',
				'default' => 1,
				'label'   => '著者情報',
			]
		);
	}

	/**
	 * アーカイブページ設定
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 */
	public function customize_register_archive( $wp_customize ) {
		$customizer = new Customize_Control( $wp_customize );
		$customizer->add_section(
			[
				'section'  => 'ys_design_archive',
				'title'    => 'アーカイブページ設定',
				'panel'    => Design::PANEL_NAME,
				'priority' => 120,
			]
		);
		$customizer->add_section_label( 'レイアウト' );
		/**
		 * 表示カラム数
		 */
		$col1 = Customizer::get_assets_dir_uri( '/design/column-type/col-1.png' );
		$col2 = Customizer::get_assets_dir_uri( '/design/column-type/col-2.png' );
		$img  = '<img src="%s" alt="" width="100" height="100" />';
		$customizer->add_image_label_radio(
			[
				'id'          => 'ys_archive_layout',
				'default'     => '2col',
				'label'       => 'ページレイアウト',
				'description' => 'アーカイブページの表示レイアウト',
				'choices'     => [
					'2col' => sprintf( $img, $col2 ),
					'1col' => sprintf( $img, $col1 ),
				],
			]
		);
		/**
		 * 一覧タイプ
		 */
		$list = Customizer::get_assets_dir_uri( '/design/archive/list.png' );
		$card = Customizer::get_assets_dir_uri( '/design/archive/card.png' );
		$img  = '<img src="%s" alt="" width="100" height="100" />';
		$customizer->add_image_label_radio(
			[
				'id'          => 'ys_archive_type',
				'default'     => 'card',
				'label'       => '一覧レイアウト',
				'description' => '記事一覧の表示タイプ',
				'choices'     => [
					'card' => sprintf( $img, $card ),
					'list' => sprintf( $img, $list ),
				],
			]
		);
		$customizer->add_section_label( '表示・非表示設定' );
		// 投稿日.
		$customizer->add_checkbox(
			[
				'id'      => 'ys_show_archive_publish_date',
				'default' => 1,
				'label'   => '投稿日を表示する',
			]
		);
		// カテゴリー.
		$customizer->add_checkbox(
			[
				'id'      => 'ys_show_archive_category',
				'default' => 1,
				'label'   => 'カテゴリーを表示する',
			]
		);
		// 概要.
		$customizer->add_checkbox(
			[
				'id'      => 'ys_show_archive_description',
				'default' => 1,
				'label'   => '概要を表示する',
			]
		);
		$customizer->add_number(
			[
				'id'      => 'ys_option_excerpt_length',
				'default' => 80,
				'label'   => '概要文の文字数',
			]
		);
	}


}

$class_content = new Content();
$class_content->register();
