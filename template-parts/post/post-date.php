<?php
/**
 * 投稿日・更新日表示
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

defined( 'ABSPATH' ) || die();

// 呼び出し元で表示対象として組み立てた日付情報だけを使用する.
$post_date = $args['post_date'] ?? [];

// 表示対象の日付がない場合は、空のメタ情報を出力しない.
if ( empty( $post_date ) ) {
	return;
}
?>
<div class="singular-date">
	<?php foreach ( $post_date as $date ) : ?>
		<span class="singular-date__item">
			<?php echo $date['icon']; ?>
			<?php if ( $date['time'] ) : ?>
				<time class="updated" datetime="<?php echo esc_attr( $date['datetime'] ); ?>"><?php echo esc_html( $date['text'] ); ?></time>
			<?php else : ?>
				<?php echo esc_html( $date['text'] ); ?>
			<?php endif; ?>
		</span>
	<?php endforeach; ?>
</div>
