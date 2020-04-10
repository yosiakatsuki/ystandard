<?php
/**
 * コメントテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

if ( post_password_required() ) {
	return;
}
?>
<aside id="comments" class="comments__area comments-area">
	<?php if ( have_comments() ) : ?>
		<p class="comments-title">コメント</p>
		<ol class="comments__list comment-list">
			<?php
			wp_list_comments(
				[
					'type'        => 'comment',
					'style'       => 'ol',
					'short_ping'  => false,
					'avatar_size' => 42,
					'walker'      => new YS_Walker_Comment(),
				]
			);
			?>
		</ol>
		<?php the_comments_navigation(); ?>
	<?php endif; ?>
	<?php if ( comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
		<?php
		comment_form(
			[
				'title_reply_before' => '<p id="reply-title" class="comments__reply-title comment-reply-title">',
				'title_reply_after'  => '</p>',
				'comment_field'      => '<p class="comments__form-comment"><label for="comment">' . _x( 'Comment', 'noun' ) . '<span class="required">*</span></label><textarea id="comment" class="comments__textarea" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>',
				'class_submit'       => 'submit comments__submit',
			]
		);
		?>
	<?php endif; ?>
</aside>
