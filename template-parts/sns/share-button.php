<?php
	$data = ys_get_share_button_data();
	if( ! empty( $data ) ) :
?>
<aside class="share-btn">
	<ul class="share-btn__list">
		<?php foreach ($data as $value): ?>
			<li class="share-btn__item">
				<a class="share-btn__link <?php echo $value['type']; ?>" href="<?php echo $value['url']; ?>" target="_blank" rel="nofollow">
					<i class="fa fa-<?php echo $value['icon']; ?> share-btn__icon" aria-hidden="true"></i><span class="share-btn__text"><?php echo $value['button-text']; ?></span></a>
			</li>
		<?php endforeach; ?>
	</ul>
</aside>
<?php endif; ?>