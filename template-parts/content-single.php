<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="http://schema.org/BlogPosting" itemref="masthead" >
	<meta itemscope id="EntityOfPageid-<?php the_ID(); ?>" itemscope itemprop="mainEntityOfPage" itemtype="https://schema.org/Webpage" itemid="<?php echo the_permalink(); ?>">

	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title" itemprop="headline name">', '</h1>' ); ?>

		<div class="entry-meta entry-date-container">
			<?php ys_template_the_entry_date(); ?>
		</div><!-- .entry-meta -->

		<?php if(has_post_thumbnail()): ?>
			<figure class="post-thumbnail" itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
				<?php ys_image_the_post_thumbnail(); ?>
			</figure><!-- .post-thumbnail -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<?php
		if(ys_is_amp() && get_option('ys_amp_normal_link',0) == 1):
	?>
	<div class="amp-view-info">
		<p>※現在このページは高速表示用レイアウトで表示されています。<a class="normal-view-link" href="<?php the_permalink() ?>">通常表示に切り替える »</a></p>
	</div>
	<?php endif; ?>

	<div class="entry-content" itemprop="articleBody">
		<?php

			the_content();

			ys_template_the_link_pages();

		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php
			// シェアボタン
			ys_template_the_sns_share();
			// 書いた人
			get_template_part( 'template-parts/biography' );
		 ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->