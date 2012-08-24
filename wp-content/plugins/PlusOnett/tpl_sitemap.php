<?php
/*
Template Name: Page - Sitemap
*/
?>
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
      <h1><?php the_title(); ?></h1>
     </div>
    <div class="post-content">
    
    <?php the_content(); ?>
    
      <div class="arclist">
        <h3><?php _e('Pages','templatic');?></h3>
        <ul>
          <?php wp_list_pages('title_li='); ?>
        </ul>
      </div>
      <!--/arclist -->
      <div class="arclist">
        <h3><?php _e('Posts','templatic');?></h3>
        <ul>
          <?php $archive_query = new WP_Query('showposts=60');
            while ($archive_query->have_posts()) : $archive_query->the_post(); ?>
          <li><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>">
            <?php the_title(); ?>
            </a> <span class="arclist_comment">
            <?php comments_number(__('0 comment','templatic'), __('1 comment','templatic'),__('% comments','templatic')); ?>
            </span></li>
          <?php endwhile; ?>
        </ul>
      </div>
      <!--/arclist -->
      <div class="arclist">
        <h3><?php _e('Archives','templatic');?></h3>
        <ul>
          <?php wp_get_archives('type=monthly'); ?>
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
      <div class="arclist">
        <h3><?php _e('Meta','templatic');?></h3>
        <ul>
          <li><a href="<?php bloginfo('rdf_url'); ?>" title="RDF/RSS 1.0 feed">
          RDF/<?php _e('RSS','templatic')?> <?php _e('1.0 feed','templatic')?></a></li>
          <li><a href="<?php bloginfo('rss_url'); ?>" title="RSS 0.92 feed"><?php _e('RSS','templatic')?> <?php _e('0.92 feed','templatic')?></a></li>
          <li><a href="<?php bloginfo('rss2_url'); ?>" title="RSS 2.0 feed"><?php _e('RSS','templatic')?><?php _e('2.0 feed','templatic')?></a></li>
          <li><a href="<?php bloginfo('atom_url'); ?>" title="Atom feed"><?php _e('Atom feed','templatic')?></a></li>
        </ul>
      </div>
      <!--/arclist -->
	   <!--/arclist -->
      <div class="arclist">
        <h3><?php _e('Articles','templatic');?></h3>
		 <ul>
	   <?php
		global $wpdb;
		
        $popularposts = "SELECT * FROM $wpdb->posts WHERE post_status = 'publish' and post_title!='' and post_type='".CUSTOM_POST_TYPE1."' order by ID desc";
        $posts = $wpdb->get_results($popularposts);
        $popular = '';
        if($posts){
            foreach($posts as $post){
                $post_title = stripslashes($post->post_title);
                $guid = get_permalink($post->ID);
                
        ?>			
        <li><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>">
            <?php the_title(); ?>
            </a> <span class="arclist_comment">
            <?php comments_number(__('0 comment','templatic'), __('1 comment','templatic'),__('% comments','templatic')); ?>
            </span></li>		
        <?php }
		} 
		?>
		</ul>
      </div>
     </div>
    <div class="post-footer">
      <?php the_tags(__('<strong>Tags: </strong>','templatic'), ', '); ?>
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