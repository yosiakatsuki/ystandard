<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="http://schema.org/BlogPosting" itemref="masthead biography">
	<meta itemscope id="EntityOfPageid-<?php the_ID(); ?>" itemscope itemprop="mainEntityOfPage" itemtype="https://schema.org/Webpage" itemid="<?php echo the_permalink(); ?>">

	<header class="entry-header">

		<?php
			// ヒーローエリア
			ys_template_the_post_hero();

			the_title( '<h1 class="entry-title" itemprop="headline name">', '</h1>' );
		?>

		<div class="entry-meta entry-date-container">
			<?php ys_template_the_entry_date(); ?>
		</div><!-- .entry-meta -->
		<?php
			// 広告
			ys_template_the_advertisement_under_title();
		?>

		<?php if(has_post_thumbnail() && ys_get_setting('ys_hide_post_thumbnail') == 0): ?>
			<figure class="post-thumbnail" itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
				<?php ys_template_the_post_thumbnail(); ?>
			</figure><!-- .post-thumbnail -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<?php
		if(ys_is_amp() && ys_get_setting('ys_amp_normal_link') == 1):
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

</article><!-- #post-## -->