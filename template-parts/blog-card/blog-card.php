<?php
/**
 * ブログカード フォーマットHTML
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

defined( 'ABSPATH' ) || die();

// 呼び出し元から表示データを受け取れない場合は、不完全なカードを出力しない.
if ( empty( $args['ys_card_data'] ) ) {
	return;
}
$ys_card_data = $args['ys_card_data'];

?>

<div class="ys-blog-card">
	<div class="ys-blog-card__container">
		<?php if ( $ys_card_data['thumbnail'] ) : ?>
			<figure class="ys-blog-card__image">
				<?php echo $ys_card_data['thumbnail']; ?>
			</figure>
		<?php endif; ?>
		<div class="ys-blog-card__text">
			<p class="ys-blog-card__title">
				<a class="ys-blog-card__link" href="<?php echo esc_url_raw( $ys_card_data['url'] ); ?>"<?php echo $ys_card_data['target']; ?>><?php echo esc_html( $ys_card_data['title'] ); ?></a>
			</p>
			<?php if ( $ys_card_data['dscr'] ) : ?>
				<div class="ys-blog-card__dscr">
					<?php echo esc_html( $ys_card_data['dscr'] ); ?>
				</div>
			<?php endif; ?>
			<?php if ( $ys_card_data['domain'] ) : ?>
				<div class="ys-blog-card__domain"><?php echo esc_html( $ys_card_data['domain'] ); ?></div>
			<?php endif; ?>
		</div>
	</div>
</div>
