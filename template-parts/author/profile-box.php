<div class="author__box clearfix">
	<div class="author__main">
		<?php
			$avatar = ys_get_author_avatar();
			if( $avatar ): ?>
			<figure class="author__avatar">
				<a href="<?php ys_the_author_link(); ?>" rel="author"><?php echo $avatar; ?></a>
			</figure>
		<?php endif; ?>
		<h2 class="author__name clear-headline"><?php ys_the_author_name(); ?></h2>
	</div><!-- .author__main -->
	<div class="author__sub">
		<div class="author__dscr">
			<?php ys_the_author_description(); ?>
		</div><!-- .author__dscr -->
		<?php
			$sns = ys_get_author_sns_list();
			if( ! empty( $sns ) ): ?>
			<ul class="author__sns list-style--none">
				<?php foreach ( $sns as $item ) : ?>
					<li class="author__sns-item">
						<a class="sns__color--<?php echo $item['icon']; ?> author__sns-link" href="<?php echo $item['url']; ?>" target="_blank" rel="nofollow"><i class="fa fa-<?php echo $item['icon']; ?>" aria-hidden="true"></i></a>
					</li>
				<?php endforeach; ?>
			</ul><!-- .author__sns -->
		<?php endif; ?>
		<p class="author__archive">
			<a class="ys-btn" href="<?php ys_the_author_link(); ?>">記事一覧</a>
		</p><!-- .author__archive -->
	</div><!-- .author__sub -->
</div><!-- .author -->