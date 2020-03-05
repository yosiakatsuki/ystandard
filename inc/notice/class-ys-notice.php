<?php
/**
 * 通知 クラス
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * Class YS_Notice
 */
class YS_Notice {

	/**
	 * YS_Notice constructor.
	 */
	public function __construct() {
		add_action( 'admin_notices', array( $this, 'amp_notice' ) );
	}

	/**
	 * 独自AMP非推奨メッセージ
	 */
	public function amp_notice() {
		if ( ! ys_get_option_by_bool( 'ys_amp_enable', false ) ) {
			return;
		}
		?>
		<div class="notice notice-error is-dismissible">
			<p>yStandard 3.14.0から独自のAMP機能は非推奨になりました。AMPページ生成には<a href="https://ja.wordpress.org/plugins/amp/" target="_blank" rel="noopener follow">「AMP」プラグイン</a>を利用してください。</p>
			<p>※このメッセージはカスタマイザーの「[ys]AMP設定」→「AMP有効化設定」→「※非推奨※」AMP機能を有効化」設定がONになっている為表示されています。AMPプラグイン導入後、上記設定をOFFにしてください。</p>
		</div>
		<?php
	}
}

new YS_Notice();
