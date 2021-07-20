<?php
/**
 * テーマのお知らせ
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

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
	 * REST API URL
	 */
	const REST_POST_URL = 'http://wp-ystandard.com/wp-json/wp/v2/posts?per_page=5&categories=1';

	/**
	 * お知らせカテゴリー情報取得
	 */
	const REST_CATEGORY_URL = 'https://wp-ystandard.com/wp-json/wp/v2/categories?parent=1';


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
	 * 必要PHPバージョンの取得
	 *
	 * @return array|false|string
	 */
	public static function get_requires_php() {
		$theme = wp_get_theme( get_template() );

		return $theme->get( 'RequiresPHP' );
	}

	/**
	 * 必要WordPressバージョンの取得
	 *
	 * @return array|false|string
	 */
	public static function get_requires_wp() {
		$theme = wp_get_theme( get_template() );

		return $theme->get( 'RequiresWP' );
	}

	/**
	 * 動作要件のチェック-PHP
	 *
	 * @return bool
	 */
	private function check_php_version() {

		if ( version_compare( phpversion(), self::get_requires_php(), '<=' ) ) {
			return false;
		}

		return true;
	}

	/**
	 * 動作要件のチェック-WP
	 *
	 * @return bool
	 */
	private function check_wordpress_version() {

		if ( version_compare( get_bloginfo( 'version' ), self::get_requires_wp(), '<=' ) ) {
			return false;
		}

		return true;
	}

	/**
	 * ダッシュボード おしらせ表示
	 */
	public function dashboard_info() {
		$feed = $this->get_feed();
		if ( ! $this->check_php_version() ) :
			?>
			<div class="ys-dashboard-section">
				<div class="ys-dashboard__notice is-warning">
					<?php echo Icon::get_icon( 'alert-triangle' ); ?>
					<div class="ys-dashboard__notice-text">
						<p>
							サイトがyStandardの動作要件以下のPHPバージョン（<?php echo phpversion(); ?>）で動作しています。 <?php echo self::get_requires_php(); ?>以上に更新してください。
						</p>
					</div>
				</div>
			</div>
		<?php endif; ?>
		<?php if ( ! $this->check_wordpress_version() ) : ?>
			<div class="ys-dashboard-section">
				<div class="ys-dashboard__notice is-warning">
					<?php echo Icon::get_icon( 'alert-triangle' ); ?>
					<div class="ys-dashboard__notice-text">
						<p>
							サイトがyStandardの動作要件以下のWordPressバージョン（<?php bloginfo( 'version' ); ?>）で動作しています。 <?php echo self::get_requires_wp(); ?>以上に更新してください。
						</p>
					</div>
				</div>
			</div>
		<?php endif; ?>
		<div class="ys-dashboard-section">
			<div>
				<p style="margin: 0;"><strong><span class="orbitron">yStandard</span>バージョン情報</strong></p>
				<div class="ys-dashboard-version__list">
					<p>本体 : <?php echo Utility::get_ystandard_version(); ?></p>
					<?php if ( get_template() !== get_stylesheet() ) : ?>
						<p>子テーマ : <?php echo Utility::get_theme_version(); ?></p>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<div class="ys-dashboard-section">
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
		</div>
		<div class="ys-dashboard-section">
			<div class="ys-dashboard__system-info">
				<div class="ys-dashboard__system-info-title">
					<span>システム情報（サポート用）</span>
					<button class="ys-dashboard__system-info-open"><?php echo Icon::get_icon( 'chevrons-down' ); ?></button>
				</div>
				<?php
				$system_info   = [];
				$system_info[] = 'WordPressバージョン: ' . get_bloginfo( 'version' );
				$system_info[] = 'PHPバージョン: ' . phpversion();
				$system_info[] = 'yStandardバージョン: ' . Utility::get_ystandard_version();
				$system_info   = apply_filters( 'ys_system_info', $system_info );
				$system_info   = implode( PHP_EOL, $system_info );
				?>
				<div class="ys-dashboard__system-info-text">
					<textarea cols="30" rows="5" style="min-width: 100%;" readonly><?php echo esc_textarea( $system_info ); ?></textarea>
					<button class="ys-dashboard__system-info-copy button action">システム情報をコピー</button>
				</div>
				<script>
					jQuery( document ).ready( function ( $ ) {
						$( '.ys-dashboard__system-info-open' ).on( 'click', function () {
							$( '.ys-dashboard__system-info-text' ).slideToggle( 300 );
						} );
						$( '.ys-dashboard__system-info-copy' ).on( 'click', function () {
							$( '.ys-dashboard__system-info-text textarea' ).select();
							document.execCommand( 'copy' );
							alert( 'システム情報をコピーしました' );
						} );
					} );
				</script>
			</div>

		</div>
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

		/**
		 * RSSで取得
		 */
		$data = [];// $this->get_feed_from_rss();

		if ( empty( $data ) ) {
			$data = $this->get_feed_from_rest();
		}
		if ( ! empty( $data ) ) {
			set_transient( self::RSS_CACHE_KEY, $data, ( 60 * 60 * 24 ) );
		}

		return $data;
	}


	/**
	 * RSSから情報取得
	 *
	 * @return array
	 */
	private function get_feed_from_rss() {
		$data      = [];
		$rss       = fetch_feed( self::RSS_URL );
		$maxitems  = $rss->get_item_quantity( 5 );
		$rss_items = $rss->get_items( 0, $maxitems );

		foreach ( $rss_items as $item ) {
			$data[] = [
				'date'  => $item->get_date( 'Y.m.d' ),
				'url'   => $item->get_permalink(),
				'title' => wp_encode_emoji( $item->get_title() ),
			];
		}

		return $data;
	}

	/**
	 * REST APIから取得
	 *
	 * @return array
	 */
	private function get_feed_from_rest() {

		$child    = '';
		$category = wp_remote_get( self::REST_CATEGORY_URL );
		if ( ! is_wp_error( $category ) ) {
			$category = json_decode( $category['body'] );
			if ( is_array( $category ) ) {
				foreach ( $category as $cat ) {
					$child .= ',' . $cat->id;
				}
			}
		}
		$data     = [];
		$response = wp_remote_get( self::REST_POST_URL . $child );
		if ( ! is_wp_error( $response ) ) {
			$body = json_decode( $response['body'] );
			if ( is_array( $body ) ) {
				foreach ( $body as $item ) {
					$data[] = [
						'date'  => date_i18n( 'Y.m.d', strtotime( $item->date ) ),
						'url'   => $item->link,
						'title' => wp_encode_emoji( $item->title->rendered ),
					];
				}
			}
		}

		return $data;
	}
}

new Theme_Info();
