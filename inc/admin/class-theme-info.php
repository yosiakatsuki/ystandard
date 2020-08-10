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
	 * お知らせ取得のキャッシュキー
	 */
	const RSS_CACHE_KEY = 'ystandard_info';

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
			'<span><span class="orbitron">yStandard</span>のおしらせ</span>',
			[ $this, 'dashboard_info' ]
		);
	}

	/**
	 * ダッシュボード おしらせ表示
	 */
	public function dashboard_info() {
		$feed = $this->get_feed();
		?>
		<div class="ys-dashboard-version">
			<p><strong><span class="orbitron">yStandard</span>バージョン情報</strong></p>
			<div class="ys-dashboard-version__list">
				<p>本体 : <?php echo Utility::get_ystandard_version(); ?></p>
				<?php if ( get_template() !== get_stylesheet() ) : ?>
					<p>子テーマ : <?php echo Utility::get_theme_version(); ?></p>
				<?php endif; ?>
			</div>
		</div>
		<ul class="ys-dashboard-info">
			<?php if ( empty( $feed ) ) : ?>
				<li>お知らせを取得できませんでした。</li>
			<?php else : ?>
				<?php foreach ( $feed as $item ) : ?>
					<li>
						<span class="ys-dashboard-info__date"><?php echo esc_html( $item['date'] ); ?></span>
						<a class="ys-dashboard-info__link" href="<?php echo esc_url_raw( $item['url'] ); ?>">
							<?php echo esc_html( $item['title'] ); ?>
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
		// キャッシュ取得.
		$cache = get_transient( self::RSS_CACHE_KEY );
		if ( false !== $cache ) {
			if ( is_array( $cache ) && ! empty( $cache ) ) {
				if ( isset( $cache[0]['date'] ) && isset( $cache[0]['url'] ) && isset( $cache[0]['title'] ) ) {
					return $cache;
				}
			}
		}

		$rss = fetch_feed( self::RSS_URL );
		if ( is_wp_error( $rss ) ) {
			return [];
		}
		$maxitems  = $rss->get_item_quantity( 5 );
		$rss_items = $rss->get_items( 0, $maxitems );

		$data = [];
		foreach ( $rss_items as $item ) {
			$data[] = [
				'date'  => $item->get_date( 'Y.m.d' ),
				'url'   => $item->get_permalink(),
				'title' => wp_encode_emoji( $item->get_title() ),
			];
		}
		set_transient( self::RSS_CACHE_KEY, $data, ( 60 * 60 * 24 ) );

		return $data;
	}
}

new Theme_Info();
