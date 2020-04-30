<?php
/**
 * Upgrade
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * Class Ys_Upgrade
 */
class Ys_Upgrade {

	/**
	 * Nonce アクション.
	 */
	const ACTION = 'ys-upgrade';
	/**
	 * Nonce 名.
	 */
	const NONCE = 'ys-upgrade-nonce';

	/**
	 * Ys_Upgrade constructor.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
		add_action( 'admin_notices', array( $this, 'upgrade_notices' ) );
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
	}

	/**
	 * アップグレード通知
	 */
	public function upgrade_notices() {
		if ( '1' === get_option( 'ys_disable_v4_upgrade_info', '0' ) ) {
			return;
		}
		if ( '0' === get_option( 'ys_v4_upgrade', '0' ) ) {
			return;
		}
		global $pagenow, $hook_suffix;
		if ( 'update-core.php' === $pagenow ) {
			return;
		}
		if ( 'admin.php' === $pagenow && 'ystandard_page_ys_settings_v4_upgrade' === $hook_suffix ) {
			return;
		}
		?>
		<div class="notice notice-info">
			<p>yStandard v4のアップグレードが有効になりました。 🎉</p>
			<p>ダッシュボード → 「<a href="<?php echo admin_url( 'update-core.php' ); ?>">更新</a>」ページからアップグレードできます。</p>
		</div>
		<?php
	}

	/**
	 * 通知
	 */
	public function admin_notices() {
		if ( '1' === get_option( 'ys_disable_v4_upgrade_info', '0' ) ) {
			return;
		}
		if ( '1' === get_option( 'ys_v4_upgrade', '0' ) ) {
			return;
		}
		global $pagenow, $hook_suffix;
		if ( 'admin.php' === $pagenow && 'ystandard_page_ys_settings_v4_upgrade' === $hook_suffix ) {
			return;
		}
		?>
		<div class="notice notice-info">
			<p>yStandard v4がリリースされました 🎉</p>
			<p>v4へのアップグレード方法については <a href="<?php echo admin_url( 'admin.php?page=ys_settings_v4_upgrade' ); ?>">こちら</a>をご覧ください。</p>
		</div>
		<?php
	}

	/**
	 * メニューページの追加
	 */
	public function add_menu() {
		add_submenu_page(
			'ys_settings_start',
			'v4アップグレード',
			'v4アップグレード',
			'manage_options',
			'ys_settings_v4_upgrade',
			array( $this, 'upgrade' )
		);
	}

	/**
	 * アップグレードページ
	 */
	public function upgrade() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		add_filter( 'ys_use_ystdb_card', '__return_false' );
		$this->update_option();
		?>
		<div class="wrap ystd-settings">
			<h2>v4へのアップグレード</h2>
			<?php if ( '1' === get_option( 'ys_v4_upgrade', '0' ) ) : ?>
				<div class="notice notice-info">
					<p>yStandard v4のアップグレードが有効になりました。 🎉</p>
					<p>ダッシュボード → 「<a href="<?php echo admin_url( 'update-core.php' ); ?>">更新</a>」ページからアップグレードできます。</p>
				</div>
			<?php else : ?>
				<div class="notice notice-info">
					<p>yStandard v4がリリースされました 🎉</p>
					<p>v4ではこれまでのyStandardに比べて強力に・でもシンプルに機能追加やデザインの調整をしています。</p>
					<p>ただ、<strong>廃止された機能や内容に変更がある機能も多く存在します。</strong></p>
					<p>このページではv4へのアップグレードに関する注意点と、必要バージョンのチェック、設定移行ツール、そして最後に<strong>v4へのアップグレード有効化の設定</strong>についてご案内します。</p>
				</div>
			<?php endif; ?>

			<div class="ystd-settings__section">
				<h2>PHPバージョン・WordPressバージョンのチェック</h2>
				<p>yStandard v4は PHP:7.3 以上、WordPress:5.4 以上が必要です。</p>
				<table class="form-table">
					<tbody>
					<?php $check = $this->upgrade_check(); ?>
					</tbody>
				</table>
			</div>
			<div class="ystd-settings__section">
				<h2>v4で追加・変更・削除される機能、設定移行ツールについて</h2>
				<h3>追加・変更・削除される機能</h3>
				<p>追加・変更・削除される機能についてはこちらのページをご覧ください▼</p>
				<?php echo do_shortcode( '[ys_blog_card url="https://wp-ystandard.com/ystandard-v4-0-0/"]' ); ?>
				<h3>設定移行ツール</h3>
				<?php echo do_shortcode( '[ys_blog_card url="https://wp-ystandard.com/ystandard-migration-v3-v4/"]' ); ?>
			</div>
			<div class="ystd-settings__section">
				<h2>v4へのアップグレード設定</h2>
				<form method="post" action="" id="upgrade">
					<?php wp_nonce_field( self::ACTION, self::NONCE ); ?>
					<table class="form-table">
						<tbody>
						<tr>
							<th>v4へのアップグレード</th>
							<td>
								<?php
								$readonly = $check ? '' : ' disabled ';
								?>
								<label><input type="checkbox" id="ys_v4_upgrade" name="ys_v4_upgrade" value="1" <?php checked( get_option( 'ys_v4_upgrade', '0' ), '1', true ); ?><?php echo $readonly; ?> />v4へのアップグレードを有効化する</label>
								<?php if ( ! $check ) : ?>
									<div style="color:red;font-size: 12px;margin-top: .5em;">v4の動作要件に達していないためアップグレードできません。<br>「PHPバージョン・WordPressバージョンのチェック」をご確認ください。</div>
								<?php endif; ?>
							</td>
						</tr>
						<tr>
							<th>アップグレード通知の停止</th>
							<td>
								<label><input type="checkbox" id="ys_disable_v4_upgrade_info" name="ys_disable_v4_upgrade_info" value="1" <?php checked( get_option( 'ys_disable_v4_upgrade_info', '0' ), '1', true ); ?> />アップグレード通知を停止する</label>
							</td>
						</tr>
						</tbody>
					</table>
					<?php submit_button(); ?>
				</form>
			</div>
		</div>
		<?php
	}

	/**
	 * アップグレード要件のチェック
	 *
	 * @param bool $echo Echo.
	 *
	 * @return bool
	 */
	private function upgrade_check( $echo = true ) {
		$php_ver   = phpversion();
		$php_check = version_compare( $php_ver, '7.3.0', '>=' );
		if ( $echo ) :
			?>
			<tr>
				<th>PHPバージョン</th>
				<td>
					<?php echo $php_ver; ?><?php echo $php_check ? ' ✅' : ' ❌'; ?>
				</td>
			</tr>
			<?php
		endif;
		$wp_ver   = get_bloginfo( 'version' );
		$wp_check = version_compare( $wp_ver, '5.4.0', '>=' );
		if ( $echo ) :
			?>
			<tr>
				<th>WordPressバージョン</th>
				<td>
					<?php echo $wp_ver; ?><?php echo $wp_check ? ' ✅' : ' ❌'; ?>
				</td>
			</tr>
			<?php
		endif;

		return $php_check && $wp_check;
	}

	/**
	 * 設定の保存
	 */
	private function update_option() {
		// nonceがセットされているかどうか確認.
		if ( ! isset( $_POST[ self::NONCE ] ) ) {
			return false;
		}
		if ( ! wp_verify_nonce( $_POST[ self::NONCE ], self::ACTION ) ) {
			return false;
		}
		if ( isset( $_POST['ys_v4_upgrade'] ) && '1' === $_POST['ys_v4_upgrade'] && $this->upgrade_check( false ) ) {
			update_option( 'ys_v4_upgrade', '1' );
		} else {
			delete_option( 'ys_v4_upgrade' );
		}
		if ( isset( $_POST['ys_disable_v4_upgrade_info'] ) && '1' === $_POST['ys_disable_v4_upgrade_info'] ) {
			update_option( 'ys_disable_v4_upgrade_info', '1' );
		} else {
			delete_option( 'ys_disable_v4_upgrade_info' );
		}
	}
}

new Ys_Upgrade();
