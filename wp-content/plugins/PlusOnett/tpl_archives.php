<?php
/*
Template Name: Page - Archives
*/
?>
<?php get_header(); ?>
<div  class="<?php templ_content_css();?>" >
	<div class="content_top"></div>
	<div class="content_bg">
<!--  CONTENT AREA START -->

<?php if ( have_posts() ) : ?>
<?php while ( have_posts() ) : the_post(); ?>
<?php $post_images = bdw_get_images($post->ID,'large'); ?>

<div class="entry">
  <div <?php post_class('single clear'); ?> id="post_<?php the_ID(); ?>">
    <div class="post-meta">
      <h1><?php the_title(); ?></h1>
     </div>
    <div class="post-content">
      <?php endwhile; ?>
      <?php endif; ?>
      
       <div class="post-content">
    	 <?php the_content(); ?>
    </div>
      
      
      <?php
        global $wpdb;
		$cdate = date('Y-m-d H:i:s');
        $years = $wpdb->get_results("SELECT DISTINCT MONTH(post_date) AS month, YEAR(post_date) as year FROM $wpdb->posts WHERE post_status = 'publish' and post_date <= \"$cdate\" and post_type = 'post' ORDER BY post_date DESC");
	if($years)
		{
			foreach($years as $years_obj)
			{
				$year = $years_obj->year;	
				$month = $years_obj->month;
				?>
      <?php query_posts("showposts=1000&year=$year&monthnum=$month"); ?>
      <div class="arclist">
        <h3> <?php echo  date('F', mktime(0,0,0,$month,1)).'-'. $year; ?> </h3>
        <ul>
          <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
          <li> <a href="<?php the_permalink() ?>">
            <?php the_title(); ?>
            </a> - <span class="arclist_date">
            <?php the_time('M j ') ?>
            </span> </li>
          <?php endwhile; endif; ?>
        </ul>
      </div>
      <?php
			}
		}
		 ?>
    </div>
  </div>
</div>



</div> <!-- content bg #end -->
    <div class="content_bottom"></div>
</div> <!-- content end -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>