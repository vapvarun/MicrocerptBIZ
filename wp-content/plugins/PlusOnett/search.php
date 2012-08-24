<?php get_header(); ?>
<div  class="<?php templ_content_css();?>" >
<div class="content_top"></div>
	<div class="content_bg">
<!--  CONTENT AREA START -->

<?php if ( have_posts() ) : ?>
<?php templ_page_title_above(); //page title above action hook?>

<div class="content-title">
  <?php ob_start(); // don't remove this code?>
  <?php if($_REQUEST['catdrop']) _e('Search Result for category','templatic'); elseif($_REQUEST['todate'] || $_REQUEST['frmdate']) _e('Search Result for date','templatic'); elseif($_REQUEST['articleauthor']) _e('Search Result for author','templatic'); else _e('Search Result for ','templatic');?>
  <?php the_search_query(); ?>
  <?php
        $page_title = ob_get_contents(); // don't remove this code
		ob_end_clean(); // don't remove this code
		?>
  <?php echo templ_page_title_filter($page_title); //page tilte filter?> <a href="javascript: void(0);" id="mode"<?php if ($_COOKIE['mode'] == 'grid') echo ' class="flip"'; ?>></a> </div>
<?php templ_page_title_below(); //page title below action hook?>
<?php get_template_part('loop'); ?>
<?php else : ?>

<div class="content-title">
  <?php ob_start(); // don't remove this code?>
  <?php if($_REQUEST['catdrop']) _e('Search Result for category','templatic'); elseif($_REQUEST['todate'] || $_REQUEST['frmdate']) _e('Search Result for date','templatic'); elseif($_REQUEST['articleauthor']) _e('Search Result for author','templatic'); else _e('Search Result for ','templatic');?>
  <?php the_search_query(); ?>
  <?php
        $page_title = ob_get_contents(); // don't remove this code
		ob_end_clean(); // don't remove this code
		?>
  <?php 
   echo templ_page_title_filter($page_title); //page tilte filter?>
   </div>
<div class="entry">
  <div class="single clear">
    <div class="post-content">
       		<?php get_search_form(); ?> 
            <p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'templatic' ); ?></p>
            
            
            <div class="arclist">
        <h3><?php _e('Archives','templatic');?></h3>
        <ul>
          <?php wp_get_archives('type=monthly&show_post_count=true'); ?>
        </ul>
      </div>
      <!--/arclist -->
      
      
      <div class="arclist">
        <h3><?php _e('Categories','templatic');?></h3>
        <ul>
          <?php wp_list_categories('title_li=&hierarchical=0&show_count=1') ?>
        </ul>
      </div>
      <!--/arclist -->
            
     </div>
  </div>
</div>
<?php endif; ?>
<?php get_template_part('pagination'); ?>


</div> <!-- content bg #end -->
    <div class="content_bottom"></div>
</div> <!-- content end -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>