<?php
/**
 * テーマのお知らせ
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

/**
 * Class Theme_Info
 *
 * @package ystandard
 */
class Theme_Info {

	/**
	 * RSS
	 */
	const RSS_URL = 'https://wp-ystandard.com/category/infomation/feed/';

	/**
	 * Theme_Info constructor.
	 */
	public function __construct() {
		add_action( 'wp_dashboard_setup', [ $this, 'dashboard_info_setup' ] );
	}

	/**
	 * ダッシュボードお知らせのセット
	 */
	public function dashboard_info_setup() {
		wp_add_dashboard_widget(
			'ystandard-info',
			'<span class="orbitron">yStandard</span>のおしらせ',
			[ $this, 'dashboard_info' ]
		);
	}

	/**
	 * ダッシュボード おしらせ表示
	 */
	public function dashboard_info() {
		$feed = $this->get_feed();
		?>
		<ul class="ys-dashboard-info">
			<?php if ( empty( $feed ) ) : ?>
				<li>お知らせを取得できませんでした。</li>
			<?php else : ?>
				<?php foreach ( $feed as $item ) : ?>
					<li>
						<span class="ys-dashboard-info__date"><?php echo esc_html( $item->get_date( 'Y.m.d' ) ); ?></span>
						<a class="ys-dashboard-info__link" href="<?php echo esc_url_raw( $item->get_permalink() ); ?>">
							<?php echo esc_html( $item->get_title() ); ?>
						</a>
					</li>
				<?php endforeach; ?>
			<?php endif; ?>
		</ul>
		<?php
	}

	/**
	 * RSS取得
	 *
	 * @return array
	 */
	public function get_feed() {
		if ( ! function_exists( 'fetch_feed' ) ) {
			include_once( ABSPATH . WPINC . '/feed.php' );
		}
		$rss = fetch_feed( self::RSS_URL );
		if ( is_wp_error( $rss ) ) {
			return [];
		}
		$maxitems  = $rss->get_item_quantity( 5 );
		$rss_items = $rss->get_items( 0, $maxitems );

		return $rss_items;
	}
}

new Theme_Info();
