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
<aside id="comments" class="comments-area comments__area singular-footer__block">
	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">コメント</h2>
		<ol class="comment-list comment__list">
			<?php
			wp_list_comments(
				array(
					'type'        => 'comment',
					'style'       => 'ol',
					'short_ping'  => false,
					'avatar_size' => 42,
					'callback'    => 'ys_wp_list_comments_callback',
				)
			);
			?>
		</ol><!-- .comment-list -->
		<?php the_comments_navigation(); ?>
	<?php endif; // Check for have_comments(). ?>
	<?php
	// If comments are closed and there are comments, let's leave a little note, shall we?
	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
		?>
		<p class="no-comments comments--no-comments">コメントは閉じられています。</p>
	<?php endif; ?>
	<?php
	comment_form(
		array(
			'title_reply_before' => '<h2 id="reply-title" class="comment-reply-title comment-reply__title">',
			'title_reply_after'  => '</h2>',
			'comment_field'      => '<p class="comment-form-comment"><label for="comment">' . _x( 'Comment', 'noun' ) . '<span class="required">*</span></label><textarea id="comment" class="comment__textarea" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>',
			'class_submit'       => 'submit comment__submit ys-btn--full',
		)
	);
	?>
</aside><!-- .comments-area -->