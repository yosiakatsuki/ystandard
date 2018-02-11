<?php
	$paging = ys_paging();
	if( ! $paging ) {
		return;
	}
?>
<section class="entry-paging entry__footer-section">
	<div class="container">
		<div class="row">
			<div class="col__2">
				<?php if( $paging['prev'] ): ?>
					<a class="entry-paging__prev" href="<?php echo $paging['prev']['url']; ?>">
						<p class="entry-paging__info entry-paging__info--prev color__font-sub">«前の記事</p>
						<?php if( $paging['prev']['image'] ): ?>
							<div class="ratio ratio__16-9">
								<figure class="entry-paging__image ratio__item ratio__image">
									<?php echo $paging['prev']['image']; ?>
								</figure>
							</div><!-- .ratio -->
						<?php endif;?>
						<h2 class="entry-paging__title"><?php echo $paging['prev']['title']; ?></h2>
					</a>
				<?php else: ?>
					<a class="entry-paging__home flex flex--c-c" href="<?php echo home_url( '/' ); ?>">
						<i class="fa fa-home" aria-hidden="true"></i>
					</a><!-- .entry-paging__home -->
				<?php endif; ?>
			</div><!-- .col__2 -->
			<div class="col__2">
				<?php if( $paging['next'] ): ?>
					<a class="entry-paging__next" href="<?php echo $paging['next']['url']; ?>">
						<p class="entry-paging__info entry-paging__info--next color__font-sub">次の記事»</p>
						<?php if( $paging['next']['image'] ): ?>
							<div class="ratio ratio__16-9">
								<figure class="entry-paging__image ratio__item ratio__image">
									<?php echo $paging['next']['image']; ?>
								</figure>
							</div><!-- .ratio -->
						<?php endif;?>
						<h2 class="entry-paging__title"><?php echo $paging['next']['title']; ?></h2>
					</a>
				<?php else: ?>
					<div class="entry-paging__home flex flex--c-c">
						<a href="<?php echo home_url( '/' ); ?>"><i class="fa fa-home" aria-hidden="true"></i></a>
					</div><!-- .entry-paging__home -->
				<?php endif; ?>
			</div><!-- .col__2 -->
		</div><!-- .row -->
	</div><!-- .container -->
</section><!-- .entry-paging -->