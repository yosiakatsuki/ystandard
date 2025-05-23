<?php
/**
 * 管理画面
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

use ystandard\helper\Style_Sheet;

defined( 'ABSPATH' ) || die();

/**
 * Class Admin
 *
 * @package ystandard
 */
class Admin {

	/**
	 * Admin constructor.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_admin_bar_style' ] );
		if ( ! is_admin() ) {
			return;
		}
		add_action( 'after_setup_theme', [ $this, 'update_check' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_styles' ] );
		add_action( 'admin_init', [ $this, 'enqueue_visual_editor_styles' ] );
		add_action( 'tiny_mce_before_init', [ $this, 'tiny_mce_before_init' ] );
		Notice::set_notice( [ $this, 'widget_manual' ] );
		Notice::set_notice( [ $this, 'menu_manual' ] );
	}

	/**
	 * メニューページのマニュアルリンク
	 */
	public function menu_manual() {
		global $pagenow;
		$manual = self::manual_link( 'manual_category/menu' );
		if ( 'nav-menus.php' === $pagenow && ! empty( $manual ) ) {
			Notice::manual( $manual );
		}
	}

	/**
	 * ウィジェットページのマニュアルリンク
	 */
	public function widget_manual() {
		global $pagenow;
		$manual = self::manual_link( 'manual/widget-area' );
		if ( 'widgets.php' === $pagenow && ! empty( $manual ) ) {
			Notice::manual( $manual );
		}
	}

	/**
	 * 管理画面の投稿タイプ判断用
	 *
	 * @param string $type post type.
	 *
	 * @return boolean
	 */
	public static function is_post_type_on_admin( $type ) {
		global $post_type;

		return ( $type === $post_type );
	}


	/**
	 * アドミンバー調整用CSS
	 */
	public function enqueue_admin_bar_style() {
		if ( ! is_admin_bar_showing() ) {
			return;
		}
		wp_enqueue_style(
			'ys-admin-bar',
			get_template_directory_uri() . '/css/admin-bar.css',
			[ 'admin-bar' ],
			Utility::get_ystandard_version()
		);
	}

	/**
	 * 管理画面-JavaScriptの読み込み
	 *
	 * @param string $hook_suffix suffix.
	 *
	 * @return void
	 */
	public function admin_enqueue_scripts( $hook_suffix ) {
		/**
		 * メディアアップローダ
		 */
		wp_enqueue_media();
		wp_enqueue_script(
			'ys-admin-scripts',
			get_template_directory_uri() . '/js/admin/admin.js',
			[ 'jquery' ],
			filemtime( get_template_directory() . '/js/admin/admin.js' ),
			true
		);
		wp_enqueue_script(
			'ys-custom-uploader',
			get_template_directory_uri() . '/js/admin/custom-uploader.js',
			[ 'jquery' ],
			filemtime( get_template_directory() . '/js/admin/custom-uploader.js' ),
			true
		);
	}

	/**
	 * 管理画面-CSSの読み込み
	 *
	 * @param string $hook_suffix suffix.
	 *
	 * @return void
	 */
	public function admin_enqueue_styles( $hook_suffix ) {
		wp_enqueue_style( 'wp-block-library' );
		wp_enqueue_style(
			'ys-google-font',
			'https://fonts.googleapis.com/css?family=Orbitron',
			[],
			Utility::get_ystandard_version()
		);
		// 必要なページのみ読み込み.
		if ( $this->is_enqueue_admin_css( $hook_suffix ) ) {
			wp_enqueue_style(
				'ys-admin',
				get_template_directory_uri() . '/css/admin.css',
				[],
				Utility::get_ystandard_version()
			);
		}
	}

	/**
	 * 管理画面のCSSを読み込むかどうか.
	 *
	 * @param string $hook_suffix hook suffix.
	 *
	 * @return bool
	 */
	private function is_enqueue_admin_css( $hook_suffix ) {
		$pages = [
			'ystandard-start-page',
			'ystandard_page',
			'index', // ダッシュボード.
			'nav-menus', // メニュー.
		];
		foreach ( $pages as $page ) {
			if ( strpos( $hook_suffix, $page ) !== false ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * ビジュアルエディタ用CSS追加
	 */
	public function enqueue_visual_editor_styles() {
		/**
		 * ビジュアルエディターへのCSSセット
		 */
		add_editor_style( 'css/tiny-mce-style.css' );
		add_editor_style( 'style.css' );
	}

	/**
	 * TinyMCEに追加CSSを適用させる
	 *
	 * @param array $settings TinyMCE設定.
	 *
	 * @return array;
	 */
	public function tiny_mce_before_init( $settings ) {
		$settings['content_style'] = str_replace( '"', '\'', Style_Sheet::minify( wp_get_custom_css() ) );

		return $settings;

	}

	/**
	 * アップデートのチェック
	 */
	public function update_check() {
		require_once get_template_directory() . '/library/plugin-update-checker/plugin-update-checker.php';

		$dir = apply_filters( 'ys_update_check_dir', 'v4' );
		$url = "https://wp-ystandard.com/download/ystandard/{$dir}/ystandard-info.json";

		\Puc_v4_Factory::buildUpdateChecker(
			apply_filters( 'ys_update_check_url', $url ),
			get_template_directory() . '/functions.php', // Full path to the main plugin file or functions.php.
			'ystandard'
		);
	}

	/**
	 * マニュアルリンク作成
	 *
	 * @param string $url    URL.
	 * @param string $text   Text.
	 * @param bool   $inline Inline.
	 * @param string $icon   Icon Name.
	 *
	 * @return string
	 */
	public static function manual_link( $url, $text = '', $inline = false, $icon = 'book' ) {

		$url  = apply_filters( 'ys_manual_link_url', $url );
		$text = apply_filters( 'ys_manual_link_text', $text, $url );

		if ( empty( $url ) ) {
			return '';
		}

		if ( '' === $text ) {
			$text = 'マニュアルを見る';
		}
		$icon = Icon::get_icon( $icon );

		if ( false === strpos( $url, 'https://' ) ) {
			$url = "https://wp-ystandard.com/{$url}/";
		}

		$inline_class = $inline ? 'is-inline' : '';

		$link = "<a class=\"ys-manual-link {$inline_class}\" href=\"{$url}\" target=\"_blank\">{$icon}{$text}</a>";
		if ( ! $inline ) {
			$link = sprintf( '<div class="ys-manual-link__wrap">%s</div>', $link );
		}

		return $link;
	}

	/**
	 * Nonceチェック
	 *
	 * @param string $name   Name.
	 * @param string $action Action.
	 *
	 * @return bool|int
	 */
	public static function verify_nonce( $name, $action ) {
		// nonceがセットされているかどうか確認.
		if ( ! isset( $_POST[ $name ] ) ) {
			return false;
		}

		return wp_verify_nonce( $_POST[ $name ], $action );
	}

	/**
	 * カスタムアップローダー出力
	 *
	 * @param string $name フォーム名.
	 * @param string $url  画像URL.
	 */
	public static function custom_uploader_control( $name, $url ) {
		?>
		<div class="ys-custom-uploader">
			<div class="ys-custom-uploader__preview">
				画像が選択されてません。
			</div>
			<input
				type="hidden"
				id="<?php echo esc_attr( $name ); ?>"
				name="<?php echo esc_attr( $name ); ?>"
				class="ys-custom-uploader__hidden"
				value="<?php echo esc_url_raw( $url ); ?>"
			/>
			<button class="button ys-custom-uploader__select" type="button">画像をアップロード</button>
			<button class="button ys-custom-uploader__clear" type="button">画像を削除</button>
		</div>
		<?php
	}

	/**
	 * 非推奨メッセージを表示する
	 *
	 * @param string $func    関数.
	 * @param string $since   いつから.
	 * @param string $comment コメント.
	 */
	public static function deprecated_comment( $func, $since, $comment = '' ) {
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}
		if ( ! defined( WP_DEBUG ) ) {
			return;
		}
		if ( false === WP_DEBUG ) {
			return;
		}
		$message = sprintf(
			'<span style="color:red"><code>%s</code>は%sで非推奨になった関数です。</span><br>' . PHP_EOL,
			$func,
			$since
		);
		if ( $comment ) {
			$message .= '<br><span style="color:#999">' . $comment . '</span><br>' . PHP_EOL;
		}
		echo $message;
	}
}

new Admin();
