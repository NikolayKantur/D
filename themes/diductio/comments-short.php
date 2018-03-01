<?php
/**
 * The template for displaying comments
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area modified">

	<?php if ( have_comments() ) : ?>
		<?php diductio_comment_nav(); ?>

		<ol class="diductio-comment-list">
			<?php
				wp_list_comments('callback=diductio_comments');
			?>
		</ol><!-- .comment-list -->

		<?php diductio_comment_nav(); ?>

	<?php endif; // have_comments() ?>

	<?php
		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="no-comments"><?php _e( 'Comments are closed.', 'diductio' ); ?></p>
	<?php endif; ?>

	<?php comment_form(array( 'logged_in_as' => '')); ?>

</div><!-- .comments-area -->
