<?php
/**
 * ブログカード クラス
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

/**
 * Class Blog_Card
 *
 * @package ystandard
 */
class Blog_Card {

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
	const SHORTCODE_PARAM = [
		'url'            => '',
		'title'          => '',
		'dscr'           => '',
		'thumbnail'      => '',
		'thumbnail_size' => 'post-thumbnail',
		'target'         => '',
		'show_image'     => true,
		'domain'         => '',
		'cache'          => '', // 空白 or disable.
	];

	/**
	 * パラメーター
	 *
	 * @var array
	 */
	private $params = [];

	/**
	 * YS_Blog_Card constructor.
	 */
	public function __construct() {

		add_action( 'after_setup_theme', [ $this, 'embed_register_handler' ] );
		add_filter( 'oembed_dataparse', [ $this, 'oembed_dataparse' ], 11, 3 );
		add_shortcode( 'ys_blog_card', [ $this, 'do_shortcode' ] );
	}

	/**
	 * ブロクカードの展開処理登録
	 */
	public function embed_register_handler() {
		wp_embed_register_handler(
			'ys_blog_card',
			$this->get_register_pattern(),
			[ $this, 'blog_card_handler' ]
		);
	}

	/**
	 * ブログカード化する条件パターンを取得
	 */
	private function get_register_pattern() {
		/**
		 * Embed 変換されるURLパターンを取得
		 */
		$oembed    = _wp_oembed_get_object();
		$providers = array_keys( $oembed->providers );
		/**
		 * デリミタの削除
		 */
		foreach ( $providers as $key => $value ) {
			$providers[ $key ] = preg_replace( '/^#(.+)#.*$/', '$1', $value );
		}

		return '#^(?!.*(' . implode( '|', $providers ) . '))https?://.*$#i';
	}

	/**
	 * Embedの変換ハンドラ
	 *
	 * @param [type] $matches matches.
	 * @param [type] $attr attr.
	 * @param [type] $url url.
	 * @param [type] $rawattr rawattr.
	 *
	 * @return string ブログカード用ショートコード
	 */
	public function blog_card_handler( $matches, $attr, $url, $rawattr ) {
		$blog_card = '[ys_blog_card url="' . $url . '"]';
		/**
		 * ビジュアルエディタ用処理
		 */
		if ( is_admin() ) {
			/**
			 * ビジュアルエディタの中でショートコードを展開する
			 */
			$blog_card = $this->get_admin_blog_card( $url );
		}

		return $blog_card;
	}

	/**
	 * エディタ内で展開するブログカードHTMLを作成する
	 *
	 * @param string $url URL.
	 *
	 * @return string
	 */
	public function get_admin_blog_card( $url ) {
		/**
		 * ビジュアルエディタの中でショートコードを展開する
		 */
		add_shortcode( 'ys_blog_card', [ $this, 'do_shortcode' ] );
		$blog_card = ys_do_shortcode(
			'ys_blog_card',
			[
				'url'   => $url,
				'cache' => 'disable',
			],
			null,
			false
		);
		$blog_card = str_replace( '<a ', '<span ', $blog_card );
		$blog_card = str_replace( '</a>', '</span>', $blog_card );

		return $blog_card;
	}

	/**
	 * ショートコード実行
	 *
	 * @param array $atts Attributes.
	 *
	 * @return string
	 */
	public function do_shortcode( $atts ) {
		$this->params = shortcode_atts(
			self::SHORTCODE_PARAM,
			$atts
		);

		/**
		 * URL指定なしの場合は表示しない
		 */
		if ( empty( $this->params['url'] ) ) {
			return '';
		}
		/**
		 * [yStandard Blocks]利用中の場合、プラグイン側で処理をする
		 */
		if ( class_exists( '\ystandard_blocks\Card' ) && apply_filters( 'ys_use_ystdb_card', true ) ) {
			$ystdb_card = new \ystandard_blocks\Card();

			return $ystdb_card->render(
				[
					'url'         => $this->params['url'],
					'dscr'        => $this->params['dscr'],
					'image'       => $this->params['thumbnail'],
					'link_target' => $this->params['target'],
				]
			);
		}
		/**
		 * URLチェック
		 */
		if ( ! wp_http_validate_url( $this->params['url'] ) ) {
			return $this->get_text_link( $this->params['url'] );
		}

		/**
		 * 投稿IDの取得
		 */
		$post_id = url_to_postid( $this->params['url'] );
		/**
		 * リンク用データの作成
		 */
		if ( $post_id ) {
			$this->set_post_data( $post_id );
		} else {
			if ( ! $this->set_site_data( $this->params['url'] ) ) {
				return $this->get_text_link( $this->params['url'] );
			}
		}

		if ( ! empty( $this->params['target'] ) ) {
			$this->params['target'] = ' target="' . $this->params['target'] . '"';
		}

		ob_start();
		Template::get_template_part(
			'template-parts/parts/blog-card',
			null,
			[ 'ys_card_data' => $this->params ]
		);

		return wp_targeted_link_rel( ob_get_clean() );
	}

	/**
	 * 外部サイトデータの作成
	 *
	 * @param string $url URL.
	 *
	 * @return bool
	 */
	private function set_site_data( $url ) {
		$this->params['post_id'] = 0;
		/**
		 * 外部サイトから取得する内容をキャッシュから取得
		 */
		$cache = $this->get_cache( [ 'url' => $url ] );
		if ( 'disable' === $this->params['cache'] ) {
			$cache = false;
		}

		$site_data = [];
		if ( $cache ) {
			/**
			 * キャッシュあり
			 */
			foreach ( $cache as $key => $value ) {
				$site_data[ $key ] = $value;
			}
		} else {
			/**
			 * 情報取得
			 */
			$response = wp_remote_get( $url );
			if ( is_array( $response ) && 200 === $response['response']['code'] ) {
				$site_data['title']     = $this->get_site_title( $response['body'] );
				$site_data['dscr']      = $this->get_site_description( $response['body'] );
				$site_data['thumbnail'] = $this->get_site_thumbnail( $response['body'] );
				if ( 'disable' !== $this->params['cache'] ) {
					/**
					 * キャッシュ作成
					 */
					Cache::set_cache(
						self::CACHE_KEY,
						$site_data,
						[ 'url' => $url ],
						self::CACHE_EXPIRATION
					);
				}
			}
		}
		// タイトル.
		if ( empty( $this->params['title'] ) ) {
			$this->params['title'] = $site_data['title'];
		}
		// 概要.
		if ( ! Utility::is_false( $this->params['dscr'] ) ) {
			if ( empty( $this->params['dscr'] ) ) {
				$this->params['dscr'] = wp_trim_words(
					html_entity_decode( $site_data['dscr'] ),
					40
				);
			}
		} else {
			$this->params['dscr'] = '';
		}
		// ドメイン.
		if ( ! Utility::is_false( $this->params['domain'] ) ) {
			$this->params['domain'] = wp_parse_url( $this->params['url'], PHP_URL_HOST );
		} else {
			$this->params['domain'] = '';
		}

		// 画像.
		if ( Utility::to_bool( $this->params['show_image'] ) ) {
			$this->params['thumbnail'] = $site_data['thumbnail'];
		}

		if ( empty( $this->params['title'] ) ) {
			return false;
		}

		return true;
	}

	/**
	 * キャッシュの取得
	 *
	 * @param array $args Param.
	 *
	 * @return bool|mixed
	 */
	private function get_cache( $args ) {
		/**
		 * 編集・プレビュー系ならキャッシュをクリア
		 */
		if ( is_admin() || is_preview() || is_customize_preview() ) {
			Cache::delete_cache( self::CACHE_KEY, $args );

			return false;
		}

		return Cache::get_cache( self::CACHE_KEY, $args );
	}

	/**
	 * タイトルを取得
	 *
	 * @param string $body body.
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
	 *
	 * @return string
	 */
	private function get_site_thumbnail( $body ) {
		if ( 1 === preg_match( '/<meta.+?property=["\']og:image["\'][^\/>]*?content=["\']([^"\']+?)["\'].*?\/?>/is', $body, $matches ) ) {
			$image = $matches[1];
			if ( wp_http_validate_url( $image ) ) {
				$html = sprintf(
					'<img src="%s" alt="%s">',
					$image,
					$this->params['title']
				);

				return apply_filters( 'post_thumbnail_html', $html, 0, 0, '', '' );
			}
		}

		return '';
	}

	/**
	 * 投稿データ作成
	 *
	 * @param int $post_id Post ID.
	 */
	private function set_post_data( $post_id ) {
		$post                    = get_post( $post_id );
		$this->params['post_id'] = $post_id;
		// タイトル.
		if ( empty( $this->params['title'] ) ) {
			$this->params['title'] = $post->post_title;
		}
		// 画像.
		$this->params['thumbnail'] = $this->get_post_thumbnail( $post_id );
		// 概要.
		if ( ! Utility::is_false( $this->params['dscr'] ) ) {
			if ( empty( $this->params['dscr'] ) ) {
				$this->params['dscr'] = $this->get_post_excerpt( $post_id );
			}
		} else {
			$this->params['dscr'] = '';
		}
		// ドメイン.
		if ( ! Utility::is_false( $this->params['domain'] ) ) {
			$this->params['domain'] = wp_parse_url( $this->params['url'], PHP_URL_HOST );
		} else {
			$this->params['domain'] = '';
		}

	}

	/**
	 * 投稿概要文を取得
	 *
	 * @param int $post_id 投稿ID.
	 *
	 * @return string
	 */
	private function get_post_excerpt( $post_id ) {
		$post = get_post( $post_id );
		if ( $post->post_excerpt ) {
			return $post->post_excerpt;
		}
		$content = get_extended( $post->post_content );

		return wp_trim_words(
			html_entity_decode( Utility::get_plain_text( $content['main'] ) ),
			40
		);
	}

	/**
	 * 投稿サムネイルを取得
	 *
	 * @param int $post_id Post ID.
	 *
	 * @return string
	 */
	private function get_post_thumbnail( $post_id ) {
		if ( ! Utility::to_bool( $this->params['show_image'] ) ) {
			return '';
		}
		if ( ! has_post_thumbnail( $post_id ) ) {
			return '';
		}

		if ( $this->params['thumbnail'] ) {
			return apply_filters( 'post_thumbnail_html', $this->params['thumbnail'], $post_id, 0, '', '' );
		}
		$sizes = [ 'large', 'full', 'medium', ];
		foreach ( $sizes as $size ) {
			$image = get_the_post_thumbnail( $post_id, $size );
			if ( ! empty( $image ) ) {
				return $image;
			}
		}

		return '';
	}


	/**
	 * テキストリンクの作成
	 *
	 * @param string $url URL.
	 *
	 * @return string
	 */
	private function get_text_link( $url ) {
		$target = '';
		if ( ! empty( $this->params['target'] ) ) {
			$target = ' target="' . $this->params['target'] . '"';
		}

		return wp_targeted_link_rel( "<div class=\"ys-blog-card__text-link\"><a href=\"${url}\" ${target}>${url}</a></div>" );
	}
}

new Blog_Card();
