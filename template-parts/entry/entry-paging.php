<?php
	$paging = ys_paging();
	if( ! $paging ) {
		return;
	}
?>
<section class="entry-paging entry__footer-section">
	<div class="container">
		<div class="row">
			<?php foreach ( $paging as $key => $value ): ?>
				<div class="col__2">
					<?php if( is_array( $value ) ): ?>
						<a class="entry-paging__<?php echo $key; ?>" href="<?php echo $value['url']; ?>">
							<p class="entry-paging__info entry-paging__info--<?php echo $key; ?> color__font-sub"><?php echo $value['text']; ?></p>
							<div class="ratio ratio__16-9">
								<?php if( $value['image'] ): ?>
									<figure class="entry-paging__figure ratio__item ratio__image">
										<?php echo $value['image']; ?>
									</figure>
								<?php else: ?>
									<div class="entry-list__no-img ratio__item flex flex--c-c">
										<i class="fa fa-picture-o" aria-hidden="true"></i>
									</div><!-- .entry-list__no-img -->
								<?php endif;?>
							</div><!-- .ratio -->
							<h2 class="entry-paging__title"><?php echo $value['title']; ?></h2>
						</a>
					<?php else: ?>
						<a class="entry-paging__home flex flex--c-c" href="<?php echo home_url( '/' ); ?>">
							<i class="fa fa-home" aria-hidden="true"></i>
						</a><!-- .entry-paging__home -->
					<?php endif; ?>
				</div><!-- .col__2 -->
			<?php endforeach; ?>
		</div><!-- .row -->
	</div><!-- .container -->
</section><!-- .entry-paging -->