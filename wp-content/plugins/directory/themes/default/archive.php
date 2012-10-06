<?php get_header(); ?>

<div id="content"><!-- start #content -->
	<div class="padder">
		<div class="page" id="blog-archives"><!-- start #blog-archives -->
			<?php locate_template( array( '/components/headers.php' ), true ); ?>

			<?php if ( have_posts() ) : ?>
				<?php locate_template( array( '/loops/loop-excerpt.php' ), true ); ?>
				<?php locate_template( array( '/components/pagination.php' ), true ); ?>
			<?php else: ?>
				<?php locate_template( array( '/components/messages.php' ), true ); ?>
			<?php endif; ?>

		</div><!-- end #blog-archives -->
	</div>
</div><!-- end #content -->

<?php get_footer(); ?>
