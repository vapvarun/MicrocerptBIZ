<?php get_header(); ?>
<?php templ_home_page_slider(); //HOME SLIDER ?>

<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/tabber.js" language="javascript"></script>

<div  class="<?php templ_content_css();?>" >
<div class="content_top"></div>
	<div class="content_bg">

<?php if (function_exists('dynamic_sidebar') && dynamic_sidebar('home_content')){?><?php } else {?>  <?php }?>
 

<?php 
global $paged,$exl_posts;

// jc commented out and added following lines
// $arg = array( 'paged' => $paged);
$arg = array( 'paged' => $paged, 'showposts' => 5);
$recentPosts = new WP_Query();
$recentPosts->query($arg);

/* jc commented out this
if(CUSTOM_POST_TYPE1)
{
$arg['post_type'] = CUSTOM_POST_TYPE1;
}
query_posts($arg); 
*/
?>
<div class="list clear ">

<?php
// dont show author info for parent site.
if ( get_current_blog_id() != 1 ) {
	author_info() ;
}
?>

<div class="tabber" id="tab1">
    <div class="tabbertab">
        <h2><?php _e('Latest Posts');?></h2>
           <?php include (TEMPLATEPATH . "/loop-listing.php"); ?>
		<?php wp_reset_query(); ?>
		
    </div>
    
    
    <div class="tabbertab last">
        <h2><?php _e('Popular Posts');?></h2>
         <?php
		 global $wpdb;
        $now = gmdate("Y-m-d H:i:s",time());
        $lastmonth = gmdate("Y-m-d H:i:s",gmmktime(date("H"), date("i"), date("s"), date("m")-12,date("d"),date("Y")));
        $popularposts = "SELECT *, post_title, COUNT($wpdb->comments.comment_post_ID) AS 'stammy' FROM $wpdb->posts, $wpdb->comments WHERE comment_approved = '1' AND $wpdb->posts.ID=$wpdb->comments.comment_post_ID AND post_status = 'publish' AND post_date < '$now' AND post_date > '$lastmonth' AND comment_status = 'open' GROUP BY $wpdb->comments.comment_post_ID ORDER BY stammy DESC ";
        $posts = $wpdb->get_results($popularposts);
        $popular = '';
        if($posts){
//		print_r ($posts);
            foreach($posts as $post){
                $post_title = stripslashes($post->post_title);
                   $guid = get_permalink($post->ID);
                      $first_post_title=substr($post_title,0,26);
        ?>
        
        
        
        <div <?php post_class('post'); ?>>
			<div class="left" style="visibility:xhidden">
					<?php 
					// echo 'post id:' . $post->ID;
					/*
					if(strtolower(get_option('ptthemes_vote'))=='registered user' || get_option('ptthemes_vote')==''){
						voting_author(c);
					}
					else{	
					$currentvotes = get_post_meta($post->ID, 'votes', true);
					if(!$currentvotes) $currentvotes = 1;
					
					if(!templ_check_post_like($post->ID)){?>
						<div class="vote" id="addlike<?php echo $post->ID;?>">
							<span class="span_currentvote"><?php echo $currentvotes; ?></span>
							<a class="hype_link" onclick="add_like('<?php echo $post->ID;?>');">Hype</a>
						</div>
						<?php
					}else{?>
						<div class="vote" id="addlike<?php echo $post->ID;?>">
							<span class="span_currentvote"><?php echo $currentvotes; ?></span>
							<span class="b_like_disable">Hype</span>
						</div>               
						<?php
						}
					}
					*/
					?>
					
					
					
			</div>
			
			<?php
			
			
			if(strtolower(get_option('ptthemes_image_position'))=='left' || get_option('ptthemes_image_position')==''){
				$display_style="left_img";
			}
			else if(strtolower(get_option('ptthemes_image_position'))=='right'){
				$display_style="right_img";
			}
			else if(strtolower(get_option('ptthemes_image_position'))=='display none'){
				$display_style="none_display";
			}
			if(get_post_meta($post->ID,"listing_img",true)){?>
			<a href="<?php the_permalink() ?>" class="<?php echo $display_style; ?>"><img src="<?php echo templ_thumbimage_filter(get_post_meta($post->ID,"listing_img",true),'&amp;w=121&amp;h=115&amp;zc=1&amp;q=80');?>" alt=""  /></a>
		<?php }
		else{ ?>
			<a href="<?php the_permalink() ?>" class="<?php echo $display_style; ?>"><img src="<?php echo bloginfo('template_directory');?>/images/no-image.png" alt="" title="No Image" border="3" /></a>
		<?php }
		?>	 
        
        <h3 style="visibility:hidden"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h3>
        
         
         <div class="post-meta">
             <?php if(templ_is_show_listing_author()){?>
              by <span class="post-author"> <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>" title="Posts by <?php the_author(); ?>">
              <?php the_author(); ?>
              </a> </span>
              <?php } ?>
            
            
            <?php if(templ_is_show_listing_date()){?>
              on <span class="post-date">
              <?php the_time(templ_get_date_format()) ?>
              </span>
              <?php } ?>
            
            
             <?php  if(templ_is_show_listing_category()){?>
            	<?php the_taxonomies(array('before'=>'<span class="post-category">', 'sep'=>'</span><span class="post-tags-none">','after'=>'</span>')); ?> 
              <?php } ?> 
        </div> <!-- post meta #end--> 
        
        
         <div class="post-content">
			<div class="quote_post">
				<div class="quote_body">				
					<?php 
						// only for non microcerpt posts (ie rss)
						if (! preg_match("/" . preg_quote(site_url(), '/') . "/", $post->guid)) { 
							$rss_post = true;
							// strip out html except p tags
							$post_content = strip_tags($post->post_content, '<p>');
							if (strlen($post_content) > 550 ) {
								echo mb_substrws($post_content, 550) . '...';
							} else {
								echo $post_content;
							}
						} else {
							// note changes this as below code was getting content for wrong post
							echo $post->post_content; 
							// templ_get_listing_content() 
							$rss_post = false;
						}
					?>
					</div>
					<div class="source">
						<p>
						<?php 
							if ($rss_post) { // only do read more for non microcerpt posts (ie rss)
								echo '<a href="' . esc_url( $post->guid ).'" target="_blank">Read More</a><br />';
							}
							$post_id = get_the_ID(); 
							$author = get_post_meta($post_id, 'Author', true);
							$source_type = get_post_meta($post_id, 'Source Type', true);
							$source_Link = get_post_meta($post_id, 'Source Link', true);
							$amazon_link = get_post_meta($post_id, 'Amazon link', true);
							$published_by = get_post_meta($post_id, 'Published by', true);
							if ($author) echo '<a href="' . site_url() .'">Author: ' .  $author .'</a><br />';
							if ($source_type) echo 'Source Type: '.$source_type.'<br />';
							if ($source_Link) echo '<a href="'.  $source_Link . '" target="_blank">Link to source</a><br />';
							if ($amazon_link) echo '<a href="'.  $amazon_link . '" target="_blank">Buy from Amazon</a><br />';
							if ($published_by) echo 'Published by: ' .  $published_by .'<br />';
							//wprp(true);
							reportpostemail ($post->guid);
							echo '</p>';
							if (function_exists('the_ratings')) {
								echo '<div style="float: left;">';
								the_ratings();
								echo '</div>';
							} 
				            if (function_exists('facebook_comments')) facebook_comments();
						?>
					</div>
				</div>			
		</div>
        
         
        
        <div class="post-meta clear-meta">
      
      <?php  if(templ_is_show_post_tags()){?>
            	<?php the_taxonomies(array('before'=>'<span class="post-category-none">', 'sep'=>'</span><span class="post-tags">','after'=>'</span>')); ?>
              <?php } ?> 
      
             <?php if(templ_is_show_listing_comment()){?>
         <span class="single_comments"> <?php comments_popup_link(__('0','templatic'), __('1','templatic'), __('%','templatic'), '', __('Closed','templatic')); ?></span>
          <?php } ?>
            
           <div class="post-share">
           
           <div class="addthis_toolbox addthis_default_style">
<a href="http://www.addthis.com/bookmark.php?v=250&amp;username=xa-4c873bb26489d97f" class="addthis_button_compact sharethis"><?php _e('Share');?></a>
</div>
           </div>
           <span class="post-view"><?php _e('Views : ');?><strong><?php echo user_post_visit_count($post->ID);?></strong></span>
            <?php
			if(get_post_meta($post->ID,"website",true) != ''){ ?>
				<span class="single-website"><a href="<?php echo get_post_meta($post->ID,"website",true); ?>" target="_blank">Website</a></span>	
			<?php
			}			
			?>
			
        </div> <!-- post meta #end-->
        
        
        
    
			 
		
		</div>
		
        <?php }
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