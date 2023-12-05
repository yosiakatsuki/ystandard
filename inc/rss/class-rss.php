<?php
/**
 * RSS
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

/**
 * Class RSS
 *
 * @package ystandard
 */
class RSS {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'the_excerpt_rss', [ $this, 'add_rss_thumbnail' ] );
		add_filter( 'the_content_feed', [ $this, 'add_rss_thumbnail' ] );
	}

	/**
	 * RSSフィードにアイキャッチ画像を表示
	 *
	 * @param string $content content.
	 *
	 * @return string
	 * @global \WP_Post $post post.
	 */
	public function add_rss_thumbnail( $content ) {
		global $post;
		if ( has_post_thumbnail( $post ) ) {
			$content = get_the_post_thumbnail( $post ) . $content;
		}

		return $content;
	}
}

new RSS();
