<?php
/**
 * プロフィール表示テンプレート
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

if ( ! ys_is_display_author_data() ) {
	return;
}
?>
<div class="author__box clearfix">
	<div class="author__avatar">
		<?php
		$avatar = ys_get_author_avatar();
		if ( $avatar ) :
		?>
			<figure class="author__icon">
				<a href="<?php ys_the_author_link(); ?>" rel="author"><?php echo $avatar; ?></a>
			</figure>
		<?php endif; ?>
	</div><!-- .author__icon -->
	<div class="author__text">
		<div class="author__main">
			<h2 class="author__name clear-headline"><?php ys_the_author_name(); ?></h2>
			<?php ys_the_author_sns(); ?>
		</div>
		<div class="author__dscr">
			<?php ys_the_author_description(); ?>
		</div><!-- .author__dscr -->
		<?php if ( ! is_author() ) : ?>
		<p class="author__archive">
			<a class="ys-btn author__link" href="<?php ys_the_author_link(); ?>">記事一覧</a>
		</p><!-- .author__archive -->
		<?php endif; ?>
	</div><!-- .author__text -->
</div><!-- .author -->