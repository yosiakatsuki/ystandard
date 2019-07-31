<?php
/**
 * フォローボックス表示テンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * フォローボックスの表示は基本的にショートコードで処理しています。
 * YS_Shortcode_Follow_Boxクラス内でデータを作成し、このファイルをテンプレートとしてHTMLを生成します。
 * (class/shortcode/class-ys-shortcode-follow-box.php)
 */
global $follow_box;
if ( empty( $follow_box ) ) {
	return;
}
/**
 * 画像
 */
$image = $follow_box['image'];
/**
 * SNSリスト
 */
$follow_list = $follow_box['follow_list'];
/**
 * メッセージ
 */
$message_top    = $follow_box['message_top'];
$message_bottom = $follow_box['message_bottom'];
/**
 * レイアウト関連
 */
$row = $follow_box['class_row'];
$col = $follow_box['class_col'];


?>
<div class="<?php echo $row; ?>">
	<div class="follow-box__image <?php echo $col; ?>">
		<?php if ( $image ) : ?>
			<div class="ratio -r-16-9">
				<div class="ratio__item">
					<div class="ratio__image">
						<?php echo $image; ?>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</div>
	<div class="follow-box__sns <?php echo $col; ?>">
		<div class="follow-box__inner flex flex--c-c flex--column">
			<?php if ( $message_top ) : ?>
				<div class="has-small-font-size"><?php echo esc_html( $message_top ); ?></div>
			<?php endif; ?>
			<div class="follow-box__list">
				<ul class="li-clear text--center">
					<?php foreach ( $follow_list as $item ) : ?>
						<li class="follow-box__item">
							<a class="follow-box__link <?php echo esc_attr( $item['class'] ); ?> -hover" href="<?php echo esc_url_raw( $item['url'] ); ?>" rel="nofollow noopener noreferrer"><?php echo $item['icon']; ?><span class="follow-box__text"><?php echo esc_html( $item['text'] ); ?></span></a></li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</div>
</div>