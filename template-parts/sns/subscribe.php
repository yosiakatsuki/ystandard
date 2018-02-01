<?php
	$subscribe = ys_get_subscribe_buttons();
	if( ! empty( $subscribe ) ):
?>
<aside id="subscribe" class="subscribe">
	<p class="subscribe__title">この記事が気に入ったらフォロー！</p>
	<div class="subscribe__container">
		<?php ys_the_subscribe_background_image(); ?>
		<div class="subscribe__buttons">
			<ul class="subscribe__list">
			<?php
				foreach ( $subscribe as $key => $value ): ?>
				<li class="subscribe__item">
					<a class="subscribe__link <?php echo 'sns__btn-outline--' . esc_attr( $value['class'] ); ?>" href="<?php echo esc_url_raw( $value['url'] ) ?>" target="_blank" rel="nofollow"><i class="fa fa-<?php echo $value['icon']; ?> sns-icon--left" aria-hidden="true"></i><?php echo esc_html( $value['text'] ); ?></a>
				</li>
			<?php endforeach; ?>
			</ul>
		</div><!-- .subscribe__buttons -->
	</div><!-- .subscribe__container -->
</aside>
<?php endif; ?>