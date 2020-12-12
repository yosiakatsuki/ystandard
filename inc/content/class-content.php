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
		add_filter( 'the_content', [ $this, 'the_content_hook' ] );
		add_filter( 'get_the_excerpt', [ $this, 'get_the_excerpt' ], 10, 2 );
		add_filter( 'widget_text', [ $this, 'responsive_iframe' ] );
		add_filter( 'document_title_separator', [ $this, 'title_separator' ] );
		add_filter( 'excerpt_length', [ $this, 'excerpt_length' ], 999 );
		add_action( 'customize_register', [ $this, 'customize_register_post' ] );
		add_action( 'customize_register', [ $this, 'customize_register_page' ] );
		add_action( 'ys_after_site_header', [ $this, 'header_post_thumbnail' ] );
		add_action(
			'set_singular_content',
			function () {
				add_action(
					'ys_singular_header',
					[ $this, 'post_thumbnail_default' ],
					self::get_header_priority( 'post-thumbnail' )
				);
				add_action(
					'ys_singular_header',
					[ $this, 'singular_title' ],
					self::get_header_priority( 'title' )
				);
				add_action(
					'ys_singular_header',
					[ $this, 'singular_meta' ],
					self::get_header_priority( 'meta' )
				);
				add_action(
					'ys_singular_footer',
					[ $this, 'related_posts' ],
					self::get_footer_priority( 'related' )
				);
			}
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
	 * Fallback Post Type
	 *
	 * @param string $post_type Post type.
	 *
	 * @return string
	 */
	public static function get_fallback_post_type( $post_type ) {

		if ( 'post' === $post_type ) {
			return 'post';
		}
		if ( 'page' === $post_type ) {
			return 'page';
		}
		if ( 'attachment' === $post_type ) {
			return 'attachment';
		}

		return is_post_type_hierarchical( $post_type ) ? 'page' : 'post';
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
		if ( ! Template::is_active_post_header() ) {
			return false;
		}
		if ( ! has_post_thumbnail( $post_id ) ) {
			$result = false;
		}
		$post_type = self::get_post_type();
		$filter    = apply_filters( "ys_show_${post_type}_header_thumbnail", null );
		if ( is_null( $filter ) ) {
			$fallback = Content::get_fallback_post_type( $post_type );
			$option   = Option::get_option_by_bool( "ys_show_${fallback}_header_thumbnail", true );
		} else {
			$option = $filter;
		}
		if ( is_singular( $post_type ) ) {
			$result = ! $option ? false : $result;
		}

		return apply_filters( 'ys_is_active_post_thumbnail', $result );
	}

	/**
	 * フル幅サムネイル設定か
	 *
	 * @return bool
	 */
	public static function is_full_post_thumbnail() {

		$post_type = self::get_post_type();
		$filter    = apply_filters( "ys_${post_type}_post_thumbnail_type", null );
		if ( is_null( $filter ) ) {
			$fallback = Content::get_fallback_post_type( $post_type );
			$type     = Option::get_option( "ys_${fallback}_post_thumbnail_type", 'default' );
		} else {
			$type = $filter;
		}

		if ( is_singular( $post_type ) && 'full' === $type ) {
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
		if ( ! is_singular() ) {
			return false;
		}
		$post_type = self::get_post_type();
		$filter    = apply_filters( "ys_show_${post_type}_related", null );
		if ( is_null( $filter ) ) {
			$fallback = Content::get_fallback_post_type( $post_type );
			$show     = Option::get_option_by_bool( "ys_show_${fallback}_related", true );
		} else {
			$show = $filter;
		}

		if ( ! $show ) {
			return false;
		}
		if ( Utility::to_bool( Content::get_post_meta( 'ys_hide_related' ) ) ) {
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
		$post_type = self::get_post_type();
		$filter    = apply_filters( "ys_show_${post_type}_publish_date", null );
		if ( is_null( $filter ) ) {
			$fallback = Content::get_fallback_post_type( $post_type );
			$option   = Option::get_option( "ys_show_${fallback}_publish_date", 'both' );
		} else {
			$option = $filter;
		}

		if ( 'none' === $option || false === $option ) {
			return false;
		}
		if ( Utility::to_bool( Content::get_post_meta( 'ys_hide_publish_date' ) ) ) {
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

		$post_type = self::get_post_type();
		$filter    = apply_filters( "ys_show_${post_type}_header_taxonomy", null );
		if ( is_null( $filter ) ) {
			$fallback = Content::get_fallback_post_type( $post_type );
			$show     = Option::get_option_by_bool( "ys_show_${fallback}_header_category", true );
		} else {
			$show = $filter;
		}

		if ( ! Utility::to_bool( $show ) ) {
			return '';
		}

		$result     = [];
		$taxonomies = apply_filters(
			"ys_get_${post_type}_header_taxonomy",
			self::get_post_header_taxonomies()
		);
		if ( empty( $taxonomies ) ) {
			return '';
		}
		foreach ( $taxonomies as $taxonomy ) {
			$term = get_the_terms( false, $taxonomy );
			if ( is_wp_error( $term ) || empty( $term ) ) {
				return '';
			}
			$result[] = sprintf(
				'<div class="singular-header__terms">%s%s</div>',
				Utility::get_taxonomy_icon( $taxonomy ),
				$term[0]->name
			);
		}

		return apply_filters(
			"ys_get_${post_type}_header_category",
			implode( '', $result ),
			$taxonomies
		);
	}

	/**
	 * 投稿詳細ヘッダー用表示タクソノミー取得
	 *
	 * @return array|bool
	 */
	public static function get_post_header_taxonomies() {
		$taxonomies = get_the_taxonomies();
		if ( ! $taxonomies ) {
			return false;
		}

		$taxonomy = array_keys( $taxonomies );

		if ( 'post' === get_post_type( get_the_ID() ) ) {
			$taxonomy = [ 'category' ];
		}

		return $taxonomy;
	}

	/**
	 * 関連記事表示
	 */
	public function related_posts() {
		if ( ! self::is_active_related_posts() ) {
			return;
		}

		$tax_filter = '';
		$content    = '';
		$post_type  = self::get_post_type();

		if ( is_singular( 'post' ) ) {
			$tax_filter = 'category';
		} elseif ( is_singular() ) {
			$taxonomies = get_the_taxonomies();
			if ( $taxonomies ) {
				$tax_filter = array_key_first( $taxonomies );
			}
		}
		if ( ! empty( $tax_filter ) ) {
			$tax_filter = apply_filters(
				"ys_${post_type}_related_posts_taxonomy",
				$tax_filter
			);
			$tax_filter = ! empty( $tax_filter ) ? "tax:${tax_filter}," : '';

			$related = new Recent_Posts();
			$content = $related->do_shortcode(
				[
					'post_type' => self::get_post_type(),
					'count'     => 6,
					'filter'    => "${tax_filter}same-post",
					'list_type' => 'card',
					'orderby'   => 'rand',
					'cache'     => 'related_posts',
					'run_type'  => 'related_posts',
				]
			);
		}
		$content = apply_filters(
			"ys_${post_type}_related_posts",
			$content
		);
		if ( $content ) {
			$related_post_title = apply_filters(
				'ys_related_post_title',
				__( 'Related Posts', 'ystandard' )
			);
			$related_post       = sprintf(
				'<div class="post-related">%s%s</div>',
				"<p class=\"post-related__title\">${related_post_title}</p>",
				$content
			);

			echo $related_post;
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
		$thumbnail = $this->get_header_post_thumbnail();
		if ( empty( $thumbnail ) ) {
			return;
		}
		ob_start();
		Template::get_template_part(
			'template-parts/parts/header-thumbnail',
			'',
			[ 'header_thumbnail' => $thumbnail ]
		);
		echo ob_get_clean();
	}

	/**
	 * ヘッダーサムネイル取得
	 *
	 * @return string
	 */
	private function get_header_post_thumbnail() {

		$hook = apply_filters( 'ys_get_header_post_thumbnail', null );
		if ( ! is_null( $hook ) ) {
			return $hook;
		}
		if ( ! self::is_full_post_thumbnail() ) {
			return '';
		}
		if ( ! self::is_active_post_thumbnail() ) {
			return '';
		}

		return get_the_post_thumbnail(
			get_the_ID(),
			'post-thumbnail',
			[
				'id'    => 'site-header-thumbnail__image',
				'class' => 'site-header-thumbnail__image',
				'alt'   => get_the_title(),
			]
		);
	}

	/**
	 * 投稿タイトル
	 */
	public function singular_title() {
		ob_start();
		Template::get_template_part( 'template-parts/parts/post-title' );
		echo ob_get_clean();
	}

	/**
	 * 投稿メタ情報
	 */
	public function singular_meta() {
		$date = '';
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

		$header_meta = sprintf(
			'<div class="singular-header__meta">%s%s</div>',
			$date,
			$cat
		);

		echo apply_filters( 'ys_singular_header_meta', $header_meta );
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
	 * Contentのフック
	 *
	 * @param string $content Content.
	 *
	 * @return string
	 */
	public function the_content_hook( $content ) {
		// Moreタグの置換.
		$content = $this->replace_more( $content );
		// Iframeのレスポンシブ対応.
		$content = $this->responsive_iframe( $content );
		// 最初の見出し置換.
		$content = $this->replace_first_heading( $content );

		return $content;
	}

	/**
	 * Hook:get_the_excerpt
	 *
	 * @param string   $content excerpt.
	 * @param \WP_Post $post    Post.
	 */
	public function get_the_excerpt( $content, $post ) {
		return self::get_custom_excerpt( ' …', 0, $post->ID );
	}

	/**
	 * Moreタグの置換
	 *
	 * @param string $content Content.
	 *
	 * @return string
	 */
	public function replace_first_heading( $content ) {

		if ( preg_match_all( '/(<h([1-6]{1})[^>]*>).*<\/h\2>/msuU', $content, $matches, PREG_SET_ORDER ) ) {
			$replace = apply_filters( 'ys_before_first_heading_content', '', $content );
			if ( isset( $matches[0] ) && isset( $matches[0][0] ) ) {
				$content = str_replace(
					$matches[0][0],
					$replace . $matches[0][0],
					$content
				);
			}
		}

		return $content;
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
				$pattern = '/<iframe[^>]+?' . $value['url'] . '[^<]+?<\/iframe>/is';
				if ( preg_match_all( $pattern, $content, $match ) ) {
					foreach ( $match[0] as $map ) {
						$aspect = preg_match( '/data-aspect-ratio="(.+)?"/is', $map, $aspect_match );
						if ( empty( $aspect ) || ( isset( $aspect_match[1] ) && 'none' !== $aspect_match[1] ) ) {
							$embed_aspect = isset( $aspect_match[1] ) ? $aspect_match[1] : $value['aspect'];
							// 変換.
							$replace = '<div class="wp-embed-aspect-' . $embed_aspect . ' wp-has-aspect-ratio"><div class="wp-block-embed__wrapper">' . $map . '</div></div>';
							$content = str_replace( $map, $replace, $content );
						}
					}
				}
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
			$length  = $length - mb_strlen( $sep );
			$length  = 0 > $length ? 1 : $length;
			$content = mb_substr( $content, 0, $length ) . $sep;
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

		return Option::get_option_by_int( 'ys_option_excerpt_length', 80 );
	}

	/**
	 * ブログ名区切り文字設定
	 *
	 * @param string $sep 区切り文字.
	 *
	 * @return string
	 */
	public function title_separator( $sep ) {
		$sep_option = Option::get_option( 'ys_title_separate', '' );
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
				'section'     => 'ys_design_post',
				'title'       => '投稿ページ',
				'priority'    => 100,
				'description' => Admin::manual_link( 'manual/post-layout' ),
				'panel'       => Design::PANEL_NAME,
			]
		);
		$customizer->add_section_label( 'レイアウト' );
		// 表示カラム数.
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
		// アイキャッチの表示.
		$customizer->add_label(
			[
				'id'    => 'ys_show_post_header_thumbnail_label',
				'label' => 'アイキャッチ画像の表示設定',
			]
		);
		$customizer->add_checkbox(
			[
				'id'      => 'ys_show_post_header_thumbnail',
				'default' => 1,
				'label'   => 'アイキャッチ画像を表示する',
			]
		);
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
				'id'          => 'ys_post_after_contents_section_label',
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
				'section'     => 'ys_design_page',
				'title'       => '固定ページ',
				'priority'    => 110,
				'description' => Admin::manual_link( 'manual/page-layout' ),
				'panel'       => Design::PANEL_NAME,
			]
		);
		$customizer->add_section_label( 'レイアウト' );
		// 表示カラム数.
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
		// アイキャッチの表示.
		$customizer->add_label(
			[
				'id'    => 'ys_show_page_header_thumbnail_label',
				'label' => 'アイキャッチ画像の表示設定',
			]
		);
		$customizer->add_checkbox(
			[
				'id'      => 'ys_show_page_header_thumbnail',
				'default' => 1,
				'label'   => 'アイキャッチ画像を表示する',
			]
		);
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
}

$class_content = new Content();
$class_content->register();
