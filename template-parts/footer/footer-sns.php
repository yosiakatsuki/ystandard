<?php
/**
 * フッターSNSリンクテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

$sns_list = ys_get_footer_sns_list();
if ( empty( $sns_list ) ) {
	return;
}
?>
<div class="footer-sns">
	<ul class="footer-sns__list list-style--none flex-wrap flex--j-center">
		<?php
		foreach ( $sns_list as $value ) :
			if ( '' != $value['url'] ) :
				?>
				<li class="footer-sns__item <?php echo $value['class']; ?>">
					<a class="footer-sns__link flex flex--c-c" href="<?php echo $value['url']; ?>" target="_blank" rel="nofollow">
						<?php if ( '' != $value['icon-class'] ) : ?>
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