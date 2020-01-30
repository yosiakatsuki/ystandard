<?php
/**
 * パンくずリストテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

if ( is_front_page() || ys_is_no_title_template() ) {
	return;
}
$ys_breadcrumb = new YS_Breadcrumb();
$items = $ys_breadcrumb->get_breadcrumbs();
if ( empty( $items ) ) {
	return;
}
?>
<div id="breadcrumbs" class="breadcrumbs">
	<div class="container">
		<ol class="breadcrumbs__list li-clear">
			<?php foreach ( $items as $key => $item ) : ?>
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