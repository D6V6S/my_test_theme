<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains comments and the comment form.
 *
 * @package WordPress
 * @subpackage Mytheme
 * @since Mytheme 1.0
 */

/*
 * If the current post is protected by a password and the visitor has not yet
 * entered the password we will return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php if ( have_comments() ) : ?>
		<h4 class="comments-title">
			<?php
			printf(
				_nx(
					'One thought on "%2$s"',
					'%1$s thoughts on "%2$s"',
					get_comments_number(),
					'comments title',
					'mytheme'
				),
				number_format_i18n( get_comments_number() ),
				'<span>' . get_the_title() . '</span>'
			);
			?>
		</h4>
			
		<ol class="comment-list">
			<?php
			wp_list_comments( array(
				'style'       => 'ol',
				'short_ping'  => true,
				'avatar_size' => 74,
				'callback' => 'mytheme_list_comment',
				'max_depth' => 2,
			) );
			?>
		</ol><!-- .comment-list -->

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
			<nav class="navigation comment-navigation" role="navigation">

				<h1 class="screen-reader-text section-heading"><?php esc_html_e('Comment navigation', 'mytheme'); ?></h1>
				<div class="nav-previous"><?php previous_comments_link(esc_html__('&larr; Older Comments', 'mytheme')); ?></div>
				<div class="nav-next"><?php next_comments_link(esc_html__('Newer Comments &rarr;', 'mytheme')); ?></div>
			</nav><!-- .comment-navigation -->
		<?php endif; // Check for comment navigation ?>

		<?php if ( ! comments_open() && get_comments_number() ) : ?>
			<p class="no-comments"><?php esc_html_e('Comments are closed.', 'mytheme'); ?></p>
		<?php endif; ?>

	<?php endif; // have_comments() ?>

    <div class="contact_form">
        <?php



		$comments_args = array(
    
            'fields' => array(
                //Author field
				'author' => '<div class="form-group">
							<label for="author">' . esc_html__( 'Full Name :' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label>
							<input id="author" class="form-control" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" />
							</div>',
                //Email Field
				'email'  => '<div class="form-group">
							<label for="email">' . esc_html__( 'Email Address :' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label>
							<input id="email" class="form-control" name="email" type="email" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" aria-describedby="email-notes" />
							</div>',

            ),
			'comment_field'	=> '<div class="form-group comment-form-comment">
								<label for="comment">' . esc_html_x('Comment :', 'mytheme' ) . '</label>
								<textarea id="comment" class="form-textarea" name="comment" rows="3" aria-required="true" required="required"></textarea>
								</div>',
            // Change the title of send button
            'label_submit' => esc_html__('Send Message', 'mytheme'),
			'class_submit' => 'btn btn-default',
			'submit_field' => '<div class="section_sub_btn">%1$s %2$s</div>',
        );
        comment_form($comments_args);
        ?>
    </div>
</div><!-- #comments -->

