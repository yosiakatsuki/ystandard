<?php
if ( post_password_required() ) {
	return;
}
?>
<aside id="comments" class="comments-area comments__area entry__footer-section">
	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title entry__footer-title">
			<?php
				$comments_number = get_comments_number();
				echo $comments_number.' 件のコメント'
			?>
		</h2>
		<ol class="comment-list comment__list">
			<?php
				wp_list_comments( array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 42,
					'callback' => 'ys_wp_list_comments_callback'
				) );
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
		comment_form( array(
			'title_reply_before' => '<h2 id="reply-title" class="comment-reply-title comment-reply__title">',
			'title_reply_after'  => '</h2>',
			'comment_field' => '<p class="comment-form-comment"><label for="comment">' . _x( 'Comment', 'noun' ) . '<span class="required">*</span></label><textarea id="comment" class="comment__text" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>',
			'class_submit' => 'submit ys-btn--full'
		) );
	?>
</aside><!-- .comments-area -->