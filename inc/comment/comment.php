<?php
/**
 *
 * 投稿コメント関連
 *
 */
/**
 * コメント一覧のコールバック
 */
if ( ! function_exists( 'ys_wp_list_comments_callback' ) ) {
	function ys_wp_list_comments_callback( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		extract( $args, EXTR_SKIP );
		if ( 'div' == $args['style'] ) {
			$tag = 'div';
			$add_below = 'comment';
		} else {
			$tag = 'li';
			$add_below = 'div-comment';
		}
		$comment_class = array( 'comment__item' );
		if( ! empty( $args['has_children'] ) ) {
			$comment_class[] = 'parent';
			$comment_class[] = 'comment__parent';
		}
	?>
		<<?php echo $tag ?> <?php comment_class( $comment_class ); ?> id="comment-<?php comment_ID() ?>">
			<?php if ( 'div' != $args['style'] ) : ?>
			<div id="div-comment-<?php comment_ID() ?>" class="comment-body comment__body">
			<?php endif; ?>
			<div class="comment-author comment__author clearfix">
				<?php
					if( ys_get_option( 'show_avatars' ) ){
						if( 0 !=  $args['avatar_size'] ) {
							if( $comment->comment_author_email == get_the_author_meta( 'user_email' ) ){
								echo ys_get_author_avatar( false, $args['avatar_size'] );
							} else {
								echo get_avatar( $comment, $args['avatar_size'] );
							}
						}
					}
				?>
			<?php printf( __( '<cite>%s</cite>' ), get_comment_author_link() ); ?>
			<?php if ( '0' == $comment->comment_approved ) : ?>
				<span class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.' ); ?></span>
			<?php endif; ?>
				<div class="comment-meta commentmetadata comment__meta"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>">
					<?php
						/* translators: 1: date, 2: time */
						printf( __('%1$s at %2$s'), get_comment_date(),  get_comment_time() ); ?></a><?php edit_comment_link( __( '(Edit)' ), '  ', '' );
					?>
				</div>
			</div>
			<div class="comment-text comment__text">
				<?php comment_text(); ?>
			</div>
			<?php
				$reply = get_comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) );
				if( '' != $reply):
			 ?>
			<div class="reply comment__reply">
				<?php echo $reply; ?>
			</div>
			<?php endif; ?>
			<?php if ( 'div' != $args['style'] ) : ?>
			</div>
			<?php endif; ?>
	<?php
	}
}

/**
 * コメント中で使用できるタグを限定
 */
function ys_comment_tags( $data ) {
  global $allowedtags;
	/**
	 * もろもろ削除
	 */
  unset( $allowedtags['a'] );
  unset( $allowedtags['abbr'] );
  unset( $allowedtags['acronym'] );
  unset( $allowedtags['b'] );
  unset( $allowedtags['div'] );
  unset( $allowedtags['cite'] );
  unset( $allowedtags['code'] );
  unset( $allowedtags['del'] );
  unset( $allowedtags['em'] );
  unset( $allowedtags['i'] );
  unset( $allowedtags['q'] );
  unset( $allowedtags['strike'] );
  unset( $allowedtags['strong'] );
	/**
	 * 使えるタグをセットする
	 */
	$allowedtags['a']          = array();
	$allowedtags['pre']        = array();
	$allowedtags['code']       = array();
	$allowedtags['blockquote'] = array();
	$allowedtags['cite']       = array();
	$allowedtags['strong']     = array();
  return $data;
}
add_filter( 'comments_open', 'ys_comment_tags' );
add_filter( 'pre_comment_approved', 'ys_comment_tags' );