<?php get_header(); ?>
<div  class="<?php templ_content_css();?>" >
<div class="content_top"></div>
	<div class="content_bg">
<!--  CONTENT AREA START -->

<?php if ( have_posts() ) : ?>
<?php while ( have_posts() ) : the_post(); ?>


<div class="entry">
  <div <?php post_class('single clear'); ?> id="post_<?php the_ID(); ?>">
    <div class="post-meta">
      <?php templ_page_title_above(); //page title above action hook?>
      <?php echo templ_page_title_filter(get_the_title()); //page tilte filter?>
      <?php templ_page_title_below(); //page title below action hook?>
    </div>
    <div class="post-content">
      <?php the_content(); ?>
    </div>
   </div>
</div>
<?php endwhile; ?>
<?php endif; ?>

		</div> <!-- content bg #end -->
    <div class="content_bottom"></div>
</div> <!-- content end -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>