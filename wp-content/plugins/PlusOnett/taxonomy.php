<?php get_header(); 
global $wp_query, $post;
$current_term = $wp_query->get_queried_object();
?>

<?php get_header(); ?>
<div  class="<?php templ_content_css();?>" >
<div class="content_top"></div>
	<div class="content_bg">
<!--  CONTENT AREA START -->


		 <?php templ_page_title_above(); //page title above action hook?>
  <div class="content-title">
       <h1> <?php echo $current_term->name; ?> </h1>
   <?php echo templ_page_title_filter($ptitle); //page tilte filter?> <a href="javascript: void(0);" id="mode"<?php if ($_COOKIE['mode'] == 'grid') echo ' class="flip"'; ?>></a> </div>
    <?php templ_page_title_below(); //page title below action hook?>             
    
    
     
     
      
<?php
$category_id_arr = array();
global $wp_query, $post;
$current_term = $wp_query->get_queried_object();
$category_id_arr[] = $current_term->term_id;
?>    
      
   
     <?php include (TEMPLATEPATH . "/loop-listing.php"); ?>
      
  </div> <!-- content bg #end -->
    <div class="content_bottom"></div>
</div> <!-- content end -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>