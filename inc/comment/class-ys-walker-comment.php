<?php
/**
 * Comment API: Walker_Comment class
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * Core walker class used to create an HTML list of comments.
 *
 * @since 2.7.0
 *
 * @see   Walker
 */
class YS_Walker_Comment extends Walker_Comment {
	/**
	 * Outputs a comment in the HTML5 format.
	 *
	 * @param WP_Comment $comment Comment to display.
	 * @param int        $depth   Depth of the current comment.
	 * @param array      $args    An array of arguments.
	 *
	 * @see   wp_list_comments()
	 */
	protected function html5_comment( $comment, $depth, $args ) {
		$tag = ( 'div' === $args['style'] ) ? 'div' : 'li';

		$commenter = wp_get_current_commenter();
		if ( $commenter['comment_author_email'] ) {
			$moderation_note = __( 'Your comment is awaiting moderation.' );
		} else {
			$moderation_note = __( 'Your comment is awaiting moderation. This is a preview, your comment will be visible after it has been approved.' );
		}

		?>
		<<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( $this->has_children ? 'parent' : '', $comment ); ?>>
		<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
			<footer class="comment-meta">
				<div class="comment-meta__container">
					<?php if ( 0 !== $args['avatar_size'] ) : ?>
						<div class="comment-author">
							<?php
							echo get_avatar(
								$comment->comment_author_email,
								$args['avatar_size']
							);
							?>
						</div><!-- .comment-author -->
					<?php endif; ?>
					<div class="comment-metadata">
						<?php printf( '<p class="comment-author-name">%s</p>', get_comment_author_link( $comment ) ); ?>
						<a href="<?php echo esc_url( get_comment_link( $comment, $args ) ); ?>" class="comment-post-time">
							<time datetime="<?php comment_time( 'c' ); ?>">
								<?php
								/* translators: 1: Comment date, 2: Comment time. */
								printf( __( '%1$s at %2$s' ), get_comment_date( '', $comment ), get_comment_time() );
								?>
							</time>
						</a>
					</div><!-- .comment-metadata -->
					<div class="comment-edit">
						<?php
						edit_comment_link( __( 'Edit' ), '<div class="edit-link">', '</div>' );
						comment_reply_link(
							array_merge(
								$args,
								[
									'add_below' => 'div-comment',
									'depth'     => $depth,
									'max_depth' => $args['max_depth'],
									'before'    => '<div class="reply">',
									'after'     => '</div>',
								]
							)
						);
						?>
					</div>

				</div>
				<?php if ( '0' === $comment->comment_approved ) : ?>
					<em class="comment-awaiting-moderation"><?php echo $moderation_note; ?></em>
				<?php endif; ?>
			</footer><!-- .comment-meta -->

			<div class="comment-content">
				<?php comment_text(); ?>
			</div><!-- .comment-content -->
		</article><!-- .comment-body -->
		<?php
	}
}
