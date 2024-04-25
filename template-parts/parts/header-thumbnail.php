<?php
/**
 * 投稿サムネイル - サイトヘッダー表示
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

defined( 'ABSPATH' ) || die();
/**
 * @var array $args {
 * @type string $header_thumbnail 投稿サムネイル画像HTML.
 * }
 */
if ( empty( $args ) || ! isset( $args['header_thumbnail'] ) ) {
	return;
}
?>

<figure class="site-header-thumbnail">
	<?php echo wp_kses_post( $args['header_thumbnail'] ); ?>
</figure>
