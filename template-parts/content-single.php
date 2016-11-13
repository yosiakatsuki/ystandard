<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

		<div class="entry-meta entry-date-container">
			<?php ys_entry_the_entry_date(); ?>
		</div><!-- .entry-meta -->

		<?php if(has_post_thumbnail()): ?>
			<figure class="post-thumbnail">
				<?php ys_image_the_post_thumbnail(); ?>
			</figure><!-- .post-thumbnail -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<?php
		if(ys_is_amp() && get_option('ys_amp_normal_link',0) == 1):
	?>
	<div class="amp-view-info">
		<p>※現在このページは高速表示用レイアウトで表示されています。<a class="normal-view-link" href="<?php the_permalink() ?>">通常表示に切り替える »</a></p>
		<p></p>
	</div>
	<?php endif; ?>

	<div class="entry-content">
		<?php

			the_content();

			ys_entry_the_link_pages();

		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php
			// シェアボタン
			ys_entry_the_sns_share();
			// 書いた人
			get_template_part( 'template-parts/biography' );
		 ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->