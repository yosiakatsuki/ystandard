<?php
/**
 * 設定スタートページ
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
}

$current_url = ys_get_page_url();
?>
<div class="wrap ystd-settings">
	<h2><span class="orbitron">yStandard</span> 設定ページ</h2>
	<div class="ystd-settings__section">
		<h3><span class="orbitron">yStandard</span> の設定はテーマカスタマイザーから!</h3>
		<p>yStandardの設定はテーマカスタマイザーから行って下さい。</p>
		<p class="wp-block-button -lg"><a href="<?php echo esc_url( add_query_arg( 'return', urlencode( $current_url ), wp_customize_url() ) ); ?>">テーマカスタマイザーを開く</a></p>
		<div class="ys-smaller">
			<p>
				※ version 2.0.0 以降はテーマカスタマイザーから設定を行う方式になりました
			</p>
		</div>
	</div>

	<div class="ystd-settings__section">
		<h3><span class="orbitron">yStandard</span>情報</h3>
		<p>
			<span class="ys-info-h"><span class="orbitron">yStandard</span>本体</span>: <?php echo ys_get_theme_version( true ); ?>
			<?php if ( get_template() !== get_stylesheet() ) : ?>
				<br><span class="ys-info-h">子テーマ</span>: <?php echo ys_get_theme_version(); ?>
			<?php endif; ?>
		</p>
	</div>

	<div class="ystd-settings__section text--center">
		<div class="flex flex--row">
			<div class="flex__col flex__col--md-2">
				<div class="card">
					<h3><span class="orbitron">yStandard</span>を応援する</h3>
					<div class="ystd-settings__icon"><i class="far fa-thumbs-up fa-2x"></i></div>
					<div class="card__text">
						<ul>
							<li>「知り合いにyStandardを紹介する」</li>
							<li>「ブログでyStandardを紹介する」</li>
						</ul>
						<p>
							など、ちょっとしたことでもyStandadを応援する方法があります
						</p>
						<p class="wp-block-button -block">
							<a class="ystd-settings__btn" href="https://wp-ystandard.com/contribute/" target="_blank" rel="noopener noreferrer nofollow"><span class="orbitron">yStandard</span>を応援する</a>
						</p>
					</div>
				</div><!-- card -->
			</div>
			<div class="flex__col flex__col--md-2">
				<div class="card">
					<h3><span class="orbitron">yStandard</span>ユーザーコミュニティ</h3>
					<div class="ystd-settings__icon"><i class="fab fa-slack fa-2x"></i></div>
					<div class="card__text">
						<p>
							yStandard利用者同士での交流を目的としたSlackチームです<br><br>
							テーマを利用していて気になった疑問点やカスタマイズTips等シェアしていただいてテーマをより楽しく使って頂けたらと思います<br>
						</p>
						<p>
							<a class="ystd-settings__btn btn btn--block" href="https://wp-ystandard.com/ystandard-user-community/" target="_blank" rel="noopener noreferrer nofollow"><span class="orbitron">yStandard</span>ユーザーコミュニティに参加する</a>
						</p>
					</div>
				</div><!-- card -->
			</div>
		</div>
	</div>
</div><!-- /.warp -->
