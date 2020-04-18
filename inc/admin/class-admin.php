<?php
/**
 * 管理画面
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

/**
 * Class Admin
 *
 * @package ystandard
 */
class Admin {

	/**
	 * ブロックエディター用インラインCSSフック名
	 */
	const BLOCK_EDITOR_ASSETS_HOOK = 'ys_block_editor_assets_inline_css';

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
		add_action( 'after_setup_theme', [ $this, 'enqueue_block_css' ] );
		add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_block_editor_assets' ] );
		add_action( 'admin_init', [ $this, 'enqueue_visual_editor_styles' ] );
		add_action( 'tiny_mce_before_init', [ $this, 'tiny_mce_before_init' ] );
		$this->tgmpa_load();
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
			[ 'jquery', 'jquery-core' ],
			Utility::get_ystandard_version(),
			true
		);
		wp_enqueue_script(
			'ys-custom-uploader',
			get_template_directory_uri() . '/js/admin/custom-uploader.js',
			[ 'jquery', 'jquery-core' ],
			Utility::get_ystandard_version(),
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
			'https://fonts.googleapis.com/css?family=Orbitron'
		);
		wp_enqueue_style(
			'ys-admin',
			get_template_directory_uri() . '/css/admin.css',
			[],
			Utility::get_ystandard_version()
		);
	}

	/**
	 * Enqueue block editor style
	 */
	public function enqueue_block_css() {
		add_theme_support( 'editor-styles' );
		add_editor_style( 'css/block-editor.css' );
		add_editor_style( 'style.css' );
	}

	/**
	 * ブロックエディタのスタイル追加
	 */
	public function enqueue_block_editor_assets() {
		wp_enqueue_style(
			'ys-block-editor-assets',
			get_template_directory_uri() . '/css/block-editor-assets.css'
		);
		wp_add_inline_style(
			'ys-block-editor-assets',
			apply_filters( self::BLOCK_EDITOR_ASSETS_HOOK, '' )
		);
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
		$settings['content_style'] = str_replace( '"', '\'', Enqueue_Styles::minify( wp_get_custom_css() ) );

		return $settings;

	}

	/**
	 * アップデートのチェック
	 */
	public function update_check() {
		require_once get_template_directory() . '/library/theme-update-checker/theme-update-checker.php';

		$dir = apply_filters( 'ys_update_check_dir', 'v4' );
		$url = "https://wp-ystandard.com/download/ystandard/${dir}/ystandard-info.json";

		// アップデート確認.
		$theme_update_checker = new \ThemeUpdateChecker(
			'ystandard',
			apply_filters(
				'ys_update_check_url',
				$url
			)
		);
	}

	/**
	 * 管理画面通知
	 *
	 * @param string $content notice content.
	 * @param string $type    type.
	 */
	private function notice( $content, $type = 'error' ) {
		echo "<div class=\"notice notice-${type} is-dismissible\">${content}</div>";
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
	 * TGM Plugin Activation
	 */
	private function tgmpa_load() {
		require_once get_template_directory() . '/library/TGM-Plugin-Activation/class-tgm-plugin-activation.php';
		add_action( 'tgmpa_register', [ $this, 'tgmpa_register' ] );
	}

	/**
	 * TGM Plugin Activation 実行
	 */
	public function tgmpa_register() {
		$plugins = [
			[
				'name'   => 'yStandard Blocks',
				'slug'   => 'ystandard-blocks',
				'source' => 'https://wp-ystandard.com/download/ystandard/plugin/ystandard-blocks/ystandard-blocks.zip',
			],
			[
				'name' => 'WP Multibyte Patch',
				'slug' => 'wp-multibyte-patch',
			],
			[
				'name' => 'Lazy Load - Optimize Images',
				'slug' => 'rocket-lazy-load',
			],
			[
				'name' => 'EWWW Image Optimizer',
				'slug' => 'ewww-image-optimizer',
			],
		];
		$config  = [
			'id'           => 'tgmpa',
			'default_path' => '',
			'menu'         => 'tgmpa-install-plugins',
			'parent_slug'  => 'themes.php',
			'capability'   => 'edit_theme_options',
			'has_notices'  => true,
			'dismissable'  => true,
			'dismiss_msg'  => '',
			'is_automatic' => false,
			'message'      => '',
		];

		tgmpa( $plugins, $config );
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
