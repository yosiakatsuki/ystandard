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
	</div>

	<div class="ystandard-settings__section">
		<h3><span class="orbitron">yStandard</span>情報</h3>
		<p>
			<span class="ys-info-h"><span class="orbitron">yStandard</span>本体</span>: <?php echo ys_get_theme_version( true ); ?>
			<?php if ( get_template() !== get_stylesheet() ) : ?>
				<br><span class="ys-info-h">子テーマ</span>: <?php echo ys_get_theme_version(); ?>
			<?php endif; ?>
		</p>
	</div>

	<div class="ystandard-settings__section ys-text-center">
		<div class="row">
			<div class="col col__2--tb">
				<div class="card">
					<h3><span class="orbitron">yStandard</span>を応援する</h3>
					<div class="ystandard__info-icon"><i class="far fa-thumbs-up"></i></div>
					<div class="card__text">
						<ul>
							<li>「知り合いにyStandardを紹介する」</li>
							<li>「ブログでyStandardを紹介する」</li>
						</ul>
						<p>
							など、ちょっとしたことでもyStandadを応援する方法があります
						</p>
						<p>
							<a class="ys-btn ys-btn--large" href="https://wp-ystandard.com/contribute/" target="_blank"><span class="orbitron">yStandard</span>を応援する</a>
						</p>
					</div>
				</div><!-- card -->
			</div>
			<div class="col col__2--tb">
				<div class="card">
					<h3><span class="orbitron">yStandard</span>ユーザーコミュニティ</h3>
					<div class="ystandard__info-icon"><i class="fab fa-slack"></i></div>
					<div class="card__text">
						<p>
							yStandard利用者同士での交流を目的としたSlackチームです<br><br>
							テーマを利用していて気になった疑問点やカスタマイズTips等シェアしていただいてテーマをより楽しく使って頂けたらと思います<br>
						</p>
						<p>
							<a class="ys-btn ys-btn--large" href="https://wp-ystandard.com/ystandard-user-community/" target="_blank"><span class="orbitron">yStandard</span>ユーザーコミュニティに参加する</a>
						</p>
					</div>
				</div><!-- card -->
			</div>
		</div>
	</div>
</div><!-- /.warp -->
