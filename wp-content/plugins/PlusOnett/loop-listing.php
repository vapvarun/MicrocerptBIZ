<?php templ_before_loop(); // before loop hooks?>
<?php if ( have_posts() ) : ?>
<div id="loop" class="<?php if ($_COOKIE['mode'] == 'grid') echo 'grid'; else echo 'list clear'; ?> ">
  <?php 
	$pcount=0; 
	while ( have_posts() ) : the_post(); 
	$pcount++;
	?>
    
     
  <div <?php post_class('post'); ?> id="post_<?php the_ID(); ?>">
    <div class="left">
	<?php 
	/*
	if(strtolower(get_option('ptthemes_vote'))=='registered user' || get_option('ptthemes_vote')==''){
		voting($post->ID); 
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
			<a href="<?php the_permalink() ?>" class="<?php echo $display_style; ?>"><img src="<?php echo bloginfo('template_directory');?>/images/no-image.png" alt="" title="No Image" /></a>
		<?php }
		?>				           
        <!--  Post Title Condition for Post Format-->

         <!--  Post Title Condition for Post Format-->
                                    
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
                                     
    <?php templ_before_loop_post_content(); // before loop post content hooks?>

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
					templ_get_listing_content();
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
				$source_title = get_post_meta($post_id, 'Title', true);
				$source_type = get_post_meta($post_id, 'Source Type', true);
				$source_Link = get_post_meta($post_id, 'Source Link', true);
				$amazon_link = get_post_meta($post_id, 'Amazon link', true);
				$published_by = get_post_meta($post_id, 'Published by', true);
				if ($author) echo '<a href="' . site_url() .'">Author: ' .  $author .'</a><br />';
				if ($source_title) echo 'Title: ' .  $source_title .'<br />';
				if ($source_type) echo 'Source Type: '.$source_type.'<br />';
				if ($source_Link) echo '<a href="'.  $source_Link . '" target="_blank">Link to source</a><br />';
				if ($amazon_link) echo '<a href="'.  $amazon_link . '" target="_blank">Buy from Amazon</a><br />';
				if ($published_by) echo 'Published by: ' .  $published_by .'<br />';
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
	  
    <!--  Post Content Condition for Post Format-->
     <?php templ_after_loop_post_content(); // after loop post content hooks?>
  </div>
		<?php 
        $page_layout = templ_get_page_layout();
        if($page_layout=='full_width'){
                    if($pcount==3){
                    $pcount=0; 
                    ?>
                        <div class="hr clearfix"></div>
                <?php } }
                else if(($page_layout=='3_col_fix' ) || ($page_layout=='3_col_right') ||( $page_layout=='3_col_left')){
                    if($pcount==2){
                    $pcount=0; 
                    ?>
                        <div class="hr clearfix"></div>
                <?php }
                }
                else if ($pcount==2){
                    $pcount=0; 
                    ?>
                <div class="hr clearfix"></div>
                <?php }?>
        
  <?php endwhile; ?>
  <?php get_template_part('pagination'); ?>
</div>
<?php else : ?>	
<?php //get_search_form(); ?>
<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'templatic' ); ?></p>
<?php endif; ?>
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=xa-4c873bb26489d97f"></script>
<?php templ_after_loop(); // after loop hooks?>