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
<div class="footer-sns footer-section">
	<ul class="footer-sns__list li-clear flex flex-wrap flex--j-center">
		<?php
		foreach ( $sns_list as $value ) :
			if ( '' !== $value['url'] ) :
				?>
				<li class="footer-sns__item <?php echo $value['class']; ?>">
					<a class="footer-sns__link flex flex--c-c" href="<?php echo $value['url']; ?>" target="_blank" rel="nofollow noopener noreferrer" title="<?php echo $value['title']; ?>">
						<?php
						if ( '' !== $value['icon'] ) {
							echo $value['icon'];
						}
						?>
					</a>
				</li>
				<?php
			endif;
		endforeach;
		?>
	</ul>
</div><!-- site-footer__sns -->