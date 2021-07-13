<?php
/**
 * パンくずリストテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

defined( 'ABSPATH' ) || die();

if ( empty( $breadcrumbs ) ) {
	return;
}
?>
<div id="breadcrumbs" class="breadcrumbs">
	<div class="container">
		<ol class="breadcrumbs__list li-clear">
			<?php foreach ( $breadcrumbs as $key => $item ) : ?>
				<li class="breadcrumbs__item">
					<?php if ( empty( $item['item'] ) ) : ?>
						<?php echo esc_html( $item['name'] ); ?>
					<?php else : ?>
						<a href="<?php echo esc_url_raw( $item['item'] ); ?>">
							<?php echo esc_html( $item['name'] ); ?>
						</a>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ol>
	</div><!-- .container -->
</div><!-- #breadcrumbs.breadcrumbs -->
