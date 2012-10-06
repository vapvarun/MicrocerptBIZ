<?php get_header() ?>

<div id="content"><!-- start #content -->
	<div class="padder">
		<div class="page" id="blog-single"><!-- start #blog-single -->

			<?php if ( have_posts() ) : ?>

				<?php  while ( have_posts() ) : the_post(); ?>

				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

					<div class="entry-meta">
						<?php the_dr_posted_on(); ?>
						<?php do_action('sr_avg_rating'); ?><br />
						<span class="comments"><?php comments_popup_link( __( 'No Reviews &#187;', DR_TEXT_DOMAIN ), __( '1 Review &#187;', DR_TEXT_DOMAIN ), __( '% Reviews &#187;', DR_TEXT_DOMAIN ), '',  __( 'Reviews Off', DR_TEXT_DOMAIN) ); ?></span>
						<br />
						<?php the_dr_posted_in(); ?>
						<?php edit_post_link( __( 'Edit', DR_TEXT_DOMAIN ), '<span class="edit-link">', '</span>' ); ?>
						<span class="tags"><?php the_tags( __( 'Tags: ', DR_TEXT_DOMAIN ), ', ', ''); ?></span>

						<?php if ( !is_user_logged_in() ) : ?>
							<?php echo '<p class="must-log-in">' .  sprintf( __( 'You must be <a href="%s">logged in</a> to rate item.' ), wp_login_url( apply_filters( 'the_permalink', get_permalink( $post->ID ) ) ) ) . '</p>'; ?>
						<?php else: ?>
							<?php do_action('sr_rate_this'); ?>
						<?php endif; ?>
					</div>

					<div class="entry-post">
						<h1 class="entry-title"><?php the_title(); ?></h1>
						<div class="entry-content">
							<?php the_post_thumbnail( array( 275, 100 ), array( 'class' => 'alignleft' ) ); ?>
							<?php the_content(); ?>

							<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', DR_TEXT_DOMAIN ), 'after' => '</div>' ) ); ?>
						</div><!-- .entry-content -->
						<div class="clear"></div>
					</div>

					<?php if ( get_the_author_meta( 'description' ) ) : // If a user has filled out their description, show a bio on their entries  ?>
						<div id="entry-author-info">
							<div id="author-avatar">
								<?php echo get_avatar( get_the_author_meta( 'user_email' ), 60 ); ?>
							</div><!-- #author-avatar -->
							<div id="author-description">
								<h2><?php printf( esc_attr__( 'About %s', DR_TEXT_DOMAIN ), get_the_author() ); ?></h2>
								<?php the_author_meta( 'description' ); ?>
								<div id="author-link">
									<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
										<?php printf( __( 'View all posts by %s <span class="meta-nav">&rarr;</span>', DR_TEXT_DOMAIN ), get_the_author() ); ?>
									</a>
								</div><!-- #author-link	-->
							</div><!-- #author-description -->
						</div><!-- #entry-author-info -->
					<?php endif; ?>

				</div>

				<div id="commentbox">
					<?php comments_template('', true); ?>
				</div>

				<?php endwhile; ?>

				<div id="post-navigator">
					<div class="alignleft"><?php next_posts_link( __( '&laquo; Previous Entries', DR_TEXT_DOMAIN ) ) ?></div>
					<div class="alignright"><?php previous_posts_link( __( 'Next Entries &raquo;', DR_TEXT_DOMAIN ) ) ?></div>
				</div>

			<?php else : ?>

				<h3><?php _e("Sorry, we can't find the post you're looking for at this URL. Please try selecting a menu item from above or to the side of this message to get where you'd like to go.", DR_TEXT_DOMAIN); ?></h3>

			<?php endif; ?>

		</div><!-- end #blog-single -->
	</div>
</div><!-- end #content -->

<?php get_footer() ?>
