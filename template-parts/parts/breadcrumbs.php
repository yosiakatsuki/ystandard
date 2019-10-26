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
$items = ys_get_breadcrumbs();
?>
<div id="breadcrumbs" class="breadcrumbs">
	<div class="container">
		<ol class="breadcrumbs__list li-clear" itemscope itemtype="https://schema.org/BreadcrumbList">
			<?php foreach ( $items as $key => $item ) : ?>
				<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" class="breadcrumbs__item">
					<?php if ( empty( $item['link'] ) ) : ?>
						<span itemtype="https://schema.org/Thing" itemprop="item">
							<span itemprop="name"><?php echo esc_html( $item['title'] ); ?></span>
						</span>
					<?php else : ?>
						<a itemtype="https://schema.org/Thing" itemprop="item" href="<?php echo esc_url( $item['link'] ); ?>">
							<span itemprop="name"><?php echo esc_html( $item['title'] ); ?></span>
						</a>
					<?php endif; ?>
					<meta itemprop="position" content="<?php echo esc_attr( $key + 1 ); ?>"/>
				</li>
			<?php endforeach; ?>
		</ol>
	</div><!-- .container -->
</div><!-- #breadcrumbs.breadcrumbs -->