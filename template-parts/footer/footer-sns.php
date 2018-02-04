<?php
	$sns_list = ys_get_footer_sns_list();
?>
<div class="footer-sns">
	<ul class="footer-sns__list list-style--none flex-wrap">
	<?php
		foreach ( $sns_list as $value ) :
			if( '' != $value['url'] ):
			?>
			<li class="footer-sns__item <?php echo $value['class']; ?>">
				<a class="footer-sns__link"  href="<?php echo $value['url']; ?>" target="_blank" rel="nofollow">
					<?php if( '' != $value['icon-class'] ): ?>
						<i class="<?php echo $value['icon-class']; ?>" aria-hidden="true"></i>
					<?php endif; ?>
				</a>
			</li>
			<?php
			endif;
		endforeach;
	?>
	</ul>
</div><!-- site-footer__sns -->