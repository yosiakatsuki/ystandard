<?php
/**
 * SNSシェアボタンテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

$data = ys_get_share_button_data();
if ( ! empty( $data ) ) :
	$col = ys_get_sns_share_button_col();
	?>
	<aside class="share-btn">
		<ul class="share-btn__list li-clear flex flex--row -no-gutter -all">
			<?php foreach ( $data as $value ) : ?>
				<li class="share-btn__item <?php echo esc_attr( $col ); ?>">
					<a class="share-btn__link sns-bg--<?php echo $value['type']; ?> -hover sns-border--<?php echo $value['type']; ?>" href="<?php echo $value['url']; ?>" target="_blank" rel="nofollow noopener noreferrer"><i class="<?php echo $value['icon']; ?> share-btn__icon" aria-hidden="true"></i><span class="share-btn__text"><?php echo $value['button-text']; ?></span></a>
				</li>
			<?php endforeach; ?>
		</ul>
	</aside>
<?php endif; ?>