<?php
/**
 * 購読ボタンテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

$subscribe = ys_get_subscribe_buttons();
if ( ! empty( $subscribe ) ) :
	?>
	<aside id="subscribe" class="subscribe">
		<div class="subscribe__container">
			<div class="row row--no-gutter">
				<div class="col__2--tb col--no-gutter subscribe__image-container">
					<?php ys_the_subscribe_image(); ?>
				</div><!-- .col__2-tb -->
				<div class="col__2--tb col--no-gutter subscribe__buttons-container">
					<div class="flex flex--c-c">
						<div class="subscribe__buttons">
							<p class="subscribe__title">この記事が気に入ったらフォロー！</p>
							<ul class="subscribe__list list-style--none">
								<?php foreach ( $subscribe as $key => $value ) : ?>
									<li class="subscribe__item">
										<a class="subscribe__link <?php echo 'sns__btn--' . esc_attr( $value['class'] ); ?>" href="<?php echo esc_url_raw( $value['url'] ); ?>" target="_blank" rel="nofollow"><i class="<?php echo $value['icon']; ?> sns-icon--left" aria-hidden="true"></i><?php echo esc_html( $value['text'] ); ?></a>
									</li>
								<?php endforeach; ?>
							</ul>
						</div><!-- .subscribe__buttons -->
					</div><!-- .flex flex-c-c -->
				</div><!-- .col__2-tb -->
			</div><!-- .row -->
		</div><!-- .subscribe__container -->
	</aside>
<?php endif; ?>