<?php
/**
 * SNSシェアボタンテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * シェアボタンの表示は基本的にショートコードで処理しています。
 * YS_Shortcode_Share_Buttonクラス内でデータを作成し、このファイルをテンプレートとしてHTMLを生成します。
 * (inc/class/shortcode/class-ys-shortcode-share-button.php)
 */
global $ys_sns_share_btn_data;
$data  = $ys_sns_share_btn_data['data'];
$col   = $ys_sns_share_btn_data['col'];
$title = $ys_sns_share_btn_data['title'];
if ( empty( $data ) ) {
	return;
}

/**
 * 見出し
 */
if ( '' !== $title ) {
	echo $title;
}
?>
<ul class="share-btn__list li-clear flex flex--row -no-gutter -all">
	<?php foreach ( $data as $value ) : ?>
		<li class="share-btn__item <?php echo esc_attr( $col ); ?>">
			<a class="share-btn__link sns-bg--<?php echo $value['type']; ?> -hover sns-border--<?php echo $value['type']; ?>" href="<?php echo $value['url']; ?>" target="_blank" rel="nofollow noopener noreferrer"><i class="<?php echo $value['icon']; ?> share-btn__icon" aria-hidden="true"></i><span class="share-btn__text"><?php echo $value['button-text']; ?></span></a>
		</li>
	<?php endforeach; ?>
</ul>
