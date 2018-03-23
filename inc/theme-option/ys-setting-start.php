<?php
/**
 * 設定スタートページ
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
}
$current_url = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
?>
<div class="wrap ystandard-settings">
	<h2><span class="orbitron">yStandard</span> 設定ページ</h2>
	<div class="ystandard-settings__section">
		<h3><span class="orbitron">yStandard</span> の設定はテーマカスタマイザーから!</h3>
		<p>yStandardの設定はテーマカスタマイザーから行って下さい。</p>
		<p><a class="ys-btn" href="<?php echo esc_url( add_query_arg( 'return', urlencode( $current_url ), wp_customize_url() ) ); ?>">テーマカスタマイザーを開く</a></p>
		<div class="ys-smaller">
			<p>
				※ version 2.0.0 以降はテーマカスタマイザーから設定を行う方式になりました<br>
				※「<a href="https://www.amazon.co.jp/%E3%81%AF%E3%81%98%E3%82%81%E3%81%A6%E3%81%AE%E3%83%96%E3%83%AD%E3%82%B0%E3%82%92%E3%83%AF%E3%83%BC%E3%83%89%E3%83%97%E3%83%AC%E3%82%B9%E3%81%A7%E4%BD%9C%E3%82%8B%E3%81%9F%E3%82%81%E3%81%AE%E6%9C%AC-%E3%81%98%E3%81%87%E3%81%BF%E3%81%98%E3%81%87%E3%81%BF%E5%AD%90/dp/4798052817/" target="_blank">はじめてのブログをワードプレスで作るための本</a>」発売後に大きなバージョンアップを行ったため、書籍記載の設定内容と異なっていますがご了承下さい。
			</p>
		</div>
	</div><!-- .ys-info__section -->
	<div class="ystandard-settings__section">
		<h3><span class="orbitron">yStandard</span>情報</h3>
		<p>
			<span class="ys-info-h"><span class="orbitron">yStandard</span>本体</span>: <?php echo ys_get_theme_version( true ); ?>
			<?php if ( get_template() != get_stylesheet() ) : ?>
				<br><span class="ys-info-h">子テーマ</span>: <?php echo ys_get_theme_version(); ?>
			<?php endif; ?>
		</p>
	</div>
	<div class="ystandard-settings__section">
		<h3><span class="orbitron">yStandard</span>を応援する</h3>
		<div class="flex">
			<div class="ystandard-settings__flex50 ys-text-center ystandard-settings__peing">
				<h4>フォロー・メッセージ</h4>
				<p><img class="ystandard-settings__icon" src="https://pbs.twimg.com/profile_images/914315796389564417/ie5KjEtn_400x400.jpg" alt=""></p>
				<p><a href="https://twitter.com/yosiakatsuki?ref_src=twsrc%5Etfw" class="twitter-follow-button" data-show-count="false">Follow @yosiakatsuki</a><script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script></p>
				<p><a class="ys-btn" href="https://peing.net/ja/yosiakatsuki">Peingで質問を送る</a></p>
			</div>
			<div class="ystandard-settings__flex50 ys-text-center flex ystandard-setting__support">
				<h4>なにか送る</h4>
				<div class="flex flex--c-c">
					<div>
						<p><span style="display:inline-block"><a href="https://osushi.love/intent/post/d57eb961b970410c91001ca3093b9f75" class="ossh-post-btn" data-type="large">お寿司を送る</a><script src="https://platform.osushi.love/widgets.js"></script></span></p>
					</div>
				</div><!-- .flex flex--c-c -->
			</div>
		</div><!-- .flex -->
	</div>
</div><!-- /.warp -->