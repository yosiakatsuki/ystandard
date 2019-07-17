<?php
/**
 * プロフィール表示テンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 著者情報の表示は基本的にショートコードで処理しています。
 * YS_Shortcode_Author_Boxクラス内でデータを作成し、このファイルをテンプレートとしてHTMLを生成します。
 * (class/shortcode/class-ys-shortcode-author-box.php)
 */
global $ys_author_data;
if ( empty( $ys_author_data ) ) {
	return;
}
/**
 * プロフィール画像
 */
$avatar = $ys_author_data['avatar'];
/**
 * 投稿者名
 */
$name = $ys_author_data['name'];
/**
 * SNSリンク一覧
 * [color]:色指定用SNS名
 * [url]:リンク先URL
 * [icon]:アイコンフォント用クラス
 * [title]:SNS名称
 */
$sns = $ys_author_data['sns'];
/**
 * プロフィール文
 */
$profile = $ys_author_data['profile'];
/**
 * 記事一覧URL
 */
$archive_url = $ys_author_data['archive_url'];
/**
 * 記事一覧ボタン設定
 * ボタン名 or false(ボタンを表示しない)
 */
$archive_button = $ys_author_data['archive_button'];

/**
 * レイアウト関連
 */
$row        = $ys_author_data['class_row'];
$avatar_col = $ys_author_data['class_avatar_col'];
$text_col   = $ys_author_data['class_text_col'];

?>
<div class="<?php echo $row; ?>">
	<?php if ( $avatar ) : ?>
		<div class="<?php echo $avatar_col; ?>">
			<figure class="author-box__avatar">
				<?php echo $avatar; ?>
			</figure>
		</div>
	<?php endif; ?>
	<div class="author-box__text <?php echo $text_col; ?>">
		<?php
		/**
		 * 投稿者名
		 */
		echo $name;
		?>
		<?php if ( ! empty( $sns ) ) : ?>
			<div class="author-box__sns author-box__block">
				<ul class="li-clear flex">
					<?php foreach ( $sns as $key => $value ) : ?>
						<li class="author-box__sns-item">
							<a class="sns-text--<?php echo $value['color']; ?> author-box__sns-link" href="<?php echo $value['url']; ?>" target="_blank" rel="nofollow noopener noreferrer" title="<?php echo $value['title']; ?>"><i class="<?php echo $value['icon']; ?>" aria-hidden="true"></i></a>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>
		<?php if ( ! empty( $profile ) ) : ?>
			<div class="has-small-font-size author-box__dscr author-box__block">
				<?php echo $profile; ?>
			</div>
		<?php endif; ?>
		<?php if ( false !== $archive_button ) : ?>
			<div class="author-box__block">
				<p class="author-box__archive">
					<span class="wp-block-button -sm">
						<a class="has-small-font-size author-box__archive-link" href="<?php echo $archive_url; ?>"><?php echo $archive_button; ?></a>
					</span>
				</p><!-- .author__archive -->
			</div>
		<?php endif; ?>
	</div><!-- .author__text -->
</div><!-- .author -->