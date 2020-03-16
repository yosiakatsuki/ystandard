<?php
/**
 * ブログカード ショートコード クラス
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * Class YS_Shortcode_Blog_Card
 */
class YS_Shortcode_Blog_Card extends YS_Shortcode_Base {
	/**
	 * ブログカード表示データのキャッシュ用キー
	 */
	const CACHE_KEY = 'blog_card';
	/**
	 * ブログカード表示データ更新までの日数
	 */
	const CACHE_EXPIRATION = 7;

	/**
	 * ショートコードパラメーター
	 */
	const SHORTCODE_PARAM = array(
		'class'          => 'ys-blog-card',
		'url'            => '',
		'title'          => '',
		'dscr'           => '',
		'description'    => '',
		'thumbnail'      => '',
		'thumbnail_size' => 'post-thumbnail',
		'target'         => '',
		'btn_text'       => 'この記事を見る',
		'btn_ex_text'    => 'このサイトを見る',
		'cache'          => '', // 空白 or disable.
		'show_ex_img'    => true,
		'domain'         => '',
	);

	/**
	 * ブログカード表示用データ
	 *
	 * @var array
	 */
	private $card_data = array(
		'post_id'   => 0,
		'url'       => '',
		'title'     => '',
		'dscr'      => '',
		'thumbnail' => '',
		'attr'      => '',
		'btn_text'  => '',
		'create_at' => '',
		'blog_card' => false,
		'domain'    => '',
	);

	/**
	 * キャッシュキー作成用パラメーター
	 *
	 * @var array
	 */
	private $cache_args = array();

	/**
	 * YS_Shortcode_Share_Button constructor.
	 *
	 * @param array $args ユーザー指定パラメーター.
	 */
	public function __construct( $args = array() ) {
		parent::__construct( $args, self::SHORTCODE_PARAM );

		/**
		 * キャッシュ判定用のパラメーター作成
		 */
		$this->cache_args = shortcode_atts(
			self::SHORTCODE_PARAM,
			$args
		);
	}

	/**
	 * HTML取得
	 *
	 * @param string $template_type テンプレートタイプ.
	 *
	 * @return string
	 */
	public function get_html( $template_type = '' ) {
		global $ys_blog_card_data;
		$ys_blog_card_data = false;
		/**
		 * URL
		 */
		$url = $this->get_param( 'url' );
		/**
		 * URL指定なしの場合は表示しない
		 */
		if ( '' === $url ) {
			return '';
		}
		/**
		 * [yStandard Blocks]利用中の場合、プラグイン側で処理をする
		 */
		if ( class_exists( '\ystandard_blocks\Card' ) && apply_filters( 'ys_use_ystdb_card', true ) ) {
			$ystdb_card = new \ystandard_blocks\Card();

			return $ystdb_card->render(
				array(
					'url'         => $url,
					'dscr'        => $this->get_param( 'dscr' ),
					'image'       => $this->get_param( 'thumbnail' ),
					'link_target' => $this->get_param( 'target' ),
				)
			);
		}
		/**
		 * URLチェック
		 */
		if ( ! wp_http_validate_url( $url ) ) {
			return $this->create_a_tag( $url );
		}
		/**
		 * カードデータ作成
		 */
		$this->get_card_data( $url );
		/**
		 * 準備チェック
		 */
		if ( ! $this->card_data['blog_card'] ) {
			return $this->create_a_tag( $url );
		}
		/**
		 * AMPの場合＆自サイトの場合は画像再作成
		 */
		if ( ys_is_amp() && 0 < $this->card_data['post_id'] ) {
			$this->card_data['thumbnail'] = $this->get_post_thumbnail( $this->card_data['post_id'] );
		}
		/**
		 * 展開用データ作成
		 */
		$ys_blog_card_data = $this->card_data;
		/**
		 * テンプレート拡張
		 */
		$template_type = apply_filters( 'ys_blog_card_template', $template_type );
		/**
		 * ボタン部分のHTML作成
		 */
		ob_start();
		ys_get_template_part( 'template-parts/parts/blog-card', $template_type );
		$content = ob_get_clean();
		/**
		 * ショートコード基本クラスのタイトルを消す
		 */
		$this->set_param( 'title', '' );

		return parent::get_html( $content );
	}

	/**
	 * データ作成
	 *
	 * @param string $url URL.
	 */
	private function get_card_data( $url ) {
		/**
		 * キャッシュを取得
		 */
		$data = $this->get_cache_data();
		if ( $data ) {
			/**
			 * 取得データをセット
			 */
			$this->card_data = $data;

			return;
		}
		/**
		 * 投稿IDの取得
		 */
		$post_id = $this->get_post_id( $url );
		if ( 0 !== $post_id ) {
			/**
			 * サイト内のURLの場合
			 */
			$this->get_post_data( $post_id );
		} else {
			/**
			 * 外部サイトの場合
			 */
			$this->get_site_data( $url );
		}
		/**
		 * キャッシュ更新
		 */
		$this->set_cache();

		return;
	}

	/**
	 * キャッシュデータの取得
	 */
	private function get_cache_data() {
		if ( 'disable' === $this->get_param( 'cache' ) ) {
			return false;
		}
		/**
		 * プレビュー時にキャッシュリフレッシュ
		 */
		if ( is_preview() ) {
			YS_Cache::delete_cache( self::CACHE_KEY, $this->cache_args );

			return false;
		}

		return YS_Cache::get_cache( self::CACHE_KEY, $this->cache_args );
	}

	/**
	 * キャッシュの更新
	 */
	private function set_cache() {
		if ( 'disable' !== $this->get_param( 'cache' ) ) {
			YS_Cache::set_cache(
				self::CACHE_KEY,
				$this->card_data,
				$this->cache_args,
				self::CACHE_EXPIRATION
			);
		}
	}

	/**
	 * 投稿IDを取得
	 *
	 * @param string $url URL.
	 *
	 * @return int
	 */
	private function get_post_id( $url ) {
		if ( ! $this->is_my_site( $url ) ) {
			return 0;
		}
		$post_id = url_to_postid( $url );
		/**
		 * データにセット
		 */
		$this->card_data['post_id'] = $post_id;

		return $post_id;
	}

	/**
	 * 投稿データ取得
	 *
	 * @param int $post_id Post ID.
	 */
	private function get_post_data( $post_id ) {
		$post = get_post( $post_id );
		/**
		 * URL
		 */
		$this->card_data['url'] = get_permalink( $post->ID );
		/**
		 * 画像取得
		 */
		$this->card_data['thumbnail'] = $this->get_post_thumbnail( $post_id );
		/**
		 * タイトル取得
		 */
		$title = $this->get_title( $post->post_title );
		if ( '' === $title ) {
			return;
		}
		$this->card_data['title'] = $title;
		/**
		 * 概要取得
		 */
		$this->card_data['dscr'] = $this->get_description(
			\ystandard\Content::get_custom_excerpt( ' …', 0, $post_id )
		);
		/**
		 * 属性
		 */
		$this->card_data['attr'] = $this->get_link_attr( get_permalink( $post_id ), $this->get_param( 'target' ) );
		/**
		 * ボタンテキスト
		 */
		$this->card_data['btn_text'] = $this->get_param( 'btn_text' );
		/**
		 * 準備OK
		 */
		$this->card_data['blog_card'] = true;
	}

	/**
	 * アイキャッチ画像取得
	 *
	 * @param int $post_id Post ID.
	 *
	 * @return string
	 */
	private function get_post_thumbnail( $post_id ) {
		if ( has_post_thumbnail( $post_id ) ) {
			/**
			 * 画像サイズ
			 */
			$thumb_size = apply_filters(
				'ys_blog_card_thumbnail_size',
				$this->get_param( 'thumbnail_size' )
			);
			/**
			 * 画像取得
			 */
			$thumb = get_the_post_thumbnail(
				$post_id,
				$thumb_size,
				array( 'class' => 'ys-blog-card__img' )
			);

			return apply_filters( 'ys_blog_card_thumbnail', $thumb, $post_id );
		}

		return '';
	}

	/**
	 * 外部サイトデータ取得
	 *
	 * @param string $url URL.
	 */
	private function get_site_data( $url ) {
		/**
		 * サイトページ取得
		 */
		$response = wp_remote_get( $url );
		if ( ! is_array( $response ) || 200 !== $response['response']['code'] ) {
			return;
		}
		$body = $response['body'];
		/**
		 * タイトル取得
		 */
		$title = $this->get_title( $this->get_site_title( $body ) );
		if ( '' === $title ) {
			return;
		}
		$this->card_data['title'] = $title;
		/**
		 * URL
		 */
		$this->card_data['url'] = $url;
		/**
		 * 概要取得
		 */
		$this->card_data['dscr'] = $this->get_description( $this->get_site_description( $body ) );
		/**
		 * 画像取得
		 */
		$this->card_data['thumbnail'] = $this->get_site_thumbnail( $body, $url );
		/**
		 * 属性
		 */
		$this->card_data['attr'] = $this->get_link_attr( $url, $this->get_param( 'target' ) );
		/**
		 * ボタンテキスト
		 */
		$this->card_data['btn_text'] = $this->get_param( 'btn_ex_text' );
		/**
		 * ドメイン
		 */
		$domain = wp_parse_url( $url, PHP_URL_HOST );
		if ( false !== $domain ) {
			$this->card_data['domain'] = $domain;
		}
		/**
		 * 準備OK
		 */
		$this->card_data['blog_card'] = true;
	}

	/**
	 * サイトタイトルを取得
	 *
	 * @param string $body Body.
	 *
	 * @return string
	 */
	private function get_site_title( $body ) {
		if ( 1 === preg_match( '/<title>(.+?)<\/title>/is', $body, $matches ) ) {
			return $matches[1];
		}
		if ( 1 === preg_match( '/<meta.+?property=["\']og:title["\'][^\/>]*?content=["\']([^"\']+?)["\'].*?\/?>/is', $body, $matches ) ) {
			return $matches[1];
		}

		return '';
	}

	/**
	 * サイトdescriptionを取得
	 *
	 * @param string $body Body.
	 *
	 * @return string
	 */
	private function get_site_description( $body ) {
		if ( 1 === preg_match( '/<meta.+?name=["\']description["\'][^\/>]*?content=["\']([^"\']+?)["\'].*?\/?>/is', $body, $matches ) ) {
			return $matches[1];
		}
		if ( 1 === preg_match( '/<meta.+?property=["\']og:description["\'][^\/>]*?content=["\']([^"\']+?)["\'].*?\/?>/is', $body, $matches ) ) {
			return $matches[1];
		}

		return '';
	}

	/**
	 * サイト画像を取得
	 *
	 * @param string $body Body.
	 * @param string $url  URL.
	 *
	 * @return string
	 */
	private function get_site_thumbnail( $body, $url ) {
		/**
		 * 画像指定があれば優先
		 */
		if ( '' !== $this->get_param( 'thumbnail' ) ) {
			return $this->get_param( 'thumbnail' );
		}
		/**
		 * 外部サイト画像を表示しない設定確認
		 */
		if ( ! $this->get_param( 'show_ex_img' ) && ! $this->is_my_site( $url ) ) {
			return '';
		}

		if ( 1 === preg_match( '/<meta.+?property=["\']og:image["\'][^\/>]*?content=["\']([^"\']+?)["\'].*?\/?>/is', $body, $matches ) ) {
			$image = $matches[1];
			if ( wp_http_validate_url( $image ) ) {
				/**
				 * 画像タグ作成 width,heightは仮で指定する
				 */
				return $this->get_image_tag( $image, 250, 250, '' );
			}
		}

		return '';
	}

	/**
	 * タイトルの取得
	 *
	 * @param string $title タイトル.
	 *
	 * @return string
	 */
	private function get_title( $title ) {
		if ( $this->get_param( 'title' ) ) {
			return $this->get_param( 'title' );
		}

		return $title;
	}

	/**
	 * 説明の取得
	 *
	 * @param string $description 説明.
	 *
	 * @return string
	 */
	private function get_description( $description ) {
		if ( $this->get_param( 'description' ) ) {
			$description = $this->get_param( 'description' );
		}
		if ( $this->get_param( 'dscr' ) ) {
			$description = $this->get_param( 'dscr' );
		}
		if ( 'false' === $description ) {
			return '';
		}
		/**
		 * 長さ調節
		 */
		$length = ys_excerpt_length();
		$sep    = '…';
		if ( mb_strlen( $description ) > $length ) {
			$description = mb_substr( $description, 0, $length - mb_strlen( $sep ) ) . $sep;
		}

		return $description;
	}

	/**
	 * 画像タグを作成
	 *
	 * @param string $url    url.
	 * @param int    $width  width.
	 * @param int    $height height.
	 * @param string $alt    alt.
	 * @param string $attr   attr.
	 *
	 * @return string
	 */
	private function get_image_tag( $url, $width, $height, $alt, $attr = '' ) {
		return sprintf(
			'<img src="%s" width="%s" height="%s" alt="%s" %s>',
			$url,
			$width,
			$height,
			$alt,
			$attr
		);
	}

	/**
	 * 展開できなかった時用にリンクタグを作成
	 *
	 * @param string $url URL.
	 *
	 * @return string
	 */
	private function create_a_tag( $url ) {
		$url = sprintf(
			'<a href="%s"%s>%s</a>',
			$url,
			$this->get_link_attr( $url, $this->get_param( 'target' ) ),
			$url
		);
		if ( has_filter( 'the_content', 'wpautop' ) ) {
			$url = wpautop( $url );
		} else {
			$url = '<br>' . $url;
		}

		return $url;
	}

	/**
	 * リンクにつける属性を取得
	 *
	 * @param string $url    URL.
	 * @param string $target target属性.
	 *
	 * @return string
	 */
	private function get_link_attr( $url, $target = '' ) {
		/**
		 * 外部サイトかつtarget指定がない場合、_blankつける
		 */
		if ( ! $this->is_my_site( $url ) && '' === $target ) {
			$target = '_blank';
		}
		if ( '' !== $target ) {
			$attr = sprintf( ' target="%s"', $target );
			if ( '_blank' === $this->get_param( 'target' ) ) {
				$attr .= ' rel="noopener noreferrer"';
			}

			return $attr;
		}

		return '';
	}

	/**
	 * 自サイトかチェック
	 *
	 * @param string $url URL.
	 *
	 * @return bool
	 */
	private function is_my_site( $url ) {
		if ( false === strpos( $url, home_url() ) ) {
			return false;
		}

		return true;
	}
}
