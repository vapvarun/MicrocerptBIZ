<?php
/*
Template Name: RSS content
*/
?>
<?php get_header(); ?>
<div  class="<?php templ_content_css();?>" >
<div class="content_top"></div>
	<div class="content_bg">
<!--  CONTENT AREA START -->


<?php get_rss ('http://www.midcurrent.com/news/index.rdf', 'some title') ?>



		</div> <!-- content bg #end -->
    <div class="content_bottom"></div>
</div> <!-- content end -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>