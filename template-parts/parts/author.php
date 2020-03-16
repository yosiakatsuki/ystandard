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
 */
if ( ! isset( $ys_author_data ) || empty( $ys_author_data ) ) {
	return;
}

?>
<div class="author-box">
	<div class="author-box__header">
		<?php if ( $ys_author_data['avatar'] ) : ?>
			<figure class="author-box__avatar">
				<?php echo $ys_author_data['avatar']; ?>
			</figure>
		<?php endif; ?>
		<div class="author-box__profile">
			<p class="author-box__name"><?php echo $ys_author_data['name']; ?></p>
			<?php if ( $ys_author_data['position'] ) : ?>
				<p class="author-box__position"><?php echo $ys_author_data['position']; ?></p>
			<?php endif; ?>
			<?php if ( ! empty( $ys_author_data['sns'] ) ) : ?>
				<ul class="author-box__sns">

					<?php foreach ( $ys_author_data['sns'] as $key => $value ) : ?>
						<li class="author-box__sns-item">
							<a class="author-box__sns-link sns-text--<?php echo $value['color']; ?>" href="<?php echo $value['url']; ?>" target="_blank" rel="nofollow noopener noreferrer" title="<?php echo $value['title']; ?>">
								<i class="<?php echo $value['icon']; ?>" aria-hidden="true"></i>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
	</div>
	<?php if ( $ys_author_data['description'] ) : ?>
		<div class="author-box__description">
			<?php echo $ys_author_data['description']; ?>
		</div>
	<?php endif; ?>
</div>
