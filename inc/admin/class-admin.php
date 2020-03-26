<?php
/**
 * 条件判断用関数群
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
	 * Admin constructor.
	 */
	public function __construct() {
		add_action( 'after_setup_theme', [ $this, 'update_check' ] );
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
	 * アップデートのチェック
	 */
	public function update_check() {
		require_once get_template_directory() . '/library/theme-update-checker/theme-update-checker.php';

		// アップデート確認.
		$theme_update_checker = new \ThemeUpdateChecker(
			'ystandard',
			apply_filters(
				'ys_update_check_url',
				"https://wp-ystandard.com/download/ystandard/v4/ystandard-info.json"
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
