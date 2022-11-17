<?php
/**
 * Feed
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

/**
 * Feed
 */
class Feed {

	/**
	 * パネル名
	 *
	 * @var string
	 */
	const PANEL_NAME = 'ys_feed';

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'customize_register', [ $this, 'customize_register' ] );
		add_action( 'feed_links_show_comments_feed', [ $this, 'comments_feed' ] );
	}

	/**
	 * コメントフィードの有効・無効
	 *
	 * @param bool $flag Whether to display the comments feed link. Default true.
	 * @return bool
	 */
	public function comments_feed( $flag ) {

		$flag = Option::get_option_by_bool( 'ys_feed_disable_comment_feed', $flag );

		return $flag;
	}

	/**
	 * 設定追加
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 */
	public function customize_register( $wp_customize ) {
		$customizer = new Customize_Control( $wp_customize );
		$customizer->add_section(
			[
				'section'     => self::PANEL_NAME,
				'title'       => '[ys]RSSフィード',
				'description' => 'RSSフィードの設定' . Admin::manual_link( 'manual/rss-feed' ),
			]
		);
		$customizer->add_section_label( 'コメントフィード' );
		$customizer->add_checkbox(
			[
				'id'        => 'ys_feed_disable_comment_feed',
				'label'     => 'コメントフィードを出力する',
				'default'   => 1,
				'transport' => 'postMessage',
			]
		);
	}
}

new Feed();
