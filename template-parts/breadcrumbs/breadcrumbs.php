<?php
/**
 * パンくずリストテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

defined( 'ABSPATH' ) || die();

if ( empty( $args['breadcrumbs'] ) ) {
	return;
}
$breadcrumbs = $args['breadcrumbs'];
?>
<nav id="breadcrumbs" class="breadcrumbs" aria-label="<?php esc_attr_e( 'パンくずリスト', 'ystandard' ); ?>">
	<div class="breadcrumbs-container">
		<ol class="breadcrumbs__list">
			<?php foreach ( $breadcrumbs as $key => $item ) : ?>
				<li class="breadcrumbs__item">
					<?php if ( empty( $item['item'] ) ) : ?>
						<?php echo esc_html( $item['name'] ); ?>
					<?php else : ?>
						<a href="<?php echo esc_url( $item['item'] ); ?>">
							<?php echo esc_html( $item['name'] ); ?>
						</a>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ol>
	</div><!-- .container -->
</nav><!-- #breadcrumbs.breadcrumbs -->
